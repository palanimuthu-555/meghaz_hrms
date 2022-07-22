<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holidays extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        /*if (!User::is_admin()) {
            $this->session->set_flashdata('message', lang('access_denied'));
            redirect('');
        }*/
        //   App::module_access('menu_holidays');
		if(!App::is_access('menu_holidays'))
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
			$this->template->title(lang('holidays').' - '.config_item('company_name'));			
			$data['datatables']     = TRUE;
			$data['datepicker']     = TRUE;
			$data['page']           = lang('holidays');
			$data['role']           = $this->tank_auth->get_role_id();
			$data['holidays']       = $this->db->query("select * from dgt_holidays where DATE_FORMAT(holiday_date,'%Y') = ".date('Y')." and status = 0 order by holiday_date  ASC ")->result_array();
			$this->template
				 ->set_layout('users')
				 ->build('holidays',isset($data) ? $data : NULL);
	    }else{
		   redirect('');	
		}			 
     }
	function add()
	{
		if ($this->input->post()) {
 			$this->db->where('holiday_date',date('Y-m-d',strtotime($this->input->post('holiday_date'))));
			$this->db->where('status',0);
			$already = $this->db->count_all_results('dgt_holidays');
			if($already==0){				
				$this->db->where('title',$this->input->post('holiday_title'));
			 	$this->db->where("DATE_FORMAT(holiday_date,'%Y')",date('Y'));
				$this->db->where('status',0);
				$already_title = $this->db->count_all_results('dgt_holidays');
				if($already_title ==0){
					$det['title']         = $this->input->post('holiday_title');
		 			$det['holiday_date']  = date('Y-m-d',strtotime($this->input->post('holiday_date')));
					$det['description']   = $this->input->post('holiday_description');
					$this->db->insert('dgt_holidays',$det);
					$this->session->set_flashdata('tokbox_success', 'Holiday created Successfully');
				}else{
					 $this->session->set_flashdata('tokbox_error', 'Holiday Already exists !');
				}
			}else{
				 
				 $this->session->set_flashdata('tokbox_error', 'Holiday Already exists !');
			}
			redirect('holidays');
		}else{
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title(lang('holidays').' - '.$this->config->item('company_name')); 
 			$data['datepicker']     = TRUE;
			$data['page']           = lang('holidays');
			$data['role']           = $this->tank_auth->get_role_id();
  			$this->template
			->set_layout('users')
			->build('create_holiday',isset($data) ? $data : NULL);
 		}
		
	} 
	function edit()
	{
		if ($this->input->post()) {
 			$this->db->where('holiday_date',date('Y-m-d',strtotime($this->input->post('holiday_date'))));
			$this->db->where('id !=',$this->input->post('holiday_tbl_id'));
			$this->db->where('status',0);
			$alreay = $this->db->count_all_results('dgt_holidays');
			if($alreay == 0){
				
				$this->db->where('title',$this->input->post('holiday_title'));
			 	$this->db->where("DATE_FORMAT(holiday_date,'%Y')",date('Y'));
				$this->db->where('status',0);
				$this->db->where('id !=',$this->input->post('holiday_tbl_id'));
				$already_title = $this->db->count_all_results('dgt_holidays');
				if($already_title ==0){
					$det['title']         = $this->input->post('holiday_title');
		 			$det['holiday_date']  = date('Y-m-d',strtotime($this->input->post('holiday_date')));
					$det['description']   = $this->input->post('holiday_description');
					$this->db->update('dgt_holidays',$det,array('id'=>$this->input->post('holiday_tbl_id'))); 
				}else{
					$this->session->set_flashdata('tokbox_error', 'Holiday Already exists !');
				}
 			}else{
 				$this->session->set_flashdata('tokbox_error', 'Holiday Already exists !');
 			}
    		redirect('holidays');
		}else{
			$this->load->module('layouts');
			$this->load->library('template');
			$this->template->title(lang('holidays').' - '.$this->config->item('company_name')); 
 			$data['datepicker']     = TRUE;
			$data['page']           = lang('holidays');
			$data['role']           = $this->tank_auth->get_role_id();
			$data['holidays_det']   = $this->db->query("select * from dgt_holidays where id = ".$this->uri->segment(3)."")->result_array();
 			//print_r($data['holydays_det']);exit;
   			$this->template
			->set_layout('users')
			->build('edit_holiday',isset($data) ? $data : NULL);
 		}
 	} 
	function delete()
	{
		if ($this->input->post()) {
			$det['status']        = 1; 
			$this->db->update('dgt_holidays',$det,array('id'=>$this->input->post('holiday_tbl_id'))); 
			redirect('holidays');
 		}else{
			$data['holiday_id'] = $this->uri->segment(3);
			$this->load->view('modal/delete_holiday',$data);
		} 
	}
	function year_holidays()
	{
		 $year     = $this->input->post('year');
		 $holidays = $this->db->query("select * from dgt_holidays where DATE_FORMAT(holiday_date,'%Y') = ".$year." and status = 0 order by holiday_date ASC ")
		                      ->result_array();
 		 $i        = 1;  
		 $html     = '';
		 foreach($holidays as $key => $hldays){
		 $curdate       = strtotime(date('d-m-Y'));
		 $hlidate       = strtotime($hldays['holiday_date']); 
 		 $bg_color = ''; 
		 if($curdate > $hlidate){ $bg_color = 'bgcolor="#F6CECE" class="holiday-completed"'; }else{ $bg_color = 'bgcolor="#A9F5A9"'; }
         $html     .= '<tr '.$bg_color.'>
							<td>'.$i.'</td>
							<td>'.$hldays['title'].'</td>
							<td>'.date('d-m-Y',strtotime($hldays['holiday_date'])).'</td>
							<td width="50%">'.$hldays['description'].'</td>';
			                if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') {
			                if($curdate > $hlidate){ $url = '#'; $del_url='#'; $dis = 'disabled'; $modl = ''; }else{ $url = base_url().'holidays/edit/'.$hldays['id']; $del_url=base_url().'holidays/delete/'.$hldays['id']; $dis=''; $modl='data-toggle="ajaxModal"'; }  
				$html     .= '<td class="text-right"> 
								<a href="'.$url.'" '.$dis.' class="btn btn-success btn-sm"  title="'.lang('edit').'">
									<i class="fa fa-edit"></i> 
								 </a>
								 <a class="btn btn-danger btn-sm" title="Delete" '.$modl.'  href="'.$del_url.'" '.$dis.' data-original-title="Delete">
									<i class="fa fa-trash-o"></i>
								 </a>
							 </td>';
						   }  
			$html     .= '</tr>';
	      $i++; }    	  
 		  if($html == ''){
			  $html   = '<tr>
							<td class="text-center" colspan="5">No Data Available</td>
						 </tr>';
		  } 
 		  echo $html;
		  exit;
	}

  	function check_holiday_name()
    {
        $holiday_name = $this->input->post('holiday_name');
        $this->db->where("DATE_FORMAT(holiday_date,'%Y')",date('Y'));
        $this->db->where("title",$holiday_name);
        $check_holiday_name = $this->db->get('dgt_holidays')->num_rows();
        if($check_holiday_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    function check_edit_holiday_name()
    {
        $holiday_name = $this->input->post('holiday_name');
        $id = $this->input->post('id');
        $this->db->where("DATE_FORMAT(holiday_date,'%Y')",date('Y'));
        $this->db->where("title",$holiday_name);
        $this->db->where("id !=",$id);
        $check_holiday_name = $this->db->get('dgt_holidays')->num_rows();
        if($check_holiday_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
}
