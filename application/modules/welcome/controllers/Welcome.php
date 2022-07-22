<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        
        User::logged_in();

        if (User::is_staff()) {
            redirect('collaborator');
        }
        if (User::is_client()) {
            redirect('clients');
        }
        if(isset($_GET['setyear'])){ $this->session->set_userdata('chart_year', $_GET['setyear']); }
        if(isset($_GET['chart'])){ $this->session->set_userdata('chart', $_GET['chart']); }

        $this->load->model(array('Project','App','Invoice','Client','Payment','Ticket','Report','Expense'));

    }

    function index()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(config_item('company_name'));
    $data['page'] = lang('home');
    $data['projects']   = Project::all();
    $data['activities'] = App::get_activity($limit = 30);
    // $data['fullcalendar'] = TRUE;
    $data['datatables'] = TRUE;
    $data['sums'] = $this->_totals();
    $data['sums2'] = $this->_totals_per_currency();
    $data['present_absent_count'] = $this->today_present_absent_emp();
    if(App::counter('items',array()) == 0){
        $data['no_invoices'] = TRUE;
    }
    $this->template
    ->set_layout('users')
    ->build('user_home',isset($data) ? $data : NULL);
    }
        
    function _totals() {
            $paid = $due = array();
            $currency = config_item('default_currency');
            $symbol = array();
            $paid = $due = 0;
            foreach(Invoice::get_invoices() as $inv) {
                $paid_amount = Invoice::get_invoice_paid($inv->inv_id);
                $due_amount = Invoice::get_invoice_due_amount($inv->inv_id);
                if ($inv->currency != $currency) {
                    $paid_amount = Applib::convert_currency($inv->currency, $paid_amount);
                    $due_amount = Applib::convert_currency($inv->currency, $due_amount);
                }
                $paid += $paid_amount;
                $due += $due_amount;
            }
            return array("paid"=>$paid, "due"=>$due);
        
        }
        
    function _totals_per_currency() {
            $paid = $due = array();
            foreach(Invoice::get_invoices() as $inv) {
                $paid_amount = Invoice::get_invoice_paid($inv->inv_id);
                $due_amount = Invoice::get_invoice_due_amount($inv->inv_id);
                if (!isset($paid[$inv->currency])) { $paid[$inv->currency] = 0; }
                if (!isset($due[$inv->currency])) { $due[$inv->currency] = 0; }
                $paid[$inv->currency] += $paid_amount;
                $due[$inv->currency] += $due_amount;
            }
            return array("paid"=>$paid, "due"=>$due);
        
        }

    
     public function today_present_absent_emp()
    {
        date_default_timezone_set('Asia/Kolkata');
        $punch_in_date = date('Y-m-d');
        $punch_in_time = date('H:i');
        $punch_in_date_time = date('Y-m-d H:i');

        $strtotime = strtotime($punch_in_date_time);
        $a_year    = date('Y',$strtotime);
        $a_month   = date('m',$strtotime);
        $a_day     = date('d',$strtotime);
        $a_days     = date('d',$strtotime);
        $a_dayss     = date('d',$strtotime);
        $a_cin     = date('H:i',$strtotime);
        // $subdomain_id     = $this->session->userdata('subdomain_id');
        // $branch_id = $this->session->userdata('branch_id');

        // if($branch_id != '') {
        //     $this->db->where("u.branch_id IN (".$branch_id.")",NULL, false);
        // }

        $record = $this->db->select('ad.*')
                    ->from('attendance_details ad')
                    ->join('users u', 'u.id=ad.user_id')
                    ->where(array('a_month'=>$a_month,'a_year'=>$a_year))
                    ->get()
                    ->result_array();

        if(!empty($record)){
            foreach ($record as $key => $value) {
              $all_user_id[] = $value['user_id'];
            }
        }
        $all_user_id =  array_unique($all_user_id);

        $today_present = 0;
        $today_absent = 0;
        $today_late = 0;

        foreach ($all_user_id as $key => $value) {
            if($value !=1){
                
                $user_id = $value;
                $where     = array('ad.user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
               
                $results = $this->db->select('ad.*')
                            ->from('attendance_details ad')
                            ->join('users u', 'u.id=ad.user_id', 'left')
                            ->where($where)
                            ->get()
                            ->result_array();
                
                foreach ($results as $rows) {
                    $current_date=date('Y-m-d');
                    $current_day =date('d');
                    $current_month =date('m');
                    $current_year =date('Y');                      
                    $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$current_date);
                    $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                    $shift =  $this->db->get_where('shifts',array('id' => $user_schedule['shift_id']))->row_array(); 

                    if(!empty($rows['month_days'])){
                        $month_days =  unserialize($rows['month_days']);
                        $month_days_in_out =  unserialize($rows['month_days_in_out']);
                        $day = $month_days[$current_day-1];
                        $day_in_out = $month_days_in_out[$current_day-1];
                        $latest_inout = end($day_in_out);
                        if(!empty($user_schedule)){
                           
                    
                            if(!empty($day['punch_in']))
                            {                                   
                               $today_present++;    

                                    $later_entry_minutes = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['max_start_time'],$schedule_date.' '.$day['punch_in']);


                                    if($later_entry_minutes > 0){
                                        $today_late++;
                                    }                           
                            } else {
                                
                                $today_absent++;
                            }
                        }else{
                              if(!empty($day['punch_in']))
                            {                                   
                               $today_present++;    

                                                      
                            }
                        } 
                    }
                }
            }
            
        }
        $data['today_absent'] = $today_absent; 
        $data['today_present'] = $today_present; 
        return $data;
       // echo 'record<pre>'; print_r($today_absent); exit;
        }


    }

/* End of file welcome.php */