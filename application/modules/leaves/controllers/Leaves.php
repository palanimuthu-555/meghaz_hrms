<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Leaves extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Leaves_model'));
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
		
		
		if(!App::is_access('menu_leaves'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

       // App::module_access('menu_leaves');
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    function index()
    {
		if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
				$this->template->title(lang('leaves').' - '.config_item('company_name'));	
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
				$data['datatables'] = TRUE;
                $data['page']       = lang('leaves');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('leaves',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     } 
	 function admin_login() //this is ADMIN LOGIN WITHOUT PASSWORD FUNCTION
	 { 
	     $user = $this->db->query("SELECT * FROM `dgt_users` where id = 1")->result_array();  
		 $this->session->set_userdata(array(
												'user_id'   => $user[0]['id'],
												'username'  => $user[0]['username'],
												'role_id'   => $user[0]['role_id'],
												'status'	=> ($user[0]['activated'] == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
										    ));
 		 redirect('/leaves');								
 	 } 
	 
	 function sts_update($leave_tbl_id = 0 ,$sts_type = 0) 
	 {  
	    $log_in_sts = false;
 		if(!$this->tank_auth->is_logged_in()) {	  
			$user = $this->db->query("SELECT * FROM `dgt_users` where id = 1")->result_array();  
			if(!empty($user)){
 				$this->session->set_userdata(array(
														'user_id'   => $user[0]['id'],
														'username'  => $user[0]['username'],
														'role_id'   => $user[0]['role_id'],
														'status'	=> ($user[0]['activated'] == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,
												  ));
				$log_in_sts = true;	 
			}else{ 
			    $log_in_sts = false;
			}
		}else{
			 $log_in_sts = true;
		}   
		if($log_in_sts){ 
			$chk = $this->db->query("select * from dgt_user_leaves where id = ".$leave_tbl_id."")->result_array();
			if(isset($chk[0]['status']) && $chk[0]['status'] == 0){
				$det['status']  = $sts_type; 
				$this->db->update('dgt_user_leaves',$det,array('id'=>$leave_tbl_id));  
				$head_str = "  ";
				if($sts_type == 1){
					$head_str = " Approved ";
				}else if($sts_type == 2){
					$head_str = " Rejected ";
				}  
				$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$chk[0]['user_id']." ")->result_array();
				$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$chk[0]['user_id']." ")->result_array();
				if(!empty($acc_det) && !empty($user_det)){
					$recipient       = array();
					if($user_det[0]['email'] != '') { $recipient[] = $user_det[0]['email']; }
					$subject         = " Leave Request ".$head_str;
					$message         = '<div style="height: 7px; background-color: #535353;"></div>
											<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
												<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request '.$head_str.'</div>
												<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
													<p> Hi '.$acc_det[0]['fullname'].',</p>
													<p> Your Leave Request for '.date('d-m-Y',strtotime($chk[0]['leave_from'])).' to '.date('d-m-Y',strtotime($chk[0]['leave_to'])).' has been '.$head_str.' by Admin </p> 
													<br>  
													<a style="text-decoration:none;" href="http://dreamguystech.com/hrm/"> 
														<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
													</a>
													<br>
													</big><br><br>Regards<br>The '.config_item('company_name').' Team
												</div>
										 </div>';  
					if(!empty($recipient) && count($recipient) > 0){		 
						$params      = array(
												'recipient' => $recipient,
												'subject'   => $subject,
												'message'   => $message
											);   
						$succ = Modules::run('fomailer/send_email',$params); 	
					}
				}   
				
			}else{
			    //here alert message	
			}
			redirect('/leaves');
		}else{
			redirect('');
		}
	} 
	function add()
	{
 		if($this->tank_auth->is_logged_in()) { 
		 if ($this->input->post()) {

	 	 	$leave_approver= $this->db->get('leave_approver_settings')->result_array();

	 	 	foreach ($leave_approver as $key => $value) {
	 	 		$approvers_id[] = $value['approvers'];
	 	 	}
	 	 	if(!empty($approvers_id)){
	 	 		if (in_array(9, $approvers_id))
			  	{
				  	$approvers[] = $this->input->post('teamlead_id');
				  	$teamlead_id_details = $this->db->get_where('users',array('id'=>$this->input->post('teamlead_id')))->row_array();
				  	$recipient[] = $teamlead_id_details['email'];
				  	if (($key = array_search(9, $approvers_id)) !== false) {
						    unset($approvers_id[$key]);
						}
						if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->row_array();
								if(!empty($user_details)){
									$approvers[] = $user_details['id'];	
									$recipient[] = $user_details['email'];

								}
								
							}
						}
						
				  }
				else
			  	{
				  	if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->row_array();
								if(!empty($user_details)){
									$approvers[] = $user_details['id'];	
									$recipient[] = $user_details['email'];
								}
								
							}
						}
			  	}
	 	 	} else {
	 	 		$approvers[] = $this->input->post('teamlead_id');
	 	 		$teamlead_id_details = $this->db->get_where('users',array('id'=>$this->input->post('teamlead_id')))->row_array();
			  	$recipient[] = $teamlead_id_details['email'];
	 	 	}
	 	 	
	  	// 	echo "<pre>"; print_r($approvers_id); 
	  	// 	echo "<pre>"; print_r($approvers); 
	  	// 	echo "<pre>"; print_r($recipient); 
			 // echo "<pre>"; print_r($_POST); exit;
	 	 	$approvers_array = $approvers;
	 	 	$approvers = implode(',', $approvers);
	 	 	$leave_approvers = $this->db->get('designation')->result_array();
			$user_id              = $this->tank_auth->get_user_id(); //echo $user_id;exit;
  			$det['user_id']       = $user_id;
			$det['leave_type']    = $this->input->post('req_leave_type'); 
			$det['teamlead_id']    = $approvers; 
 			$det['leave_from']    = date('Y-m-d',strtotime($this->input->post('req_leave_date_from')));
			$det['leave_to']      = date('Y-m-d',strtotime($this->input->post('req_leave_date_to')));
  			$qry                  =  "SELECT * FROM `dgt_user_leaves` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
 			$contdtn    		  = true;					  
 			$leave_list 		   = $this->db->query($qry)->result_array();
  			$d1 		 		   = strtotime($this->input->post('req_leave_date_from'));
 			$d2 		 		   = strtotime($this->input->post('req_leave_date_to'));
 			$array1     		   = array();
			for($i = $d1; $i <= $d2; $i += 86400 ){  $array1[] = $i; }  
  			if(!empty($leave_list)){ 
				foreach($leave_list as $key => $val)
				{ 
					$d11  = strtotime($val['leave_from']);
 			        $d22  = strtotime($val['leave_to']);
					for($i = $d11; $i <= $d22; $i += 86400 ){
						if(in_array($i,$array1)){
							$contdtn = false;	
							break;
						} 
					}  
					if(!$contdtn) { break; }
  				}
 			}  
 			if($contdtn){
				$det['leave_days']    = $this->input->post('req_leave_count');  
				if($det['leave_days'] <= 1){
				   $det['leave_day_type'] = $this->input->post('req_leave_day_type'); 
				}
				$det['leave_reason']  = $this->input->post('req_leave_reason');
				$this->db->insert('dgt_user_leaves',$det);   
				$leave_tbl_id  = $this->db->insert_id();
				// echo $this->db->last_query();
				// exit();

				if (count($approvers_array) > 0) {
					$i = 1;
                    foreach ($approvers_array as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'leave_id' => $leave_tbl_id,
                            'status' => 0,
                            'created_by'=>$this->session->userdata('user_id'),
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;

                        if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                        		if($i ==1){
	                        		$approvers_details['view_status'] = 1;
		                        } else{
		                        	$approvers_details['view_status'] = 0;
		                        }   
                        	}else{
                        		$approvers_details['view_status'] = 1;
                        	}
                       $this->db->insert('dgt_leave_approvers',$approvers_details);   
                       $login_user_name = $this->tank_auth->get_username();  
                       if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                    		if($i ==1){   
		                        $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($this->session->userdata('user_id')),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('title', true),
		                        );
		                		App::Log($args);     
		                	} 
		                }else{
		                	  $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($this->session->userdata('user_id')),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('title', true),
		                        );
		                		App::Log($args);
		                }   
		                $i++;       

                    }                	

                    
                }


 				$leave_day_str = $det['leave_days'].' days';
				if($det['leave_days'] < 1){
				 	$leave_day_str = 'Half day';
 				}
				//This is admin alert Email   
				$base_url = base_url();
				
				$login_user_name = $this->tank_auth->get_username();  
				
				// $this->db->select('value');
				// $records = $this->db->get_where('dgt_config',array('config_key'=>'company_email'))->row_array();

				$log_detail = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				// if($log_detail['teamlead_id'] != 0)
				// {
					$this->db->select('email');
					$send_mail = $this->db->get_where('dgt_users',array('id'=>$log_detail['teamlead_id']))->row_array();
					$send_mail = !empty($send_mail)?$send_mail:'';
				// }else{
				// 	$send_mail = '';
				// }
				// if($send_mail != '')
				// {
				// 	$recipient       = $send_mail['email'];
				// }
				// else{
				// 	$recipient       = array($records['value']);
				// }
					// <a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/4"> 
					// 							<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
					// 							</a>
					// 							<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/5"> 
					// 							<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
					// 							</a>  
				$from_leave = date('d M Y',strtotime($det['leave_from']));
				$to_leave = date('d M Y',strtotime($det['leave_to']));
				$lead_emails = $this->db->get('dgt_lead_reporter')->result_array(); 
				$emails = array(); 
				foreach($lead_emails as $lead_email){
					$emails[] = $lead_email['reporter_email'];
				}
				 
				$subject         = " Employee Leave Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' requested to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' ) </p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 			 
					if(!empty($recipient))	{		
						foreach ($recipient as $key => $u) 
		                {
		                   // echo ($u);exit;
		                    $params['recipient'] = $u;
		                    $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
		                    $params['message'] = $message;
		                    $params['attached_file'] = '';
		                    //$succ = Modules::run('fomailer/send_email',$params); 	
		                    Modules::run('fomailer/send_email',$params);
		                }
		            }				

					$this->session->set_flashdata('tokbox_success', 'Leave Requested Successfully');

 			}else{
				$this->session->set_flashdata('tokbox_error', 'Already Requested');
			}
     		redirect('leaves');
		} 
		}else{
		   redirect('');	
		}
 	} 
	function approve()
	{

		$approver = $this->session->userdata('user_id');
		if ($this->input->post()) {

			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();			
			$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

			

			if($this->input->post('approve') == 'teamlead')
			{
				$det['reason']      = $this->input->post('reason');  // Teamlead Approval
				// $det['status']      = 4; 
				$det['status']      = 1; 
			}
			if($this->input->post('approve') == 'management')
			{
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 1; 
			}
			$approvers_status['status'] = 1;
			$view_status['view_status'] = 1;
			if (User::is_admin()) {
				$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$this->input->post('req_leave_tbl_id'))); 
				$this->db->update('dgt_leave_approvers',$view_status,array('leave_id'=>$_POST['req_leave_tbl_id'])); 
			}else{
				$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$this->input->post('req_leave_tbl_id'),'approvers'=>$approver)); 

			}
			
				$leave_approvers = $this->db->get('leave_approver_settings')->result_array();
			if($leave_approvers[0]['default_leave_approval'] == "seq-approver"){
			// next approvers view
			// if($_POST['status'] == 1){		
			if (!User::is_admin()) {		
		        $get_approver_record = $this->db->get_where('dgt_leave_approvers',array('leave_id'=>$_POST['req_leave_tbl_id'],'approvers'=>$approver))->row_array();	        
				
				$view_next = $this->db->query('select * from dgt_leave_approvers where id = (select min(id) from dgt_leave_approvers where id > '.$get_approver_record['id'].')')->row_array();
				
				if(!empty($view_next)){
					$this->db->update('dgt_leave_approvers',$view_status,array('leave_id'=>$_POST['req_leave_tbl_id'],'id'=>$view_next['id'])); 

					$data = array(
								'module' => 'leaves',
								'module_field_id' => $_POST['req_leave_tbl_id'],
								'user' => $view_next['approvers'],
								'activity' => 'Leave Requested by '.$acc_det[0]['fullname'],
								'icon' => 'fa-plus',
								// 'value1' => $cur.' '.$this->input->post('amount'),
								'value2' => $leave_det[0]['user_id']
								);
					// print_r($data);
				App::Log($data);	

				}	
			}


			// }
		}

			$leave_approvers_status   = $this->db->get_where('leave_approvers',array('status !='=>1,'leave_id'=>$this->input->post('req_leave_tbl_id')))->num_rows();
		 	if($leave_approvers_status == 0){
	         	$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
	        } else {
	             $this->db->set(array('reason'=>$det['reason']))->where('id',$this->input->post('req_leave_tbl_id'))->update('dgt_user_leaves');
	        }

			// $this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			

	         	$data = array(
						'module' => 'leaves',
						'module_field_id' => $this->input->post('req_leave_tbl_id'),
						'user' => $leave_det[0]['user_id'],
						'activity' => 'Leave Approved by '.$approver_det['fullname'],
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('reason'),
						'value2' => $leave_det[0]['user_id']
						);
					App::Log($data);
					$base_url = base_url();
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Leave Request Approved ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by '.$approver_det['fullname'].' </p> 
												<br>  
												<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			} 
			$this->session->set_flashdata('tokbox_success', 'Leave Approved Successfully');
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(4);
			$data['teamlead'] = $this->uri->segment(3);
			$this->load->view('modal/approve',$data);
		}
 	}
	function reject()
	{
		$approver = $this->session->userdata('user_id');
		if ($this->input->post()) {
			// if($this->input->post('approve') == 'teamlead')
			// {
			// 	$det['reason']      = $this->input->post('reason'); 
			// 	$det['status']      = 5; 
			// }
			// if($this->input->post('approve') == 'management')
			// {
				$det['reason']      = $this->input->post('reason'); 
				$det['status']      = 2; 
			// }
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			$approvers_status['status'] = 2;
			$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$this->input->post('req_leave_tbl_id'),'approvers'=>$approver)); 
  			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$this->input->post('req_leave_tbl_id')." ")->result_array();
			$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
			$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();						
			$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

			$data = array(
						'module' => 'leaves',
						'module_field_id' => $this->input->post('req_leave_tbl_id'),
						'user' => $leave_det[0]['user_id'],
						'activity' => 'Leave Rejected by '.$approver_det['fullname'],
						'icon' => 'fa-plus',
						'value1' => $cur.' '.$this->input->post('reason'),
						'value2' => $leave_det[0]['user_id']
						);
					App::Log($data);
					$base_url = base_url();
 			if(!empty($acc_det) && !empty($user_det)){
				$recipient       = array();
				if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
				$subject         = " Leave Request Rejected ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Rejected</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi '.$acc_det[0]['fullname'].',</p>
												<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by '.$approver_det['fullname'].' </p> 
												<br>  
												<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
													<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';  
				if(!empty($recipient) && count($recipient) > 0){		 
					$params      = array(
											'recipient' => $recipient,
											'subject'   => $subject,
											'message'   => $message
										);   
					$succ = Modules::run('fomailer/send_email',$params); 	
				}
 			}
			$this->session->set_flashdata('tokbox_success', 'Leave Rejected Successfully');
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(4);
			$data['teamlead'] = $this->uri->segment(3);
			$this->load->view('modal/reject',$data);
		} 
	}
	function cancel()
	{
		if ($this->input->post()) {
			$det['reason']      = $this->input->post('reason'); 
			$det['status']      = 3; 
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(3);
			$this->load->view('modal/cancel',$data);
		}
	
	}
	function delete()
	{
		if ($this->input->post()) {
			$det['status']      = 7; 
			$det['reason']      = $this->input->post('reason'); 
			$this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
			redirect('leaves');
 		}else{
			$data['req_leave_tbl_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete',$data);
		}
 	}
	function search_leaves()
	{ 
 		$l_type =  $_POST['l_type'];
		$l_sts  =  $_POST['l_sts']; 
		$uname  =  $_POST['uname']; 
		$dfrom  =  $_POST['dfrom']; 
		$dto    =  $_POST['dto']; 
		
		if($dfrom != '') $dfrom = date('Y-m-d',strtotime($_POST['dfrom']));
		if($dto != '') $dto = date('Y-m-d',strtotime($_POST['dto']));
 		 
		$qry    =  "SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.avatar
					FROM `dgt_user_leaves` ul
					left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
					left join dgt_account_details ad on ad.user_id = ul.user_id 
					where ";
		if($l_type != ''){ $qry   .=  " ul.leave_type = '".$l_type."' and "; } 			
		if($l_sts != ''){ $qry    .=  " ul.status = '".$l_sts."' and "; } 
 		if($uname != ''){ $qry    .=  " ul.user_id = (SELECT user_id FROM `dgt_account_details` WHERE fullname like '%".$uname."%') and "; } 
 		if($dfrom != ''){ $qry    .=  " ul.leave_from >= '".$dfrom."' and "; } 
		if($dto != ''){ $qry      .=  " ul.leave_to <= '".$dto."' and "; } 
   		$qry    .=  " ul.status != 4 and DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." order by ul.id DESC";
 		//echo $qry; exit;
 		$html       = '';	
 		$leave_list = $this->db->query($qry)->result_array();
  	    foreach($leave_list as $key => $levs){   
				 $html    .= '<tr>
								<td>'.($key+1).'</td>
								<td><a  href="'.base_url().'employees/profile_view/'.$levs['user_id'].'" class="avatar"><img class="avatar" src="'.base_url().'assets/avatar/'.$levs['avatar'].'" alt=""></a><a class="text-info" href="'.base_url().'leaves/show_leave/'.$levs['user_id'].'">'.$levs['fullname'].'</a></td>
								<td>'.$levs['l_type'].'</td>
								<td>'.date('d-m-Y',strtotime($levs['leave_from'])).'</td>
								<td>'.date('d-m-Y',strtotime($levs['leave_to'])).'</td>
								<td>'.$levs['leave_reason'].'</td>
								<td>'; 
								    $html    .=  $levs['leave_days'];
									if($levs['leave_day_type'] == 1){ $html    .= ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){ $html    .= ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){ $html    .= ' ( Second Half )'; } 
				   $html    .= '</td>
								<td>';
								
								if($levs['status'] == 4){
									$html    .= '<span class="badge badge-info"> TL - Approved</span><br>';
									$html    .= '<span class="badge badge-danger"> Management - Pending</span>';
								}else if($levs['status'] == 7){
											$html    .= '<span class="badge badge-danger"> Deleted </span>';
										}
								if($levs['status'] == 0){
									$html    .= ' <span class="badge text-white" style="background:#D2691E"> Pending </span>';
								}else if($levs['status'] == 1){
									$html    .= '<span class="badge badge-success"> Approved </span> ';
								}else if($levs['status'] == 2){
									$html    .= '<span class="badge badge-danger"> Rejected</span>';
								}else if($levs['status'] == 3){
									$html    .= '<span class="badge badge-danger"> Cancelled</span>';
								}

				   $html    .= '</td>
								<td>'.$levs['reason'].'</td>
								<td>';
				   if($levs['status'] == 0){
						  $html    .= '<a  class="btn btn-success btn-sm"  
									 data-toggle="ajaxModal" href="'.base_url().'leaves/approve/management/'.$levs['id'].'" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a>';
				   } 
				   if($levs['status'] == 0 || $levs['status'] == 1){   
						  $html    .= '&nbsp;<a class="btn btn-danger btn-sm"  
									 data-toggle="ajaxModal" href="'.base_url().'leaves/reject/management/'.$levs['id'].'" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a>';
				   }  
				   $html    .= '
								</td>
							</tr>';
        } 
		if($html == ''){
 			$html = '<tr>
			           <td colspan="10" class="text-center"> No Data Available </td>
 			         </tr>';
 		}  
  		echo $html;  exit;
  	}

  	public function leave_check()
  	{
  		$user_id = $_POST['login_id'];
  		// echo $user_id; exit;
  		$total_leaves = array();
  		$normal_leaves = array();
  		$medical_leaves = array();
  		$sick_leaves = array();
  		$leaves = $this->Leaves_model->check_leavesById($user_id);
  		$nor_leaves = $this->Leaves_model->check_leavesBycat($user_id,'1');
  		$med_leaves = $this->Leaves_model->check_leavesBycat($user_id,'2');
  		$sk_leaves = $this->Leaves_model->check_leavesBycat($user_id,'3');
  		for($i=0;$i<count($leaves);$i++)
  		{
  			$total_leaves[] = $leaves[$i]['leave_days'];
  		}
  		foreach($nor_leaves as $n_leave)
  		{
  			$normal_leaves[] = $n_leave['leave_days'];
  		}
  		foreach($med_leaves as $md_leave)
  		{
  			$medical_leaves[] = $md_leave['leave_days'];
  		}
  		foreach($sk_leaves as $sk_leave)
  		{
  			$sick_leaves[] = $sk_leave['leave_days'];
  		}

  		$t_leaves = array_sum($total_leaves);
  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
  		if($lop > 0 )
  		{
  			$lop_days = $lop;
  		}else{
  			$lop_days = 0;
  		}

  		$all_leaves = array(
  			'total_leaves' => $t_leaves,
  			'normal_leaves' => array_sum($normal_leaves),
  			'medical_leaves' => array_sum($medical_leaves),
  			'sick_leaves' => array_sum($sick_leaves),
  			'loss_pay' => $lop_days
  		);
  		echo json_encode($all_leaves); exit; 

  	}


  	public function show_leave()
  	{
  		 $user_id = $this->uri->segment(3);
              
  		$this->load->module('layouts');
                $this->load->library('template');                
				$this->template->title(lang('leaves').' - '.config_item('company_name'));
 				$data['datepicker'] = TRUE;
				$data['form']       = TRUE; 
				$data['datatables'] = TRUE;
                $data['page']       = 'leaves';
                $data['user_id']    = $user_id;
               
                $this->template
					 ->set_layout('users')
					 ->build('show_leaves',isset($data) ? $data : NULL);
  	}





}
