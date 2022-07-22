<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Termination extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array('App','Users'));
        $this->load->model('terminationmodel','terminations');
        if(!App::is_access('menu_termination'))
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
                $this->template->title(lang('termination')); 
 				$data['datepicker'] = TRUE;
                $data['datatables'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('termination');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('termination',isset($data) ? $data : NULL);
		}else{
		   redirect('');	
		}
     }


     public function add_termination_type()
    {
        $termination_type=$this->input->post('termination_type');
               
            $data = array(
                'termination_type'=>$termination_type,
                 );
            $this->db->insert('termination_type',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination type added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination type added failed!';
            }
        
        echo json_encode($datas);

        exit;

    } 

    public function get_termination()
    {
    	$query=$this->db->get('termination_type');
        $result= $query->result();
        $data=array();
		foreach($result as $r)
		{
			$data['value']=$r->id;
			$data['label']=$r->termination_type;
			$json[]=$data;
			
			
		}
		echo json_encode($json);
		exit;
    }

       public function termination_list()
    {
        $list = $this->terminations->get_datatables();
        $data = array();
        $no = $_POST['start'];
		$style_w="display:none";
		$style_d="display:none";
		if(App::is_permit('menu_termination','write'))
		{
			$style_w="";	
		}
		if(App::is_permit('menu_termination','delete'))
		{
			$style_d="";
		}
		
		
        $a=1;
         foreach ($list as $termination) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $termination->fullname;
            $row[] = $termination->deptname;
            $row[] = $termination->terminationtypes;
            $row[] = date('d M Y',strtotime($termination->terminationdate));
            $row[] = $termination->reason;
            $row[] = date('d M Y',strtotime($termination->lastdate));

            
		if(App::is_permit('menu_termination','write')==true || App::is_permit('menu_termination','delete')==true)
			{
					$row[]='<div class="dropdown dropdown-action">
                            <a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
							<div class="dropdown-menu float-right">
                                <a class="dropdown-item" href="#" onclick="edit_termination('.$termination->id.')" style="'.$style_w.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                <a class="dropdown-item" href="#" onclick="delete_terminations('.$termination->id.')" style="'.$style_d.'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                            </div>
							</div>';

			}
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->terminations->count_all(),
            "recordsFiltered" => $this->terminations->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function termination_edit($id)
    {
        $data = $this->terminations->get_by_id($id);

        echo json_encode($data);
        exit;
    }

   




    public function add_termination()
    {

    	
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');
       
      
            $data = array(
                'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                'reason'=>$reason,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('termination',$data);
           
            $result=($this->db->affected_rows()!= 1)? false:true;
            $login_user_details = $this->db->get_where('users',array('id'=>$employee))->row_array();
            $termination_type = $this->db->get_where('termination_type',array('id'=>$termination_type))->row_array();
            $data = array(
                'module' => 'termination',
                'module_field_id' => $employee,
                'user' => $employee,
                'activity' => 'Terminated by '.User::displayName($this->session->userdata('user_id')),
                'icon' => 'fa-plus',
            );
            App::Log($data);
            if($result==true) 
            {
                // echo print_r($termination_type); exit;
                $subject_admin         = "Termination Letter";
                    $message_admin         = '<div style="height: 7px; background-color: #535353;"></div>
                                        <div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
                                            <div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Termination</div>
                                            <div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
                                                <p> Hi '.User::displayName($employee).',</p>
                                                <p><b>Termination Type </b> : '.$termination_type['termination_type'].'</p>
                                                <p><b>Reason </b> : '.$reason.'</p>  
                                                <p><b>Termination Date </b> : '.$terminationdate.'</p>
                                                <p><b>Last Date </b> : '.$lastdate.'</b></p>     
                                                                                    
                                                <br> 
                                                
                                                &nbsp;&nbsp;  
                                                
                                                <br>
                                                </big><br><br>Regards<br>The '.config_item('company_name').' Team
                                            </div>
                                     </div>';       
                    
                        
                        $params['recipient'] = $login_user_details['email'];
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject_admin;
                        $params['message'] = $message_admin;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                $datas['result']='yes';
                $datas['status']='Termination added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_termination()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $termination_type=$this->input->post('termination_type');
        $lastdate=date('Y-m-d',strtotime($this->input->post('lastdate')));
        $terminationdate=date('Y-m-d',strtotime($this->input->post('terminationdate')));
        $reason=$this->input->post('reason');

        
            $data = array(
                'employee'=>$employee,
                'lastdate'=>$lastdate,
                'termination_type'=>$termination_type,
                'terminationdate'=>$terminationdate,
                'reason'=>$reason,
                );
            $this->db->where('id',$id);
            $this->db->update('termination',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Termination update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Termination update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function termination_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->terminations->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    } 




	 



}
