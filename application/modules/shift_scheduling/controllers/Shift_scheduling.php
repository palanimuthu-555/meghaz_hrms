<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shift_scheduling extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        // if($this->session->userdata('role_id') != 1){
        //     redirect();
        // }
			

		

        $this->load->helper('security');
        $this->load->model(array('Client','App','Lead','Users'));
        // $this->load->model('Shift_scheduling','shift_scheduling');        
        $this->load->model('Shift_scheduling_model','shift_scheduling');
		if(!App::is_access('menu_shift_scheduling'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}	
    }
    function index(){
        $this->active();
    }

    function active()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
    $data['page'] = lang('shift_scheduling');
    $data['sub_page'] = lang('shift_scheduling');
    $data['datatables'] = TRUE;
    $data['form'] = TRUE;
    $data['country_code'] = TRUE;
    $data['daterangepicker'] = TRUE;
   
    if($_POST){
     // echo "<pre>"; print_r($_POST); exit()   ;
        $data['username']= $_POST['username'];
        $data['department_id']= $_POST['department_id'];
        $data['schedule_date']= $_POST['schedule_date'];
        $data['week']= $_POST['week'];
        // $schedules_date = explode('-', $_POST['schedule_date']);
        
        // echo $diff; exit;
        $this->db->select('U.id as user_id,S.*,D.designation,A.fullname');
        $this->db->from('shift_scheduling S');
        $this->db->join('users U','U.id = S.employee_id','LEFT');
        $this->db->join('account_details A','A.user_id = U.id','LEFT');
        $this->db->join('designation D','D.id = U.designation_id','LEFT');
        // $this->db->where('S.published','1');
        if($_POST['username'] !=''){
            $this->db->like('A.fullname', $_POST['username']);
        }
        if($_POST['department_id'] !=''){
            $this->db->where('U.department_id', $_POST['department_id']);
        }
        $this->db->group_by('S.employee_id');
        $data['shift_scheduling'] = $this->db->get()->result_array();
         // echo "<pre>";print_r($data['shift_scheduling']); exit;
    } else {
        // echo 1; exit;
        $this->db->select('U.id as user_id,S.*,D.designation,A.fullname');
        $this->db->from('shift_scheduling S');
        $this->db->join('users U','U.id = S.employee_id','LEFT');
        $this->db->join('account_details A','A.user_id = U.id','LEFT');
        $this->db->join('designation D','D.id = U.designation_id','LEFT');
        // $this->db->where('S.published','1');
        $this->db->group_by('S.employee_id');
        $data['shift_scheduling'] = $this->db->get()->result_array();
    }
    
    $this->template
    ->set_layout('users')
    ->build('daily_schedule',isset($data) ? $data : NULL);
    }

    function shift_list()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
    $data['page'] = lang('shift_scheduling');
    $data['sub_page'] = lang('shift_scheduling');
    $data['datatables'] = TRUE;
    $data['form'] = TRUE;
    $data['country_code'] = TRUE;
    $data['datepicker'] = TRUE;     
    $data['shifts'] = $this->db->get_where('shifts')->result_array(); 
   // echo "<pre>";print_r($data['shifts']); exit();
    $this->template
    ->set_layout('users')
    ->build('shift_list',isset($data) ? $data : NULL);
    }

    function add_shift()
    {
        if($_POST){

            //print_r($_POST);exit; 
                       
            $week_days = isset($_POST['week_days'])?$_POST['week_days']:'';
            if(!empty($week_days)){
                $week_days = implode(',',$week_days);
            }
            $workdays = isset($_POST['workdays'])?$_POST['workdays']:'';
            if(!empty($workdays)){
                $workday = count($_POST['workdays']);
            }
               // echo "<pre>";print_r($_POST); exit();

            $_POST['start_date'] = isset($_POST['start_date'])?date('Y-m-d',strtotime($_POST['start_date'])):'';
            $_POST['end_date'] = isset($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
            $shift_details = array(            
            'shift_name' => $_POST['shift_name'],
            'group_id' => isset($_POST['group_id'])?$_POST['group_id']:0,
            'start_date' => $_POST['start_date'],
           // 'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
            'start_time' => date("H:i", strtotime($_POST['start_time'])),
           // 'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
            //'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
            'end_time' => date("H:i", strtotime($_POST['end_time'])),
            //'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
            'break_time' => isset($_POST['break_time'])?$_POST['break_time']:0,
            'color' => isset($_POST['color'])?$_POST['color']:'#235ca5',
            'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
            'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
            'no_of_days_in_cycle' => isset($_POST['no_of_days_in_cycle'])?$_POST['no_of_days_in_cycle']:0,
            'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
            'workday' => isset($workday)?$workday:0,
            'week_days' => isset($week_days)?$week_days:'',
            'end_date' =>$_POST['end_date'],
            'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
            'tag' => $_POST['tag'],
            'note' => $_POST['note'],
            'created_by' => $this->session->userdata('user_id'),
            'subdomain_id' => $this->session->userdata('user_id'),
            'published' => 1

            );
                            //echo "<pre>";print_r($shift_details); exit();
            $this->db->insert('shifts',$shift_details);
            // echo $this->db->last_query(); exit;
            $shift_id =$this->db->insert_id();
            $data = array(
                'module' => 'shift_scheduling',
                'module_field_id' => $shift_id,
                'user' => $this->session->userdata('user_id'),
                'activity' => 'New Shift Scheduled',
                'icon' => 'fa-plus',
                'value1' => $cur.' '.$this->input->post('shift_name')
                );
            App::Log($data);
        $this->session->set_flashdata('tokbox_success', lang('shift_created_successfully'));
            
            
        redirect('shift_scheduling/shift_list');
        
    }else{            
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
            $data['page'] = lang('shift_scheduling');
            $data['sub_page'] = lang('shift_scheduling');
            $data['datatables'] = TRUE;
            $data['datepicker'] = TRUE;            
            // $data['daterangepicker'] = TRUE;
            $data['form'] = TRUE;     
            $this->template
            ->set_layout('users')
            ->build('add_shift',isset($data) ? $data : NULL);
        }
    
    }

     function edit_shift()
    {
        if($_POST){

             // echo "<pre>"; print_r($_POST); exit;
                       
           $week_days = isset($_POST['week_days'])?$_POST['week_days']:'';
            if(!empty($week_days)){
                $week_days = implode(',',$week_days);
            }
            $workdays = isset($_POST['workdays'])?$_POST['workdays']:'';
            if(!empty($workdays)){
                $workday = count($_POST['workdays']);
            }
            if(isset($_POST['cyclic_shift'])){
                $no_of_days_in_cycle = isset($_POST['no_of_days_in_cycle'])?$_POST['no_of_days_in_cycle']:0;
            } else{
                $no_of_days_in_cycle = 0;
            }
            $_POST['start_date'] = isset($_POST['start_date'])?date('Y-m-d',strtotime($_POST['start_date'])):'';
            $_POST['end_date'] = isset($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
            $shift_details = array(            
            'shift_name' => $_POST['shift_name'],
            'group_id' => isset($_POST['group_id'])?$_POST['group_id']:0,
            'start_date' => $_POST['start_date'],
            //'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
            'start_time' => date("H:i", strtotime($_POST['start_time'])),
            //'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
            //'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
            'end_time' => date("H:i", strtotime($_POST['end_time'])),
            //'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
            'break_time' => isset($_POST['break_time'])?$_POST['break_time']:0,
             'color' => isset($_POST['color'])?$_POST['color']:'#235ca5',
            'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
            'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
            'no_of_days_in_cycle' => $no_of_days_in_cycle,
            'workday' => isset($workday)?$workday:0,
            'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
            'week_days' => $week_days,   
            'end_date' =>$_POST['end_date'],
            'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
            'tag' => $_POST['tag'],
            'note' => $_POST['note'],
            'created_by' => $this->session->userdata('user_id'),
            'subdomain_id' => $this->session->userdata('user_id'),
            'published' => 1

            );
            // echo "<pre>";print_r($shift_details); exit();
            $this->db->where('id',$_POST['id']);
            $this->db->update('shifts',$shift_details);
            $shift_id =$this->db->insert_id();
            //update the shift_scheduling
            $shift_scheduling = array(    
           // 'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
            'start_time' => date("H:i", strtotime($_POST['start_time'])),
           // 'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
            //'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
            'end_time' => date("H:i", strtotime($_POST['end_time'])),
            //'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
            'break_time' => isset($_POST['break_time'])?$_POST['break_time']:0,
            'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
            'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
            'week_days' => $week_days,
            'end_date' =>$_POST['end_date'],
            'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0
            );
                             // echo "<pre>";print_r($shift_details); exit();
            $this->db->where('shift_id',$_POST['id']);
            $this->db->where('schedule_date >',date('Y-m-d'));
            $this->db->update('shift_scheduling',$shift_scheduling);
            // end update the shift_scheduling
            $data = array(
                'module' => 'shift_scheduling',
                'module_field_id' => $shift_id,
                'user' => $this->session->userdata('user_id'),
                'activity' => 'Updated Shift Scheduled',
                'icon' => 'fa-plus',
                'value1' => $cur.' '.$this->input->post('shift_name')
                );
            App::Log($data);
        $this->session->set_flashdata('tokbox_success', lang('shift_edited_successfully'));
            
            
        redirect('shift_scheduling/shift_list');
        
    }else{            
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
            $data['page'] = lang('shift_scheduling');
            $data['sub_page'] = lang('shift_scheduling');
            $data['datatables'] = TRUE;
            $data['datepicker'] = TRUE;            
            // $data['daterangepicker'] = TRUE;
            $id= $this->uri->segment(3);
            $data['shift_details'] = $this->db->get_where('shifts',array('id'=>$id))->row_array(); 
            $data['form'] = TRUE;     
            $this->template
            ->set_layout('users')
            ->build('edit_shift',isset($data) ? $data : NULL);
        }
    
    }

    function get_shift_by_id(){
        

        if($this->input->post()){
            $id = $this->input->post('id');           
            $record = $this->db->get_where('shifts',array('id'=>$id))->row_array(); 
             $records['start_date'] =($record['start_date'] != '0000-00-00')?date('d-m-Y',strtotime($record['start_date'])):date('d-m-Y',time()) ;
            $records['min_start_time'] = date('h:i a', strtotime($record['min_start_time']));
            $records['start_time'] = date('h:i a', strtotime($record['start_time']));
            $records['max_start_time'] = date('h:i a', strtotime($record['max_start_time']));
            $records['min_end_time'] = date('h:i a', strtotime($record['min_end_time']));
            $records['end_time'] = date('h:i a', strtotime($record['end_time']));
            $records['max_end_time'] = date('h:i a', strtotime($record['max_end_time']));
            $records['break_time'] = $record['break_time'];
            $records['recurring_shift'] = $record['recurring_shift'];
            $records['cyclic_shift'] = $record['cyclic_shift'];
            $records['no_of_days_in_cycle'] = $record['no_of_days_in_cycle'];
            $records['workday'] = $record['workday'];
            $records['repeat_week'] = $record['repeat_week'];
            $records['week_days'] = $record['week_days'];
            $records['end_date'] =($record['end_date'] != '0000-00-00')?date('d-m-Y',strtotime($record['end_date'])):date('d-m-Y',time()) ;
            $records['indefinite'] = $record['indefinite'];
            echo json_encode($records);
            die();
        }
    }
    
    function delete_shift($id = NULL)
    {
        if ($this->input->post()) {

            $id = $this->input->post('id', TRUE);            

            App::delete('activities',array('module'=>'shift_scheduling', 'module_field_id' => $id));
            //delete ticket
            App::delete('shifts',array('id'=>$id));

            // Applib::go_to('tickets','success',lang('ticket_deleted_successfully'));
            $this->session->set_flashdata('tokbox_success', lang('shift_deleted_successfully'));
            redirect('shift_scheduling/shift_list');

        }else{
            $data['id'] = $id;
             // echo  $id; exit;

            $this->load->view('modal/delete_shift',$data);
        }
    }
    function dept_emp(){
        if($this->input->post()){
            $depart_id = $this->input->post('department');

            $this->db->select('US.id as user_id,AD.fullname');
            $this->db->from('users US');
            $this->db->join('dgt_account_details AD','US.id = AD.user_id');
            $this->db->where('US.department_id', $depart_id);
            $this->db->order_by("AD.fullname", "asc");
            $records = $this->db->get()->result_array();
            echo json_encode($records);
            die();
        }
    }

    function add_schedule()
    {
        if($_POST){
             $employees = $_POST['employee'];
                 //echo "<pre>";print_r($_POST); 
                  if (count($employees) > 0) {
                    //echo 123;exit; 
                     
                    $exist_schedule_count= 1;
                    foreach ($employees as $key => $value) {
                        $this->db->where('employee_id',$value);
                        $this->db->where('schedule_date >=',date('Y-m-d',strtotime($_POST['schedule_date'])));
                        if($_POST['indefinite'] != 1 && empty($_POST['cyclic_shift'])){                            
                            $this->db->where('schedule_date <=',date('Y-m-d',strtotime($_POST['end_date'])));
                        }
                        $this->db->delete('shift_scheduling');
                         // echo   $this->db->last_query(); exit;
                        $d = $_POST['week_days'];
                        $leaveDay = 0; 
                        $weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                        $week_days = isset($_POST['week_days'])?$_POST['week_days']:'';
                        if(!empty($week_days)){
                            $week_days = implode(',',$week_days);
                        }
                        if(isset($_POST['single_insert'])){
                               // echo "<pre>";print_r($_POST); exit();
                            $_POST['end_date'] = !empty($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
                            $_POST['schedule_date'] = date('Y-m-d',strtotime($_POST['schedule_date']));
                             if(!empty($_POST['end_date'])){
                                $end_schedulde_date= $_POST['end_date'];
                                $end_schedulde_date= date('Y-m-d',strtotime('+1 day', strtotime($_POST['end_date'])));
                          } else {
                            $end_schedulde_date= date('Y-m-d',strtotime('+120 day'));
                          }
                         
                           // echo $end_schedulde_date; exit();
                          
                        
                        $begin = new DateTime($_POST['schedule_date']);
                            $end = new DateTime($end_schedulde_date);

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);
                             
                             // echo $employee_shifts; exit;
                             // echo "<pre>";
                            foreach ($period as $dt) {

                               
                                $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>$dt->format("Y-m-d")))->row_array();
                                
                             // echo $employee_shifts; exit;
                               if(empty($employee_shifts)){
                                    $shift_details = array(
                                    'dept_id' => $_POST['department'],
                                    'employee_id' => $value,
                                    'schedule_date' => $dt->format("Y-m-d"),
                                    'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                    'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                    'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                    'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                    'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                    'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                    'break_time' => $_POST['break_time'],
                                    'shift_id' => $_POST['shift_id'],
                                   // 'color' => $_POST['color'],
                                    'accept_extras' => 1,
                                    'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                    'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
                                    'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                    'week_days' => $week_days,
                                    // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                    // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                    // 'schedule_repeat' => $_POST['repeat_time'],
                                    // 'tag' => $_POST['tag'],
                                    // 'note' => $_POST['note'],
                                    'created_by' => $this->session->userdata('user_id'),
                                    'subdomain_id' => $this->session->userdata('user_id'),
                                    'published' => 1

                                    );
                                 // echo "<pre>";print_r($shift_details); exit();
                                $this->db->insert('shift_scheduling',$shift_details);


                                $shift_id =$this->db->insert_id();
                                $exist_count == 0;
                            }
                        }
                            //  else {
                                
                            //     $exist_date = $_POST['schedule_date'];
                            //     $exist_count += $exist_schedule_count;
                            // }
                    } else {
                      
                          // $end_schedulde_date= date('Y-m-d',strtotime('+'.$_POST["repeat_week"].' week', strtotime($_POST['schedule_date'])));
                          if(!empty($_POST['end_date'])){
                                $end_schedulde_date= $_POST['end_date'];
                                $end_schedulde_date= date('Y-m-d',strtotime('+1 day', strtotime($_POST['end_date'])));
                          } else {
                            $end_schedulde_date= date('Y-m-d',strtotime('+120 day'));
                          }
                         
                           // echo $end_schedulde_date; exit();
                          
                        $_POST['end_date'] = !empty($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
                        // $repeat_time = $_POST['repeat_time'] * 7;
                         // echo $start_date .' '.$maxDays; exit;
                        if(isset($_POST["recurring_shift"]) && !empty($_POST["recurring_shift"])){
                            $begin = new DateTime($_POST['schedule_date']);
                            $end = new DateTime($end_schedulde_date);

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);
                            $k=0;

                           // echo "<pre>";print_r($period); exit;       
                            foreach ($period as $dt) {
                                if(in_array(lcfirst($dt->format("l")), $_POST['week_days'])){
                                    // echo $dt->format("l Y-m-d H:i:s\n");
                                    // echo "<pre>";print_r($_POST); exit;
                               
                                    $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>$dt->format("Y-m-d")))->row_array();
                                   
                                    
                                    if(empty($employee_shifts)){
                                         $shift_details = array(
                                        'dept_id' => $_POST['department'],
                                        'employee_id' => $value,
                                        'schedule_date' => $dt->format("Y-m-d"),
                                        'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                        'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                        'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                        'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                        'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                        'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                        'break_time' => $_POST['break_time'],
                                        'shift_id' => $_POST['shift_id'],
                                       // 'color' => $_POST['color'],
                                        'accept_extras' => 1,
                                        'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                        'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                        'week_days' => $week_days,                                    
                                        'end_date' =>$_POST['end_date'],
                                        'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
                                        // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                        // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                        // 'schedule_repeat' => $_POST['repeat_time'],
                                        // 'tag' => $_POST['tag'],
                                        // 'note' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id'),
                                        'subdomain_id' => $this->session->userdata('user_id'),
                                        'published' => 1                           

                                        );

                                         // echo "<pre>";print_r($shift_details); exit();
                                        $exist_count == 0;
                                        $this->db->insert('shift_scheduling',$shift_details);
                                        $shift_id =$this->db->insert_id();
                                    }
                                    // else {
                                        
                                    //     $exist_date = $_POST['schedule_date'];
                                    //     $exist_count += $exist_schedule_count;
                                    // }
                                }
                                
                            // echo "<pre>";print_r($shift_details); exit();
                                $k++;

                    
                            }
                         
                        } else if(isset($_POST["cyclic_shift"]) && !empty($_POST["cyclic_shift"])){
                            $workdays = isset($_POST['workdays'])?$_POST['workdays']:'';
                            if(!empty($workdays)){
                                $workday = count($_POST['workdays']);
                            }
                            if(isset($_POST['cyclic_shift'])){
                                $no_of_days_in_cycle = isset($_POST['no_of_days_in_cycle'])?$_POST['no_of_days_in_cycle']:0;
                            } else{
                                $no_of_days_in_cycle = 0;
                            }
                            $end_schedulde_date= date('Y-m-d',strtotime('+120 day'));
                             
                           
                            
                                for($i=1; $i<=120; $i++){
                                    
                                    // $workdays = 5;
                                    echo $i%$no_of_days_in_cycle;
                                    if($i%$no_of_days_in_cycle > $workday || $i%$no_of_days_in_cycle == 0){
                                        echo "Leave";
                                    }else {
                                        
                                        $day =$i-1;
                                        $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date'])))))->row_array();                                   
                                        if(empty($employee_shifts)){
                                             $shift_details = array(
                                            'dept_id' => $_POST['department'],
                                            'employee_id' => $value,
                                            'schedule_date' => date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date']))),
                                            'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                            'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                            'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                            'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                            'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                            'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                            'break_time' => $_POST['break_time'],
                                            'shift_id' => $_POST['shift_id'],
                                            //'color' => $_POST['color'],
                                            'accept_extras' =>1,
                                            'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                            'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
                                            'no_of_days_in_cycle' => $no_of_days_in_cycle,
                                            'workday' => isset($workday)?$workday:0,
                                            'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                            'week_days' => $week_days,                                    
                                            'end_date' =>date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date']))),
                                            'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
                                            // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                            // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                            // 'schedule_repeat' => $_POST['repeat_time'],
                                            // 'tag' => $_POST['tag'],
                                            // 'note' => $_POST['note'],
                                            'created_by' => $this->session->userdata('user_id'),
                                            'subdomain_id' => $this->session->userdata('user_id'),
                                            'published' => 1                           

                                            );

                                             // echo "<pre>";print_r($shift_details); exit();
                                            $exist_count == 0;
                                            $this->db->insert('shift_scheduling',$shift_details);
                                            $shift_id =$this->db->insert_id();
                                        }
                                    }
                                    

                                }
                                             
                                   
                                    // else {
                                        
                                    //     $exist_date = $_POST['schedule_date'];
                                    //     $exist_count += $exist_schedule_count;
                                    // }
                                
                           
                              
                        }
                        else{
                            $begin = new DateTime($_POST['schedule_date']);
                            $end = new DateTime($end_schedulde_date);

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);
                             // echo $employee_shifts; exit;
                             // echo "<pre>";
                            foreach ($period as $dt) {

                               
                                $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>$dt->format("Y-m-d")))->row_array();
                               
                               if(empty($employee_shifts)){
                                     $shift_details = array(
                                    'dept_id' => $_POST['department'],
                                    'employee_id' => $value,
                                    'schedule_date' => $dt->format("Y-m-d"),
                                    'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                    'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                    'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                    'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                    'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                    'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                    'break_time' => $_POST['break_time'],
                                    'shift_id' => $_POST['shift_id'],
                                    //'color' => $_POST['color'],
                                    'accept_extras' => 1,
                                    'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                    'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
                                    'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                    'week_days' => $week_days,
                                    // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                    // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                    // 'schedule_repeat' => $_POST['repeat_time'],
                                    // 'tag' => $_POST['tag'],
                                    // 'note' => $_POST['note'],
                                    'created_by' => $this->session->userdata('user_id'),
                                    'subdomain_id' => $this->session->userdata('user_id'),
                                    'published' => 1                           

                                    );


                                    $exist_count == 0;
                                    $this->db->insert('shift_scheduling',$shift_details);
                                    $shift_id =$this->db->insert_id();
                                }
                            // }else {
                                
                            //     $exist_date = $_POST['schedule_date'];
                            //     $exist_count += $exist_schedule_count;
                            // }
                            // echo "<pre>";print_r($shift_details); exit();
                            // $j++;

                            }
                        }
                       
                        // echo $this->db->last_query(); exit();
                       
                    }
                    $base_url = base_url();
                     $data = array(
                        'module' => 'Shift_scheduling',
                        'module_field_id' => $_POST['shift_id'],
                        'user' => $value,
                        'activity' => 'New Shift Scheduled',
                        'icon' => 'fa-plus',
                        'value1' => $cur.' '.$this->input->post('department')
                        );
                    App::Log($data);
                    $user_details = $this->db->get_where('users',array('id'=>$value))->row_array();
                        if(!empty($user_details)){
                            $recipient[] = $user_details['email'];

                        }
                        $exist_schedule_count++;
                }
                    $subject         = "New Shift Schedule";
                $message         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Shift Scheduled</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi,</p>
                                                <p>You have a New Shift Scheduled from  '.$_POST["schedule_date"].' </p>                                          
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                OR 
                                                <a style="text-decoration:none; margin-left:15px" href="'.$base_url.'shift_scheduling/"> 
                                                <button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
                                                </a>
                                                <br>
                                                </big><br><br>Regards<br>The '.config_item('company_name').' Team
                                            </div>
                                     </div>';       
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u;
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }
              
              // echo $exist_count; exit();  
            // if($exist_count == 1){
            //      $this->session->set_flashdata('tokbox_error', 'You have already scheduled this date '.$exist_date);
                
            // } elseif ($exist_count > 1) {
            //     $this->session->set_flashdata('tokbox_success', 'You have already scheduled '.$exist_count.' days');
            // }else{
                
                 $this->session->set_flashdata('tokbox_success', lang('shift_schedule_created_successfully'));
            // }
            
            redirect('shift_scheduling');
        }
        
    }else{       

            if($this->uri->segment(3) !=''){
                $employee_id = $this->uri->segment(3); 
                $data['shift_scheduling'] = 
                $this->db->select('D.deptname,D.deptid,A.fullname,U.id');
                $this->db->from('users U');
                $this->db->join('account_details A','A.user_id = U.id','LEFT');
                $this->db->join('departments D','D.deptid = U.department_id','LEFT');
                $this->db->where('U.id',$employee_id);
                $data['employee_details'] = $this->db->get()->row_array();
            }
            if($this->uri->segment(4) !=''){
                $data['schedule_date'] = date("d-m-Y", strtotime($this->uri->segment(4)));
            }
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
            $data['page'] = lang('shift_scheduling');
            $data['sub_page'] = lang('shift_scheduling');
            $data['datatables'] = TRUE;
            $data['datepicker'] = TRUE;            
            // $data['daterangepicker'] = TRUE;
            $data['form'] = TRUE;     
            $this->template
            ->set_layout('users')
            ->build('add_schedule',isset($data) ? $data : NULL);
        }
    
    }
     function view_schedule()
    {
    $this->load->module('layouts');
    $this->load->library('template');
    $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
    $data['page'] = lang('shift_scheduling');
    $data['sub_page'] = lang('shift_scheduling');
    $data['datatables'] = TRUE;
    $data['form'] = TRUE;
    $data['country_code'] = TRUE;
   
    $this->template
    ->set_layout('users')
    ->build('view_schedule',isset($data) ? $data : NULL);
    }

    function edit_schedule()
    { 
       // echo "<pre>"; print_r($_POST); exit();
        
         if($_POST){
            $employees = $_POST['employee'];
                   
            
                  if (count($employees) > 0) {
                    // echo "<pre>";print_r($_POST); exit();
                    foreach ($employees as $key => $value) {
                        $this->db->where('employee_id',$value);
                        $this->db->where('schedule_date >=',date('Y-m-d',strtotime($_POST['schedule_date'])));
                        if($_POST['indefinite'] != 1 && empty($_POST['cyclic_shift'])){
                        // echo 1;     exit;                        
                         $this->db->where('schedule_date <=',date('Y-m-d',strtotime($_POST['end_date'])));
                        }
                        $this->db->delete('shift_scheduling');   
                        // echo $this->db->last_query(); exit; 
                        $d = $_POST['week_days'];
                        $week_days = isset($_POST['week_days'])?$_POST['week_days']:'';
                        if(!empty($week_days)){
                            $week_days = implode(',',$week_days);
                        }
                        
                       $_POST['end_date'] = !empty($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
                          // $end_schedulde_date= date('Y-m-d',strtotime('+'.$_POST["repeat_week"].' week', strtotime($_POST['schedule_date'])));
                          if(!empty($_POST['end_date'])){
                                $end_schedulde_date= $_POST['end_date'];
                                $end_schedulde_date= date('Y-m-d',strtotime('+1 day', strtotime($_POST['end_date'])));
                          } else {
                            $end_schedulde_date= date('Y-m-d',strtotime('+120 day'));
                          }
                         
                           // echo $end_schedulde_date; exit();
                          
                        $_POST['end_date'] = !empty($_POST['end_date'])?date('Y-m-d',strtotime($_POST['end_date'])):'';
                        // $repeat_time = $_POST['repeat_time'] * 7;
                         // echo $start_date .' '.$maxDays; exit;
                        if(isset($_POST["recurring_shift"]) && !empty($_POST["recurring_shift"])){
                            $begin = new DateTime($_POST['schedule_date']);
                            $end = new DateTime($end_schedulde_date);

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);
                             // echo "<pre>";print_r($period); exit;
                            foreach ($period as $dt) {
                                if(in_array(lcfirst($dt->format("l")), $_POST['week_days'])){
                                    // echo $dt->format("l Y-m-d H:i:s\n");
                                    // echo "<pre>";print_r($_POST); exit;
                               
                                    $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>$dt->format("Y-m-d")))->row_array();
                                     // echo $employee_shifts; exit;
                                     // echo "<pre>";
                                     
                                    if(empty($employee_shifts)){
                                         $shift_details = array(
                                        'dept_id' => $_POST['department'],
                                        'employee_id' => $value,
                                        'schedule_date' => $dt->format("Y-m-d"),
                                        'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                        'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                        'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                        'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                        'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                        'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                        'break_time' => $_POST['break_time'],
                                        'shift_id' => $_POST['shift_id'],
                                       // 'color' => $_POST['color'],
                                        'accept_extras' => 1,
                                        'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                        'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                        'week_days' => $week_days,                                    
                                        'end_date' =>$_POST['end_date'],
                                        'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
                                        // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                        // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                        // 'schedule_repeat' => $_POST['repeat_time'],
                                        // 'tag' => $_POST['tag'],
                                        // 'note' => $_POST['note'],
                                        'created_by' => $this->session->userdata('user_id'),
                                        'subdomain_id' => $this->session->userdata('user_id'),
                                        'published' => 1                           

                                        );

                                         // echo "<pre>";print_r($shift_details); exit();
                                        $exist_count == 0;
                                        $this->db->insert('shift_scheduling',$shift_details);
                                        $shift_id =$this->db->insert_id();
                                    }
                                    // else {
                                        
                                    //     $exist_date = $_POST['schedule_date'];
                                    //     $exist_count += $exist_schedule_count;
                                    // }
                                }
                                
                            // echo "<pre>";print_r($shift_details); exit();
                                $k++;

                    
                            }
                         
                        } else if(isset($_POST["cyclic_shift"]) && !empty($_POST["cyclic_shift"])){
                             $workdays = isset($_POST['workdays'])?$_POST['workdays']:'';
                            if(!empty($workdays)){
                                $workday = count($_POST['workdays']);
                            }
                            if(isset($_POST['cyclic_shift'])){
                                $no_of_days_in_cycle = isset($_POST['no_of_days_in_cycle'])?$_POST['no_of_days_in_cycle']:0;
                            } else{
                                $no_of_days_in_cycle = 0;
                            }
                            $end_schedulde_date= date('Y-m-d',strtotime('+120 day'));
                             
                           
                            
                                for($i=1; $i<=120; $i++){
                                    
                                    $workdays = 5;
                                    echo $i%$no_of_days_in_cycle;
                                    if($i%$no_of_days_in_cycle > $workday || $i%$no_of_days_in_cycle == 0){
                                        echo "Leave";
                                    }else {
                                        
                                        $day =$i-1;
                                        $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date'])))))->row_array();                                   
                                        if(empty($employee_shifts)){
                                             $shift_details = array(
                                            'dept_id' => $_POST['department'],
                                            'employee_id' => $value,
                                            'schedule_date' => date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date']))),
                                            'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                            'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                            'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                            'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                            'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                            'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                            'break_time' => $_POST['break_time'],
                                            'shift_id' => $_POST['shift_id'],
                                           // 'color' => $_POST['color'],
                                            'accept_extras' => 1,
                                            'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                            'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
                                            'no_of_days_in_cycle' => $no_of_days_in_cycle,
                                            'workday' => isset($workday)?$workday:0,
                                            'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                            'week_days' => $week_days,                                    
                                            'end_date' =>date('Y-m-d', strtotime('+'.$day.' days', strtotime($_POST['schedule_date']))),
                                            'indefinite' => isset($_POST['indefinite'])?$_POST['indefinite']:0,
                                            // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                            // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                            // 'schedule_repeat' => $_POST['repeat_time'],
                                            // 'tag' => $_POST['tag'],
                                            // 'note' => $_POST['note'],
                                            'created_by' => $this->session->userdata('user_id'),
                                            'subdomain_id' => $this->session->userdata('user_id'),
                                            'published' => 1                           

                                            );

                                             // echo "<pre>";print_r($shift_details); exit();
                                            $exist_count == 0;
                                            $this->db->insert('shift_scheduling',$shift_details);
                                            $shift_id =$this->db->insert_id();
                                        }
                                    }
                                    

                                }
                              
                        }
                        else{

                            $begin = new DateTime($_POST['schedule_date']);
                            $end = new DateTime($end_schedulde_date);

                            $interval = DateInterval::createFromDateString('1 day');
                            $period = new DatePeriod($begin, $interval, $end);

                            // echo pre($period);
                             // echo $employee_shifts; exit;
                             // echo "<pre>";
                            foreach ($period as $dt) {

                               
                                $employee_shifts = $this->db->get_where('shift_scheduling',array('employee_id'=>$value,'schedule_date'=>$dt->format("Y-m-d")))->row_array();
                                
                               if(empty($employee_shifts)){
                                     $shift_details = array(
                                    'dept_id' => $_POST['department'],
                                    'employee_id' => $value,
                                    'schedule_date' => $dt->format("Y-m-d"),
                                    'min_start_time' => date("H:i", strtotime($_POST['min_start_time'])),
                                    'start_time' => date("H:i", strtotime($_POST['start_time'])),
                                    'max_start_time' => date("H:i", strtotime($_POST['max_start_time'])),
                                    'min_end_time' => date("H:i", strtotime($_POST['min_end_time'])),
                                    'end_time' => date("H:i", strtotime($_POST['end_time'])),
                                    'max_end_time' => date("H:i", strtotime($_POST['max_end_time'])),
                                    'break_time' => $_POST['break_time'],
                                    'shift_id' => $_POST['shift_id'],
                                   // 'color' => $_POST['color'],
                                    'accept_extras' => 1,
                                    'recurring_shift' => isset($_POST['recurring_shift'])?$_POST['recurring_shift']:0,
                                    'cyclic_shift' => isset($_POST['cyclic_shift'])?$_POST['cyclic_shift']:0,
                                    'repeat_week' => isset($_POST['repeat_week'])?$_POST['repeat_week']:0,
                                    // 'week_days' => $week_days,
                                    // 'break_start' => date("H:i", strtotime($_POST['break_start'])),
                                    // 'break_end' => date("H:i", strtotime($_POST['break_end'])),
                                    // 'schedule_repeat' => $_POST['repeat_time'],
                                    // 'tag' => $_POST['tag'],
                                    // 'note' => $_POST['note'],
                                    'created_by' => $this->session->userdata('user_id'),
                                    'subdomain_id' => $this->session->userdata('user_id'),
                                    'published' => 1                           

                                    );

                                     // echo "<pre>";print_r($shift_details); exit();
                                    $exist_count == 0;
                                    $this->db->insert('shift_scheduling',$shift_details);
                                     // echo $this->db->last_query(); exit();
                                    $shift_id =$this->db->insert_id();
                                }
                            // }else {
                                
                            //     $exist_date = $_POST['schedule_date'];
                            //     $exist_count += $exist_schedule_count;
                            // }
                            // 
                            // $j++;

                            }
                        }
                       
                        // echo $this->db->last_query(); exit();
                       
                    }
                    $base_url = base_url();
                     $data = array(
                        'module' => 'Shift_scheduling',
                        'module_field_id' => $_POST['shift_id'],
                        'user' => $value,
                        'activity' => 'New Shift Scheduled',
                        'icon' => 'fa-plus',
                        'value1' => $cur.' '.$this->input->post('department')
                        );
                    App::Log($data);
                    $user_details = $this->db->get_where('users',array('id'=>$value))->row_array();
                        if(!empty($user_details)){
                            $recipient[] = $user_details['email'];

                        
                    }
                    $subject         = "Update Shift Schedule";
                $message         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Update Shift Scheduledt</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi,</p>
                                                <p>You have a Update Shift Scheduled from  '.$_POST["schedule_date"].' </p>                                          
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                OR 
                                                <a style="text-decoration:none; margin-left:15px" href="'.$base_url.'shift_scheduling/"> 
                                                <button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
                                                </a>
                                                <br>
                                                </big><br><br>Regards<br>The '.config_item('company_name').' Team
                                            </div>
                                     </div>';       
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u;
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }


                }
                
            $this->session->set_flashdata('tokbox_success', lang('shift_schedule_edited_successfully'));
            redirect('shift_scheduling');
        }else{
            
            if($this->uri->segment(3) !=''){
                $shift_id = $this->uri->segment(3); 
                $data['shift_scheduling'] = 

                $this->db->select('S.*,D.deptname,D.deptid,A.fullname,U.id as user_id');
                $this->db->from('shift_scheduling S');
                $this->db->join('users U','U.id = S.employee_id','LEFT');
                $this->db->join('account_details A','A.user_id = U.id','LEFT');
                $this->db->join('departments D','D.deptid = U.department_id','LEFT');
                $this->db->where('S.id',$shift_id);
                $data['employee_details'] = $this->db->get()->row_array();
            }
            if($this->uri->segment(4) !=''){
                $data['schedule_date'] = date("d-m-Y", strtotime($this->uri->segment(4)));
            }
            // $data['daterangepicker'] = TRUE;
        // echo "<pre>"; print_r($data['employee_details']); exit();
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('shift_scheduling').' - '.config_item('company_name'));
        $data['page'] = lang('shift_scheduling');
        $data['sub_page'] = lang('shift_scheduling');
        $data['datatables'] = TRUE;
        $data['datepicker'] = TRUE;       
        $data['form'] = TRUE;
        $data['country_code'] = TRUE;       
        $this->template
        ->set_layout('users')
        ->build('edit_schedule',isset($data) ? $data : NULL);
        }

        
    }
  

    function check_shift_name()
    {
        $shift_name = $this->input->post('shift_name');
        $check_username = $this->shift_scheduling->check_shift_name($shift_name);
        if($check_username > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    function check_edit_shift_name()
    {
        $shift_name = $this->input->post('shift_name');
        $id = $this->input->post('id');
        $shift_name = $this->shift_scheduling->check_edit_shift_name($shift_name,$id);
        if($shift_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    
    




    /* Dreamguys 25/02/2019 End */

}

/* End of file employees.php */
