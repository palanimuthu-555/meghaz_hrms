<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Resignation extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array('App','Users'));
        $this->load->model('resignationmodel','resignations');
        if(!App::is_access('menu_resignation'))
		{
        $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('resignation')); 
 				$data['datepicker'] = TRUE;                
                $data['datatables'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('resignation');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('resignation',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


      public function resignation_list()
    {
        $list = $this->resignations->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $style_w="display:none";
		$style_d="display:none";
		if(App::is_permit('menu_resignation','write'))
		{
			$style_w="";	
		}
		if(App::is_permit('menu_resignation','delete'))
		{
			$style_d="";
		}
		$a=1;
		
         foreach ($list as $resignation) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $resignation->fullname;
            $row[] = $resignation->deptname;
            $row[] = $resignation->reason;
            $row[] = date('d M Y',strtotime($resignation->noticedate));
            $row[] = date('d M Y',strtotime($resignation->resignationdate));
			
			if(App::is_permit('menu_resignation','write')==true || App::is_permit('menu_resignation','delete')==true)
			{
  				$row[]='<div class="dropdown dropdown-action float-right">
					<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu float-right">
					<a class="dropdown-item" href="#" onclick="edit_resignation('.$resignation->id.')" style="'.$style_w.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>
					<a class="dropdown-item" href="#" onclick="delete_resignations('.$resignation->id.')" style="'.$style_d.'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
					</div>
					</div>';
			}

           

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->resignations->count_all(),
            "recordsFiltered" => $this->resignations->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function resignation_edit($id)
    {
        $data = $this->resignations->get_by_id($id);

        echo json_encode($data);
        exit;
    }

   




    public function add_resignation()
    {
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
        $reason=$this->input->post('reason');
        $login_user_details = $this->db->get_where('users',array('id'=>$employee))->row_array();
        if($this->session->userdata('role_id') == 3){
            $resignation_notice = $this->db->get('resignation_notice')->row_array();
            if(!empty($resignation_notice)){
                if(!empty($resignation_notice['notice_days'])){
                 $noticedate=   date('Y-m-d',strtotime('+'.$resignation_notice['notice_days'].' days',strtotime($this->input->post('resignationdate'))));
                }
                if(!empty($resignation_notice['email_notification'])){
                    $user_id = explode(',', $resignation_notice['email_notification']);
                    foreach ($user_id as $key => $id) {
                         $user_details = $this->db->get_where('users',array('id'=>$id))->row_array();
                        if(!empty($user_details)){
                            $data = array(
                                'module' => 'resignation',
                                'module_field_id' => $user_details['id'],
                                'user' => $user_details['id'],
                                'activity' => 'Resignation requested by '.User::displayName($employee),
                                'icon' => 'fa-plus',
                            );
                            App::Log($data);
                            $recipient[] =$user_details['email']; 
                        }
                        # code...
                    }
                }
            }else{
               $noticedate =  $resignationdate;              
               $repoting_detils = $this->db->get_where('users',array('id'=>$login_user_details['teamlead_id']))->row_array();
               $recipient[] = $repoting_detils['email'];
            }
        }else{
            $data = array(
                        'module' => 'resignation',
                        'module_field_id' => $employee,
                        'user' => $employee,
                        'activity' => 'Resignation craeted by '.User::displayName($this->session->userdata("user_id")),
                        'icon' => 'fa-plus',
                    );
                    App::Log($data);
        }
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {   
                if($this->session->userdata('role_id') == 3){
                $subject         = "Resignation Letter";
                $message         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Resignation</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi,</p>
                                                <p><b>Name : '.User::displayName($employee).'</b></p>  
                                                <p><b>Resignation Date </b> : '.$resignationdate.'</p>
                                                <p><b>Notice Date </b> : '.$noticedate.'</b></p>     
                                                <p><b>Reason </b> : '.$reason.'</p>                                      
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                
                                                <br>
                                                </big><br><br>Regards<br>The '.User::displayName($employee).' 
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
                }else{
                    $subject_admin         = "Resignation Letter";
                    $message_admin         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Resignation</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi '.User::displayName($employee).',</p>
                                                <p><b>Reason </b> : '.$reason.'</p>  
                                                <p><b>Resignation Date </b> : '.$resignationdate.'</p>
                                                <p><b>Notice Date </b> : '.$noticedate.'</b></p>     
                                                                                    
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                
                                                <br>
                                                </big><br><br>Regards<br>The '.User::displayName($this->session->userdata("user_id")).' 
                                            </div>
                                     </div>';       
                    
                        
                        $params['recipient'] = $login_user_details['email'];
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject_admin;
                        $params['message'] = $message_admin;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    
                }

                $datas['result']='yes';
                $datas['status']='Resignation added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_resignation()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $noticedate=date('Y-m-d',strtotime($this->input->post('noticedate')));
        $resignationdate=date('Y-m-d',strtotime($this->input->post('resignationdate')));
         $reason=$this->input->post('reason');

        
            $data = array(
                'employee'=>$employee,
                'noticedate'=>$noticedate,
                'resignationdate'=>$resignationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->where('id',$id);
            $this->db->update('resignation',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Resignation update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Resignation update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function resignation_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->resignations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 
	 




}
