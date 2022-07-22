<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ticket_priority extends MX_Controller
{
    public function __construct()
    {   
        parent::__construct();
        User::logged_in();

        $this->load->library(array('form_validation'));
        $this->load->model(array('App', 'Lead', 'priority_model'));
       if(!App::is_access('menu_priority'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
        $this->lead_view = (isset($_GET['list'])) ? $this->session->set_userdata('lead_view', $_GET['list']) : 'kanban';
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('priority').' - '.config_item('company_name'));
        $data['page'] = lang('priority');
        $data['form'] = true;
        $data['datatables'] = true;
        $data['leads_plugin'] = true;
        $data['fuelux'] = true;
        $data['list_view'] = $this->session->userdata('lead_view');
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $data['priorities'] = priority_model::all();          
        $this->template
                ->set_layout('users')
                ->build('ticket_priority/index', isset($data) ? $data : null);
    }


    function add($priority_id='')
    { 
        if ($_POST||$priority_id!='') 
        {  
            if(sizeof(array_filter($_POST))<1&&!empty($priority_id))
            { 
                $priority = priority_model::find($priority_id);    
                $data = [];
                $priority_array = [];
                foreach ($priority as $key=>$val)
                {
                    $priority_array[$key]=$val;
                }    
                $data['priority'] = $priority_array;     
                $this->load->view('add',$data);
            }
            if(sizeof(array_filter($_POST))>0)
            {   
                if(isset($_POST['id'])&&!empty($_POST['id']))
                {
                    $priority_id = $_POST['id'];
                }
                // $current_date_time = date('Y-m-d H:i:s'); 
                if(isset($_POST['edit'])&&$_POST['edit']=="true"&&!empty($priority_id))
                {
                    $priority_exists = priority_model::priority_exists($_POST['title'],$_POST['id']); 
                    if(!$priority_exists)
                    {
                        $priority_id = $_POST['id'];
                        unset($_POST['edit']);
                        unset($_POST['id']);
                        // $_POST['modified_date'] = $current_date_time;
                        App::update('priorities',array('id'=>$priority_id),$this->input->post());     
                        $this->session->set_flashdata('tokbox_success', lang('priority_updated_successfully'));
			            redirect('ticket_priority');
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('priority_exists'));
			            redirect('ticket_priority');
                    }
                }
                else 
                {
                    $priority_exists = priority_model::priority_exists($_POST['title']); 
                    if(!$priority_exists)
                    {
                        // $_POST['created_date'] = $current_date_time;
                        App::save_data('priorities',$this->input->post());     
                    }
                    else 
                    {
                        $this->session->set_flashdata('tokbox_error', lang('priority_exists'));
			            redirect('ticket_priority');
                    }
                }                            
                $this->session->set_flashdata('tokbox_success', lang('priority_added_successfully'));
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        else
        {    
            $this->load->view('add');
        }
    }
    function check_priority_name()
    {
        $check_priority_name = $this->input->post('check_priority_name');
        $priority_id = $this->input->post('priority_id');
        if(!empty($priority_id)){
            $this->db->where("id !=",$priority_id);
        }
        $this->db->where("priority",$check_priority_name);
        $check_priority_name = $this->db->get('dgt_priorities')->num_rows();
        if($check_priority_name > 0)
        {
            echo "yes";
        }else{
            echo "no";
        }
        exit;
    }
    public function delete($id='')
    {
        if ($this->input->post()) 
        {
            $id = $this->input->post('id', true);
            priority_model::delete($id);  
            $this->session->set_flashdata('tokbox_success', lang('priority_deleted_successfully'));
            redirect('ticket_priority');
        } 
        else 
        {
            if($id!='')
            {
                $data['id'] = $id;
                $this->load->view('delete',$data);
            }
        }
    }

    
}