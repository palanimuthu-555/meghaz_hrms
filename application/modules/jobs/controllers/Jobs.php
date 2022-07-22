<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
require_once(APPPATH . '../vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

class Jobs extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Offer','Jobs_model'));
        
        $this->load->library(array('form_validation')); 

        $this->apiKey = config_item('apikey_tokbox');
        $this->apiSecret = config_item('apisecret_tokbox');
        $this->login_id = $this->session->userdata('user_id');
        $this->session->set_userdata('time_zone',date_default_timezone_set());
    }

    public function index()
    {   
	
		
		if(!App::is_access('recruiting_dashboard'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}

    $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('recruiting_process').' - '.config_item('company_name'));
        $data['page'] = lang('job_dashboard');
        $data['sub_page'] = lang('job_dashboard');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
      $total_jobs =  $this->Jobs_model->select_numrows('jobs'); /* total jobs count*/
      $data['total_jobs'] = $total_jobs->num_rows();
      $total_candidates =  $this->Jobs_model->select_numrows('registered_candidates'); /*job seekers count*/
      $data['total_candidates'] = $total_candidates->num_rows();

     $data['latest_jobs'] = $this->Jobs_model->get_latest_jobs();
     $where['candidate_job_status.status'] = 1; /* applied users */
     $where['candidate_files.file_type'] = 1; /* for resume files only */
      $where['jobs.deleted_at'] =NULL;
     $data['applicants_list'] = $this->Jobs_model->select_resumes_list($where);
     //echo (count($data['applicants_list']));exit; 
     //echo $this->db->last_query();exit;
     $cond['user_job_status >= '] =7;
     $offered =  $this->Jobs_model->select_offered_count($cond);
      $data['offered_users'] = array();
     foreach($offered as $key => $offer){
          $data['offered_users'][] = $offer->offered_count;
     }

     $data['month_wise'] = $month_wise= $this->Jobs_model->select_month_wise_details(array('status'=>1));

     foreach($month_wise as $key => $list){
        $data['month_wise_total_user'][$list->month][] = $list->candidate_id;
        if($list->user_job_status==7 || $list->user_job_status==8){
          $data['month_wise_offer_user'][$list->month][] = $list->candidate_id;
        }
        
     }
		



    

        $this->template
                ->set_layout('users')
                ->build('dashboard', isset($data) ? $data : null);
    }
	
   
	public function manage()
    {
		if(!App::is_access('menu_manage_jobs'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}
		
		
		
		
		
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('manage_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('manage_jobs');
        $data['sub_page'] = lang('manage_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $where['jobs.deleted_at'] = NULL; /* get only undeleted jobs*/
        $data['jobs_list'] = $this->Jobs_model->select_jobs_list($where);
        $applications = $this->Jobs_model->select_applications(array('status'=>1)); /*get only applied candidates*/
        $data['applications'] = [];
        foreach($applications as $appl){
           $data['applications'][$appl->job_id] = $appl->count;
        } 
       // print_r($data['applications']);exit; 

        $data['job_types'] = $this->Jobs_model->select('jobtypes',array('status'=>1)); /* to fetch job types*/

        $this->template
                ->set_layout('users')
                ->build('manage_jobs', isset($data) ? $data : null);
    }
	public function add()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('add_jobs').' - '.config_item('company_name'));

        $data['page'] = lang('manage_jobs');
        $data['sub_page'] = lang('add_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        if($this->input->post('submit')){
          $this->form_validation->set_rules('job_title','','trim|required');
          $this->form_validation->set_rules('department','','trim|required');
          $this->form_validation->set_rules('position','','trim|required');
          $this->form_validation->set_rules('job_location','','trim|required');
          $this->form_validation->set_rules('country_id','','trim|required');
          $this->form_validation->set_rules('no_of_vacancy','','trim|required');
          $this->form_validation->set_rules('experience','','trim|required');
          $this->form_validation->set_rules('age','','trim|required');
          $this->form_validation->set_rules('salary_from','','trim|required');
          $this->form_validation->set_rules('salary_to','','trim|required');
          $this->form_validation->set_rules('job_type','','trim|required');
          $this->form_validation->set_rules('status','','trim|required');
          $this->form_validation->set_rules('start_date','','trim|required');
          $this->form_validation->set_rules('expired_date','','trim|required');
          if($this->form_validation->run()){
            if(empty($_FILES['image_file']['name'])){
             // echo 1233; exit; 
              $this->session->set_flashdata('tokbox_error', lang('please_select_job_image'));
             redirect('jobs/add');
            }
            //print_r($_FILES);exit;

            $ins['job_title'] = $this->input->post('job_title');
            $ins['department_id'] = $this->input->post('department');
            $ins['position_id'] = $this->input->post('position');
            $ins['job_location'] = $this->input->post('job_location');
            $ins['country_id'] = $this->input->post('country_id');
            $ins['no_of_vacancy'] = $this->input->post('no_of_vacancy');
            $ins['experience'] = $this->input->post('experience');
            $ins['experience_level_id'] = $this->input->post('experience_level');
            $ins['age'] = $this->input->post('age');
            $ins['salary_from'] = $this->input->post('salary_from');
            $ins['salary_to'] = $this->input->post('salary_to');
            $ins['job_type_id'] = $this->input->post('job_type');
            $ins['job_status'] = $this->input->post('status');
            $start_date = str_replace('/', '-', date('Y-m-d',strtotime($this->input->post('start_date'))));
            $expired_date = str_replace('/', '-',date('Y-m-d',strtotime($this->input->post('expired_date'))));
           
            $ins['start_date'] = date('Y-m-d h:i:s',strtotime($start_date));
            $ins['expired_date'] = date('Y-m-d',strtotime($expired_date));
            $ins['description'] = $this->input->post('description');
            $ins['created_by'] = $this->session->userdata('user_id');
            if(!empty($_FILES['image_file']['name'])){

             $files_detail = $this->Jobs_model->image_upload('image_file','jobs');
             $file_name = $files_detail['file_name'];
             $ins['job_image'] = $file_name;    
           }

            //print_r($ins);exit;
           $add = $this->Jobs_model->insert('jobs',$ins);
           if($add){
             $this->session->set_flashdata('tokbox_success', lang('job_added_successfully'));
             redirect('jobs/add');
           }else{
             $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
             redirect('jobs/add');
           }

          }else{
             $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
             redirect('jobs/add');
          }
        }

        $data['countries'] = $this->Jobs_model->select('countries');
         $data['experience_levels'] = $this->Jobs_model->select('experience_level');
        $data['job_types'] = $this->Jobs_model->select('jobtypes',array('status'=>1)); /* to fetch job types*/
        $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch job types*/
        

        $this->template
                ->set_layout('users')
                ->build('add_jobs', isset($data) ? $data : null);
    }



    function candidates(){
		
		if(!App::is_access('menu_candidate_list'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		


        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('candidate_list').' - '.config_item('company_name'));
        $data['page'] = lang('candidate_list');
        $data['sub_page'] = lang('candidate_list');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        
        $data['candidates_list'] = $this->Jobs_model->select_candidates();
         $this->template
                ->set_layout('users')
                ->build('candidate_list', isset($data) ? $data : null);

    }

    function candidates_board(){
      $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('candidate_list').' - '.config_item('company_name'));
        $data['page'] = lang('candidate_list');
        $data['sub_page'] = lang('candidate_list');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        
        $data['candidates_list'] = $this->Jobs_model->select_candidates();

        $where['candidate_job_status.status'] =1;
        $where['candidate_files.file_type'] =1;
        $where['jobs.deleted_at'] =NULL;
        $data['candidates_status'] = $this->Jobs_model->select_resumes_list($where);


    
         $this->template
                ->set_layout('users')
                ->build('candidate_board_view', isset($data) ? $data : null);
    }

    function add_candidates(){

       $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('add_candidates').' - '.config_item('company_name'));
        $data['page'] = lang('candidate_list');
        $data['sub_page'] = lang('add_candidate');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        /*save candidate details */

        if($this->input->post('save_contact')){

          if($this->input->post('candidate_id')){
              $redirect_url = 'jobs/add_candidates/'.$this->input->post('candidate_id');
             }else{
              $redirect_url = 'jobs/add_candidates';
             }
           //print_r($_POST);exit;
            $this->form_validation->set_rules('first_name','','trim|required');
            $this->form_validation->set_rules('last_name','','trim|required');
            $this->form_validation->set_rules('email','','trim|required');
            $this->form_validation->set_rules('address','','trim|required');
            $this->form_validation->set_rules('country','','trim|required');
            $this->form_validation->set_rules('state','','trim|required');
            $this->form_validation->set_rules('city','','trim|required');
            $this->form_validation->set_rules('pincode','','trim|required');
            $this->form_validation->set_rules('phone_number','','trim|required');
            if(!$this->input->post('candidate_id')){
            $this->form_validation->set_rules('password','','trim|required');

            }
          
          if($this->form_validation->run()){

            if(!$this->input->post('candidate_id'))
            {
            $where['email'] = $this->input->post('email');
            $already_email = $this->Jobs_model->select_check('registered_candidates',array('email'=>$this->input->post('email')));
            if($already_email->num_rows()>0){
              $this->session->set_flashdata('tokbox_error',lang('email_already_exits'));
                redirect('jobs/add_candidates'); 
            }
            }
            $basic_upd['first_name'] = $this->input->post('first_name');
            $basic_upd['last_name'] = $this->input->post('last_name');
            $basic_upd['email'] = $this->input->post('email');
            if($this->input->post('password')!=""){
            $basic_upd['password'] = md5($this->input->post('password'));
            }
            $basic_upd['status'] = 1;
            $basic_upd['job_category_id'] = json_encode($this->input->post('job_category'),true);
            $basic_upd['position_type_id'] = json_encode($this->input->post('position_type'),true);

             if($this->input->post('candidate_id'))
             {
              $update_basic = $this->Jobs_model->update('registered_candidates',$basic_upd,array('id'=>$this->input->post('candidate_id'))); 

             }else{
              $update_basic = $this->Jobs_model->insert('registered_candidates',$basic_upd); 
             }
            

           
            $upd_data['address'] = $this->input->post('address');
            $upd_data['country'] = $this->input->post('country');
            $upd_data['state'] = $this->input->post('state');
            $upd_data['city'] = $this->input->post('city');
            $upd_data['pincode'] = $this->input->post('pincode');
            $upd_data['phone_number'] = $this->input->post('phone_number');
            if($this->input->post('web_address')!=""){
             $upd_data['web_address'] = $this->input->post('web_address');   
            }
            if(!empty($_FILES['resume_file']['name'])){

             $files_detail = $this->Jobs_model->upload('resume_file');
             $file_name = $files_detail['file_name'];
             $file_data['file_name'] = $file_name;           
                     
             if($this->input->post('candidate_id')){
              $where_cond['file_type'] = 1;
              $where_cond['candidate_id'] = $this->input->post('candidate_id');
              $add_file  = $this->Jobs_model->update('candidate_files',$file_data,$where_cond);
             }else{
               $file_data['file_type'] = 1; 
               $file_data['title'] = lang('resume'); 
               $file_data['candidate_id'] = $update_basic; 
              $add_file = $this->Jobs_model->insert('candidate_files',$file_data);
             }        
            
            }
            /* save education info*/
            if($this->input->post('school_name')){
             $upd_data['school_name'] = $this->input->post('school_name');
             $upd_data['passed_out_year'] = $this->input->post('passed_out_year');
             $upd_data['major_subject'] = $this->input->post('major_subject');
             $upd_data['degree'] = $this->input->post('degree');
             $upd_data['gpa'] = $this->input->post('gpa');
            }
            $upd_data['skills'] = $this->input->post('skills');
          /*Save experiance*/
          if($this->input->post('company_name')){
          $company_name = $this->input->post('company_name'); 
          $location = $this->input->post('location'); 
          $job_position = $this->input->post('job_position'); 
          $period_from = $this->input->post('period_from'); 
          $period_to = $this->input->post('period_to');
          $experineces = array();
          for($i = 0; $i< count($company_name); $i++)
          {
              $experinece = array(
                  'company_name'=>$company_name[$i],
                  'location'=>$location[$i],
                  'job_position'=>$job_position[$i],
                  'period_from'=>$period_from[$i],
                  'period_to'=>$period_to[$i]
              );
              $experineces[] = $experinece;
          }
          // echo $user_id; exit;
          $upd_data ['experience_details'] = json_encode($experineces);
          }

           $checkinfo = $this->db->where('candidate_id',$this->input->post('candidate_id'))->get('candidate_additional_information')->num_rows();
            //echo $checkinfo;exit; 
            if($this->input->post('candidate_id') && $checkinfo!=0){
              $result  =$this->Jobs_model->update('candidate_additional_information',$upd_data,array('candidate_id'=>$this->input->post('candidate_id')));
            }else{
                if($this->input->post('candidate_id')){
                 $upd_data['candidate_id'] =  $this->input->post('candidate_id');
              }else{
                 $upd_data['candidate_id'] =  $update_basic;
              }
                //$upd_data['candidate_id'] =  $update_basic;
                $result  =  $this->Jobs_model->insert('candidate_additional_information',$upd_data);
            }

            //echo $this->db->last_query();exit; 
            if($result){
                $this->session->set_flashdata('tokbox_success',lang('details_saved_successfully'));
                redirect($redirect_url); 
            }else{
                $this->session->set_flashdata('tokbox_error',lang('something_went_to_wrong'));
                redirect($redirect_url); 
            }

          }else{
             $this->session->set_flashdata('tokbox_error',lang('some_feilds_missing'));
             redirect($redirect_url); 
          }
        }

      

       
      //  print_r($_POST);exit; 

          if($this->uri->segment(3)){
            //echo $this->uri->segment(3);exit; 
            $where['a.id'] =  $this->uri->segment(3);
            $where['c.file_type'] = 1;
            $data['candidate_detail'] =  $this->Jobs_model->select_candidate_details($where);
            $category_array = json_decode($data['candidate_detail']['job_category_id'],true);
           // print_r($category_array);exit; 
            $data['designations'] = $this->Jobs_model->select_where_in('designation',$category_array,'department_id');
           // echo $this->db->last_query();exit; 
       
          }

          $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch job types*/


         $this->template
                ->set_layout('users')
                ->build('candidate_form', isset($data) ? $data : null);


    }


     function delete_candidate(){

        if($this->input->post()){
            $where['id'] = $this->input->post('candidate_id');
            $delete = $this->Jobs_model->delete('registered_candidates',$where);
            $infodelete = $this->Jobs_model->delete('candidate_additional_information',array('candidate_id'=>$this->input->post('candidate_id')));
            $file_delete = $this->Jobs_model->delete('candidate_files',array('candidate_id'=>$this->input->post('candidate_id')));
            if($delete){
                $this->session->set_flashdata('tokbox_success', lang('deleted_successfully'));
                redirect('jobs/candidates');
            }else{
                $this->session->set_flashdata('tokbox_error',  lang('something_went_to_wrong'));
                redirect('jobs/candidates');
             }

        }else{

             $data['candidate_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_candidate', $data);
        }

    }

    function experience(){
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('experience_level').' - '.config_item('company_name'));
        $data['page'] = lang('experience_level');
        $data['sub_page'] = lang('experience_level');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages(); 
        $data['experience_levels'] = $this->Jobs_model->select('experience_level');


         $this->template
                ->set_layout('users')
                ->build('experience_level', isset($data) ? $data : null);

    }
    function add_experience(){
      if($this->uri->segment(3)!=""){
        $data['exp_level'] = $this->Jobs_model->select_row_array('experience_level',array('id'=>$this->uri->segment(3)));
        $data['form_type'] = 'Edit';
      }else{
        $data['exp_level'] = [];
        $data['form_type'] = 'Add';
      }
      if($this->input->post('submit')){

      $add['experience_level'] =  $this->input->post('experience');
      $add['status'] = $this->input->post('status');
      if($this->input->post('id')){
       $result =   $this->Jobs_model->update('experience_level',$add,array('id'=>$this->input->post('id')));
      }else{
        $result =  $this->Jobs_model->insert('experience_level',$add);
      }
      if($result){
        $this->session->set_flashdata('tokbox_success', lang('experience_level_updated'));
        redirect('jobs/experience');
      }else{
        $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
        redirect('jobs/experience');
      }
      
    }
      
      $this->load->view('modal/experience_add',$data);
    }
    function delete_experience(){
		if(!App::is_access('menu_experience_level'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		


        if($this->input->post()){
            $where['id'] = $this->input->post('experience_id');
            $delete = $this->Jobs_model->delete('experience_level',$where);
            if($delete){
                $this->session->set_flashdata('tokbox_success', lang('deleted_successfully'));
                redirect('jobs/experience');
            }else{
                $this->session->set_flashdata('tokbox_error',  lang('something_went_to_wrong'));
                redirect('jobs/experience');
             }

        }else{

             $data['experience_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete_experience', $data);
        }

    }
	public function edit()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('edit_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('manage_jobs');
        $data['sub_page'] = lang('edit_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

      if($this->input->post('submit')){

          $this->form_validation->set_rules('job_title','','trim|required');
          $this->form_validation->set_rules('department','','trim|required');
          $this->form_validation->set_rules('position','','trim|required');
          $this->form_validation->set_rules('job_location','','trim|required');
          $this->form_validation->set_rules('country_id','','trim|required');
          $this->form_validation->set_rules('no_of_vacancy','','trim|required');
          $this->form_validation->set_rules('experience','','trim|required');
          $this->form_validation->set_rules('age','','trim|required');
          $this->form_validation->set_rules('salary_from','','trim|required');
          $this->form_validation->set_rules('salary_to','','trim|required');
          $this->form_validation->set_rules('job_type','','trim|required');
          $this->form_validation->set_rules('status','','trim|required');
          $this->form_validation->set_rules('start_date','','trim|required');
          $this->form_validation->set_rules('expired_date','','trim|required');
          if($this->form_validation->run()){

            $upd['job_title'] = $this->input->post('job_title');
            $upd['department_id'] = $this->input->post('department');
            $upd['position_id'] = $this->input->post('position');
            $upd['job_location'] = $this->input->post('job_location');
            $upd['country_id'] = $this->input->post('country_id');
            $upd['no_of_vacancy'] = $this->input->post('no_of_vacancy');
            $upd['experience'] = $this->input->post('experience');
            $upd['experience_level_id'] = $this->input->post('experience_level');
            $upd['age'] = $this->input->post('age');
            $upd['salary_from'] = $this->input->post('salary_from');
            $upd['salary_to'] = $this->input->post('salary_to');
            $upd['job_type_id'] = $this->input->post('job_type');
            $upd['job_status'] = $this->input->post('status');
            $start_date = str_replace('/', '-', date('Y-m-d',strtotime($this->input->post('start_date'))));
            $expired_date = str_replace('/', '-',date('Y-m-d',strtotime($this->input->post('expired_date'))));
           
            $upd['start_date'] = date('Y-m-d h:i:s',strtotime($start_date));
            $upd['expired_date'] = date('Y-m-d',strtotime($expired_date));
            $upd['description'] = $this->input->post('description');
            if(!empty($_FILES['image_file']['name'])){

             $files_detail = $this->Jobs_model->image_upload('image_file','jobs');
             $file_name = $files_detail['file_name'];
             $upd['job_image'] = $file_name; 
           }
          
           $update = $this->Jobs_model->update('jobs',$upd,array('id'=>$this->uri->segment(3)));
           if($update){
             $this->session->set_flashdata('tokbox_success', lang('job_updated_successfully'));
             redirect('jobs/manage');
           }else{
             $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
             redirect('jobs/manage');
           }

          }else{
             $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
             redirect('jobs/manage');
          }
        }
        $data['countries'] = $this->Jobs_model->select('countries');
        $data['experience_levels'] = $this->Jobs_model->select('experience_level');
        $data['job_types'] = $this->Jobs_model->select('jobtypes',array('status'=>1)); /* to fetch job types*/
        $data['departments'] = $this->Jobs_model->select('departments'); /* to fetch job category*/
        $data['job'] = $this->Jobs_model->select_row('jobs',array('id'=>$this->uri->segment(3)));

       $data['positions'] = $this->Jobs_model->select('designation',array('department_id'=>$data['job'][0]['department_id'])); /* to fetch job category*/
   
        $this->template
                ->set_layout('users')
                ->build('edit_jobs', isset($data) ? $data : null);
    }

    function delete(){

        if($this->input->post()){
            $where['id'] = $this->input->post('jobid');
            $upd['deleted_at'] = date('Y-m-d');
            $delete = $this->Jobs_model->update('jobs',$upd,$where);
            if($delete){
                $this->session->set_flashdata('tokbox_success', lang('job_deleted_successfully'));
                redirect('jobs/manage');
            }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect('jobs/manage');
             }

        }else{

             $data['job_id'] = $this->uri->segment(3);
            $this->load->view('modal/delete', $data);
        }

    }
    function change_jobtype(){
        $job_type_id = $this->uri->segment(3); /* job type id*/
        $job_id = $this->uri->segment(4); /* job id*/
        $change = $this->Jobs_model->update('jobs',array('job_type_id'=>$job_type_id),array('id'=>$job_id));
        
         if($change){
                $this->session->set_flashdata('tokbox_success', lang('job_type_changed_successfully'));
                redirect('jobs/manage');
            }else{
                $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
                redirect('jobs/manage');
             }
         
    }
     function status(){
        $job_status = $this->uri->segment(3); /* job status */
        $job_id = $this->uri->segment(4); /* job id*/
        $change = $this->Jobs_model->update('jobs',array('job_status'=>$job_status),array('id'=>$job_id));
        
        if($change){
            $this->session->set_flashdata('tokbox_success', lang('job_status_changed_successfully'));
            redirect('jobs/manage');
        }else{
            $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
            redirect('jobs/manage');
        }
         
    }
	public function view_jobs()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('view_jobs').' - '.config_item('company_name'));
        $data['page'] = lang('manage_jobs');
        $data['sub_page'] = lang('view_jobs');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        

       $where['job_id'] = $this->uri->segment(3);
       $data['total_views'] = $this->Jobs_model->get_counts('candidate_visited_jobs',$where);
       $where['status'] =1; /* for applied jobs*/
       $data['applications'] = $this->Jobs_model->get_counts('candidate_job_status',$where);
       //echo $this->db->last_query();exit; 

      // $data['total_applications'] = $this->Jobs_model->('candidate_job_status',$where,'candidate_id');
       $data['jobs_detail'] = $this->Jobs_model->select_jobs_detail(array('jobs.id'=>$this->uri->segment(3))); /* get jobs details*/

    
        $this->template
                ->set_layout('users')
                ->build('view_jobs', isset($data) ? $data : null);
    }

    public function apptitude_result(){
		
		if(!App::is_access('menu_apptitude_results'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		


      $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('apptitude_results').' - '.config_item('company_name'));
        $data['page'] = lang('apptitude_results');
        $data['sub_page'] = lang('apptitude_results');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        

        $data['completed_users'] = $this->Jobs_model->get_apps_completed_user();
        //echo $this->db->last_query();exit; 
        $this->template
                ->set_layout('users')
                ->build('apptitude_result', isset($data) ? $data : null);

    }

    public function question_answer()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('question_answer').' - '.config_item('company_name'));
        $data['page'] = lang('apptitude_results');
        $data['sub_page'] = lang('question_answer');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $user_id = $this->uri->segment(3);
        $job_id = $this->uri->segment(4);

        $data['categories'] = $this->Jobs_model->select('question_category',array('job_id'=>$job_id));

        $data['user_detail'] = $this->Jobs_model->select_row_array('registered_candidates',array('id'=>$user_id));
        //echo $this->db->last_query();exit; 
        $data['job_details'] = $this->Jobs_model->select_row_array('jobs',array('id'=>$job_id));


        $where['a.job_id'] = $job_id;
        $where['a.user_id'] = $user_id;
        $data['question_list'] = $this->Jobs_model->select_questions_answers($where);
        //echo $this->db->last_query();exit; 
        $this->template
                ->set_layout('users')
                ->build('question_answers', isset($data) ? $data : null);
    }

    function change_answer_status(){
     // echo $this->uri->segment(3);exit; 
      $where['id'] = $this->uri->segment(3);
      $upd['answer_status'] = $this->uri->segment(4);
      $this->Jobs_model->update('apptitude_interview_results',$upd,$where);

        $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
        redirect('jobs/question_answer/'.$this->uri->segment(5).'/'.$this->uri->segment(6));
    }

	public function applicants()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('applicants').' - '.config_item('company_name'));
        $data['page'] = lang('manage_jobs');
        $data['sub_page'] = lang('applicants');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $where['candidate_job_status.status'] = 1; /* applied users */
        $where['candidate_files.file_type'] = 1; /* for resume files only */
        $where['candidate_job_status.job_id'] = $this->uri->segment(3); 
        $data['applicants_list'] = $this->Jobs_model->select_resumes_list($where);
      // echo $this->db->last_query();exit; 

      
        $this->template
                ->set_layout('users')
                ->build('applicants', isset($data) ? $data : null);
    }
    public function manage_resumes()
    {
		
		if(!App::is_access('menu_manage_resumes'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

		
		
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('manage_resumes').' - '.config_item('company_name'));
        $data['page'] = lang('manage_resumes');
        $data['sub_page'] = lang('manage_resumes');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        $where['candidate_job_status.status'] =1;
        $where['candidate_files.file_type'] =1;
        $where['jobs.deleted_at'] =NULL;
        $data['resumes_list'] = $this->Jobs_model->select_resumes_list($where);
       // echo $this->db->last_query();exit; 


        $this->template
                ->set_layout('users')
                ->build('manage_resumes', isset($data) ? $data : null);
    }

    function user_status_change()
    {
      $status = $this->uri->segment(3); /* current status based on admin approval*/
      $user_id =  $this->uri->segment(4); /* candidate id*/
      $where['job_id'] =  $this->uri->segment(5); /* job id*/
      $where['candidate_id'] = $user_id;
      $result =  $this->Jobs_model->update('candidate_job_status',array('user_job_status'=>$status),$where);
     if($result){
        $this->session->set_flashdata('tokbox_success', lang('status_updated_successfully'));
        if($this->uri->segment(6)){
          redirect('jobs/'.$this->uri->segment(6));
        }else{
           redirect('jobs/manage_resumes');
        }
     }else{
        $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
        if($this->uri->segment(6)){
          redirect('jobs/'.$this->uri->segment(6));
        }else{
           redirect('jobs/manage_resumes');
        }
     }
      

    }

     function user_status_update()
    {
      if ($this->input->post()) {
      $status = $this->input->post('status'); /* current status based on admin approval*/
      $user_id =  $this->input->post('user_id');  /* candidate id*/
      $where['job_id'] =  $this->input->post('job_id'); /* job id*/
      $where['candidate_id'] = $user_id;
      $result =  $this->Jobs_model->update('candidate_job_status',array('user_job_status'=>$status),$where);
      echo "updated";exit; 
    }

    }



     public function shortlist_candidates()
	{
		
		if(!App::is_access('menu_shortlist_candidates'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

		
		
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('shortlist_candidates').' - '.config_item('company_name'));
        $data['page'] = lang('shortlist_candidates');
        $data['sub_page'] = lang('shortlist_candidates');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $where['jobs.deleted_at'] =NULL;
        $where['candidate_job_status.status '] = 1; /* applied jobs*/ 
        $where_not= array(0,2); /*get after resume got shortlisted*/
        
        $data['shortlisted'] =  $this->Jobs_model->select_shortlist_candidates($where,$where_not);
        //echo $this->db->last_query();exit; 
      
        $this->template
                ->set_layout('users')
                ->build('shortlist_candidates', isset($data) ? $data : null);
    }

    function schedule_timing(){
		
		if(!App::is_access('menu_schedule_timing'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		


        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('schedule_timing').' - '.config_item('company_name'));
        $data['page'] = lang('schedule_timing');
        $data['sub_page'] = lang('schedule_timing');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        
        $where['jobs.deleted_at'] = NULL;
        $where['candidate_job_status.status'] =1;
      
        $where_not =array(0,2,4); /* apptitude selected*/
        $data['candidates_list'] = $this->Jobs_model->select_users_interview($where,$where_not);
 $data['time_list'] =$this->Jobs_model->time_diffence();
         $this->template
                ->set_layout('users')
                ->build('schedule_timing', isset($data) ? $data : null);

    }

    function chats(){
       $log_id = $this->session->userdata('user_id');
      $log_check = $this->Jobs_model->select_row_array('users',array('id'=>$log_id));
      //echo $log_check['session_id'];exit;

      if($log_check['session_id'] == '')
      {
        //
        $opentok = new OpenTok($this->apiKey, $this->apiSecret);
          // An automatically archived session:
        $sessionOptions = array(
          // 'archiveMode' => ArchiveMode::ALWAYS,
          'mediaMode' => MediaMode::ROUTED
        );
        $new_session = $opentok->createSession($sessionOptions);
          // Store this sessionId in the database for later use
        $sessionId = $new_session->getSessionId();
        $res = array(
          'session_id' => $sessionId
        );

        $this->db->where('id',$log_id);
        $this->db->update('users',$res);
      }

      $data['receiver_id'] = 0;
      $data['profile_img'] = 0;
      $data['receiver_profile_img'] = 0;
      $data['mesage_type'] = 0;
      $data['group_id'] = 0;

    }

    function add_schedule_timing(){
      $user_id = $this->uri->segment(3);
      $job_id = $this->uri->segment(4);
     
    $where['user_id'] =  $user_id;
    $where['job_id'] =  $job_id;
    $already = $this->Jobs_model->select_row_array('schedule_timings',$where);

    if(count($already)>0){
      $data['form_type'] = 'Edit';
      $data['schedule_dates'] = json_decode($already['schedule_date'],true);
      $data['available_timings'] = json_decode($already['available_timing'],true);

      if($_POST['submit']){
       // print_r($_POST);exit; 
        if($this->input->post('schedule_date') == "" || $this->input->post('schedule_time')==""){
          $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
          redirect('jobs/schedule_timing');
        }
          $ins['user_id'] = $user_id;
          $ins['job_id'] = $job_id;
          $schedule_date_new = array();

          //print_r($this->input->post('schedule_date'));echo "<hr>";
          foreach ($this->input->post('schedule_date') as $key => $dates) {
            $new_date = str_replace('/', '-', $dates);
            $new_date = date('d-m-Y',strtotime($new_date));

            $schedule_date_new[] = date('Y-m-d',strtotime($new_date));
          }

       //   print_r($schedule_date_new);exit; 

          $ins['schedule_date'] = json_encode($schedule_date_new,true);
          $ins['available_timing'] = json_encode($this->input->post('schedule_time'),true);
          $update = $this->Jobs_model->update('schedule_timings',$ins,array('id'=>$already['id']));
          
          
          if($update){
              $this->session->set_flashdata('tokbox_success', lang('scheduletime_saved_successfully'));
              redirect('jobs/schedule_timing');
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
              redirect('jobs/schedule_timing');
          }
    }

    }else{
      $data['form_type'] = 'Add';
      if($_POST['submit']){
       // print_r($_POST);exit; 

        if($this->input->post('schedule_date') == "" || $this->input->post('schedule_time')==""){
          $this->session->set_flashdata('tokbox_error', lang('some_feilds_missing'));
          redirect('jobs/schedule_timing');
        }
          $ins['user_id'] = $user_id;
          $ins['job_id'] = $job_id;
          $schedule_date_new = array();
          foreach ($this->input->post('schedule_date') as $key => $dates) {
            $schedule_date_new[] = date('Y-m-d',strtotime(str_replace('/', '-', $dates)));
          }

          $ins['schedule_date'] = json_encode($schedule_date_new,true);
          $ins['available_timing'] = json_encode($this->input->post('schedule_time'),true);
          $insert = $this->Jobs_model->insert('schedule_timings',$ins);
          if($insert){
              $this->session->set_flashdata('tokbox_success', lang('scheduletime_saved_successfully'));
              redirect('jobs/schedule_timing');
          }else{
            $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
              redirect('jobs/schedule_timing');
          }
    }
  }
    $data['time_list'] =$this->Jobs_model->time_diffence();
      
    //   if($this->input->post('submit')){

    //   //$add['experience_level'] =  $this->input->post('experience');
    //   //$add['status'] = $this->input->post('status');
    //   if($this->input->post('id')){
    //    //$result =   $this->Jobs_model->update('experience_level',$add,array('id'=>$this->input->post('id')));
    //   }else{
    //     //$result =  $this->Jobs_model->insert('experience_level',$add);
    //   }
    //   if($result){
    //     $this->session->set_flashdata('tokbox_success', lang('experience_level_updated'));
    //     redirect('jobs/schedule_timing');
    //   }else{
    //     $this->session->set_flashdata('tokbox_error', lang('something_went_to_wrong'));
    //     redirect('jobs/schedule_timing');
    //   }
      
    // }
      
      $this->load->view('modal/schedule_timing_modal',$data);
    }


    public function add_header()
    {
         // print_r($_FILES);
         // print_r($_POST); exit;
         //  exit();
	
		 
		 
        if ($this->input->post()) {


                if(file_exists($_FILES['image']['tmp_name']) || is_uploaded_file($_FILES['image']['tmp_name'])) {

                            $config['upload_path'] = './assets/uploads/';
                            $config['allowed_types'] = 'gif|jpg|png|jpeg';
                            $config['overwrite'] = true;
                            // print_r($config['upload_path']); exit;
                            $this->load->library('upload', $config);

                            if ( ! $this->upload->do_upload('image')){
                                        echo $this->upload->display_errors(); exit;
                            }else{
                                $data = $this->upload->data();
                                $_POST['image'] = $data['file_name'];
                               
                            }
                }else {

                    $_POST['image'] = $_POST['avatar'];
                }
                 unset($_POST['avatar']);

                $data = array(
                        
                        'description'    => $_POST['description'],
                        'image'    => $_POST['image'],
                    );
                if(isset($_POST['id']) && !empty($_POST['id'])){
                  // print_r($data); exit;
                  $this->db->where('id',$_POST['id']);
                  $header_id = $this->db->update('all_job_header',$data);      
                }else{
                  $this->db->insert('all_job_header',$data);
                  $header_id = $this->db->insert_id();  
                }
                
                // print_r($this->db->last_query()); exit();
                $args = array(
                            'user' => User::get_id(),
                            'module' => 'jobs/manage',
                            'module_field_id' => $header_id,
                            'activity' => lang('all_job_header_updated'),
                            'icon' => 'fa-user',
                            'value1' => $this->input->post('description', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('all_job_header_updated_successfully'));
                redirect('jobs/manage');

                

                 
                        
        } 
    }

    public function job_portals()
    {
		
		
		
		
		if(!App::is_access('menu_job_portals'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}		

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('recruiting_process').' - '.config_item('company_name'));
        $data['page'] = lang('recruiting');
        $data['sub_page'] = lang('job_portals');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
       
       // echo $this->db->last_query();exit; 


        $this->template
                ->set_layout('users')
                ->build('job_portals', isset($data) ? $data : null);
    }

    function offerlistload()
    {
        return Offer::to_where(array('user_id'=>'1'));
    }
    function _jobtypename()
    {
        return Offer::job_where(array('user_id'=>'1'));
    }


    function time_elapsed_string($datetime, $full = false) {
        $current =    date("Y-m-d H:i:s");
    $now = new DateTime($current);
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function apply($jid=false,$jtype=false)
    {
        if($jid==false || $jtype==false)
        {
            header("Location: ".base_url('jobs'));
        }
         $this->load->module('layouts');
            $this->load->library('Template');

            $data['jobs'] = $this->offerlistload();
            $data['offer_jobtype'] = $this->_jobtypename();


 
       
        $this->template
                ->set_layout('login')
                ->build('jobview',isset($data)?$data:NULL);
    }




}
/* End of file contacts.php */
