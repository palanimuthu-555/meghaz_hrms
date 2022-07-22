<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Performance extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App','Client','Performance360'));
        
        // if (!User::is_admin()) {
        //     $this->session->set_flashdata('message', lang('access_denied'));
        //     redirect('');
        // }
        // App::module_access('menu_policies');
		
		
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }
    
    function index()
    {
		
		
		
		
		
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();
                $data['datatables'] = TRUE;
              
                $performance_details = $this->db->get_where('performance',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                 $okr_details = $this->db->get_where('okrdetails',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                  $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('okrdetails')->result_array();

                 $is_manager = $this->db->get_where('users',array('teamlead_id '=>$this->session->userdata('user_id')))->row_array();
                  
                // if($performance_details == '' &&  $data['role'] == '3' && $user == Array()) {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // else
                //   if($data['role'] == '3' && $is_manager != array())
                // {
                //      $data['datatables'] = TRUE;
                //     $this->template
                //      ->set_layout('users')
                //      ->build('performance_lead',isset($data) ? $data : NULL);
                // }
                // elseif($data['role'] == '1') {
                // $data['datatables'] = TRUE;
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance_manager',isset($data) ? $data : NULL);
                // }
                
                // elseif($okr_details == '' &&  $data['role'] == '3')
                // {
                //     $this->template
                //      ->set_layout('users')
                //      ->build('okr-view',isset($data) ? $data : NULL);
                // }
                // elseif($okr_details != '' &&  $data['role'] == '3')
                // {
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
                // }
                
        }else{
           redirect('');    
        }
     } 
     function manager_performance()
    {
        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();
              
                $performance_details = $this->db->get_where('performance',array('user_id'=>$this->session->userdata('user_id')))->row_array();
                 $okr_details = $this->db->get_where('okrdetails',array('user_id'=>$this->session->userdata('user_id')))->row_array();

                  $user = $this->db->where('lead',$this->session->userdata('user_id'))->group_by('user_id')->order_by('id','ASC')->get('okrdetails')->result_array();

                 $is_manager = $this->db->get_where('users',array('teamlead_id '=>$this->session->userdata('user_id')))->row_array();
                  
                // if($performance_details == '' &&  $data['role'] == '3' && $user == Array()) {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // else
                
                    $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
               
                
        }else{
           redirect('');    
        }
    } 

    public function performance_dashboard(){
		
		if(!App::is_access('menu_performance_dashboard'))
		{
		$this->session->set_flashdata('tokbox_error', lang('access_denied'));
		redirect('');
		}
		
		

      if($this->tank_auth->is_logged_in()) { 
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('performance_dashboard')); 
        $data['datepicker'] = TRUE;
        $data['form']       = TRUE; 
        $data['page']       = lang('performance_dashboard');
        $data['datatables'] = TRUE;
        $user_id = $this->session->userdata('user_id'); 
        $data['user_id'] = $user_id;
        $performance_status = $this->db->get('performance_status')->row_array();
        $data['performance_status'] = $performance_status;
        $data['role']       = $this->tank_auth->get_role_id();
        if($data['role'] == '1') {
         
          if($performance_status['okr'] ==1){
            $data['okr'] = $this->db->select('IFNULL(ROUND(AVG(k.grade_value),1),0) as key_grade,IFNULL(ROUND(AVG(r.key_gradeval),1),0) as result_grade,IFNULL(ROUND(AVG(k.grade_value+r.key_gradeval),1),0) as peer_grade,o.user_id,o.id,IFNULL(ROUND(AVG(k.progress_value),1),0) as k_progress,IFNULL(ROUND(AVG(r.keyprog_value),1),0) as r_progress,IFNULL(ROUND(AVG(k.progress_value+r.keyprog_value),1),0) as peer_progress')
                ->from('okrdetails o')
                ->join('okr_key_results k','o.id = k.okrdetailsid','left')
                ->join('okr_results r','k.okrdetailsid = r.okr_id','left')
                ->group_by('o.user_id')
                ->get()->result_array();
           // print_r($data['okr']); exit;

            $completed_key = $this->db->where('okr_status !=','Pending')->from('okr_key_results')->get()->num_rows();
            $completed_result = $this->db->where('key_status !=','Pending')->from('okr_results')->get()->num_rows();
            $pending_key = $this->db->where('okr_status ','Pending')->from('okr_key_results')->get()->num_rows();
            $pending_result = $this->db->where('key_status ','Pending')->from('okr_results')->get()->num_rows();
          
            $data['completed_performance'] = $completed_key + $completed_result;
            $data['outstanding_performance'] = $pending_key + $pending_result; 
          }

           if($performance_status['competency'] ==1){
            $data['performances_360'] = $this->db->select('IFNULL(ROUND(AVG(self_rating),1),0) as self_ratings, IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(self_rating+rating),1),0) as peer_ratings,IFNULL(ROUND(AVG(progress),1),0) as avg_progress')
            ->group_by('user_id')
           ->from('performance_360')
           ->get()->result_array();

           $data['completed_performance'] = $this->db->where('status !=',0)->from('performance_360')->get()->num_rows();
           $data['outstanding_performance'] = $this->db->where('status',0)->from('performance_360')->get()->num_rows();
          }

          if($performance_status['smart_goals'] ==1){
              $data['smartgoal'] = $this->db->select('IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(progress),1),0) as avg_progress')
            ->group_by('user_id')
           ->from('smartgoal')
           ->get()->result_array();

           $data['completed_performance'] = $this->db->where('status !=',0)->from('smartgoal')->get()->num_rows();
           $data['outstanding_performance'] = $this->db->where('status',0)->from('smartgoal')->get()->num_rows();
          }
          
        } elseif($data['role'] == '3'){

          if($performance_status['okr'] ==1){

            $data['okr'] = $this->db->select('IFNULL(ROUND(AVG(k.grade_value),1),0) as key_grade,IFNULL(ROUND(AVG(r.key_gradeval),1),0) as result_grade,IFNULL(ROUND(AVG(k.grade_value+r.key_gradeval),1),0) as peer_grade,o.user_id,o.id,IFNULL(ROUND(AVG(k.progress_value),1),0) as k_progress,IFNULL(ROUND(AVG(r.keyprog_value),1),0) as r_progress,IFNULL(ROUND(AVG(k.progress_value+r.keyprog_value),1),0) as peer_progress')
                ->from('okrdetails o')
                ->join('okr_key_results k','o.id = k.okrdetailsid')
                ->join('okr_results r','k.okrdetailsid = r.okr_id')
                ->where('o.lead',$user_id)
                ->group_by('o.user_id')
                ->order_by('o.id','ASC')
                ->get()->result_array();
             // echo $this->db->last_query(); exit;
            $completed_key = $this->db->where('k.okr_status !=','Pending')->where('o.lead',$user_id)->from('okrdetails o')->join('okr_key_results k','o.id = k.okrdetailsid','right')->get()->num_rows();

            $completed_result = $this->db->where('r.key_status !=','Pending')->where('o.lead',$user_id)->from('okrdetails o')->join('okr_results r','o.id = r.okr_id','right')->get()->num_rows();
            $pending_key = $this->db->where('k.okr_status ','Pending')->where('o.lead',$user_id)->from('okrdetails o')->join('okr_key_results k','o.id = k.okrdetailsid','right')->get()->num_rows();
            $pending_result = $this->db->where('r.key_status ','Pending')->where('o.lead',$user_id)->from('okrdetails o')->join('okr_results r','o.id = r.okr_id','right')->get()->num_rows();
            // echo $completed_key; exit;
            $data['completed_performance'] = $completed_key + $completed_result;

            $data['outstanding_performance'] = $pending_key + $pending_result; 

          }
          if($performance_status['competency'] ==1){
            $data['performances_360'] = $this->db->select('IFNULL(ROUND(AVG(self_rating),1),0) as self_ratings, IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(self_rating+rating),1),0) as peer_ratings,IFNULL(ROUND(AVG(progress),1),0) as avg_progress')->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('performance_360')->result_array(); 
            $data['completed_performance'] = $this->db->where('status !=',0)->where('teamlead_id',$user_id)->from('performance_360')->get()->num_rows();
            $data['outstanding_performance'] = $this->db->where('status',0)->where('teamlead_id',$user_id)->from('performance_360')->get()->num_rows();
          }
           if($performance_status['smart_goals'] ==1){
             $data['smartgoal'] = $this->db->select('IFNULL(ROUND(AVG(rating),1),0) as your_ratings,user_id,IFNULL(ROUND(AVG(progress),1),0) as avg_progress')->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('smartgoal')->result_array(); 
            $data['completed_performance'] = $this->db->where('status !=',0)->where('teamlead_id',$user_id)->from('smartgoal')->get()->num_rows();
            $data['outstanding_performance'] = $this->db->where('status ',0)->where('teamlead_id',$user_id)->from('smartgoal')->get()->num_rows();
        }
      }
        // echo "<pre>";print_r($data['performances_360']); exit;
       
                // if($data['role'] == '1') {
                //     $data['performances_360'] = $this->db->select()
                //             ->group_by('user_id')
                //             ->from('performance_360')
                //             ->get()->result_array();                     
                //     $this->template
                //          ->set_layout('users')
                //          ->build('list',isset($data) ? $data : NULL);
                // } elseif($performances_360 != '' &&  $data['role'] == '3'){

                //     $data['performances_360'] = Performance360::get_360_performance_manager($user_id);
                //      $this->template
                //      ->set_layout('users')
                //      ->build('list',isset($data) ? $data : NULL);
                //     // $data['competencies'] = Performance360::get_competencies_manager($user_id);
                // }
        $this->template
                         ->set_layout('users')
                         ->build('performance_dashboard',isset($data) ? $data : NULL);
      }else{
         redirect('');    
      }
    }


    function show_okrdetails()
    { 
         $okr_id = $this->uri->segment(3);

        if($this->tank_auth->is_logged_in()) { 
                $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('manager_review_okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('performance_dashboard');
                $data['role']       = $this->tank_auth->get_role_id();
                $data['okr_id']     = $okr_id;
              
              
                $this->template
                     ->set_layout('users')
                     ->build('okr-manager',isset($data) ? $data : NULL);
               
               
        }else{
           redirect('');    
        }
     } 



     function add_okr()
     {
       
        $data = array(
        'user_id' => $this->input->post('user_id'),
        'okr_description' => $this->input->post('okr_description')

        );
        
        
        
        $this->db->insert('performance',$data);
        $user_id = $this->db->insert_id();
        $data['user_details'] = $this->db->get_where('users',array('id'=>$user_id))->row_array();
       
        
        $this->load->module('layouts');
                $this->load->library('template');
                $this->template->title(lang('okr')); 
                $data['datepicker'] = TRUE;
                $data['form']       = TRUE; 
                $data['page']       = lang('okr');
                $data['role']       = $this->tank_auth->get_role_id();

        $this->template
                     ->set_layout('users')
                     ->build('okr-view',isset($data) ? $data : NULL);
     }


     function add_goals()
     {      
         // echo "<pre>";print_r($_POST); exit;
      $okr_id = $this->session->userdata('user_id');
      $okrdetails = $this->db->get_where('okrdetails',array('user_id'=>$okr_id))->row_array();
       // echo print_r($okrdetails); exit;
      if(!empty($okrdetails)){
          $okrdetailsid= $okrdetails['id'];
          if(!empty($_POST['objective_id'])){
            //update objective & result
             $objective_result = array();
              $objective = $this->input->post('objective');
              $obj_status = $this->input->post('okr_status');
              $progress_value = $this->input->post('progress_value');
              $key_results = $this->input->post('key_result');
              $key_status = $this->input->post('key_status');
              $keyres_value = $this->input->post('keyres_value');
              $okr_result_id = $this->input->post('okr_result_id');
              $okr_result_count = $this->input->post('okr_result_count');
              
                  $objectiveData['okrdetailsid']  = $okrdetailsid;
                  $objectiveData['objective']  = $objective;
                  $objectiveData['okr_status'] = $obj_status;
                  $objectiveData['progress_value']  = $progress_value;
                  $this->db->where('id',$_POST['objective_id']);
                  $this->db->update('okr_key_results',$objectiveData);
                  // $okr_objective_id = $this->db->insert_id();
                  foreach($key_results as $obj_key => $result_data){
                      $resultData['okr_id']  = $okrdetailsid;
                      $resultData['objective_id'] = $_POST['objective_id'];
                      $resultData['key_result']  = $result_data;
                      $resultData['key_status']  = $key_status[$obj_key];
                      $resultData['keyprog_value']  = $keyres_value[$obj_key];
                      $this->db->where('id',$okr_result_id[$obj_key]);
                      $this->db->update('okr_results',$resultData);
                      if($obj_key >= $okr_result_count){                      
                        // foreach ($result_data as $key => $value) {
                          $edit_resultData['okr_id']  = $okrdetailsid;
                          $edit_resultData['objective_id'] = $_POST['objective_id'];
                          $edit_resultData['key_result']  = $result_data;
                          $edit_resultData['key_status']  = $key_status[$obj_key];
                          $edit_resultData['keyprog_value']  = $keyres_value[$obj_key];
                          // echo"<pre>"; print_r($edit_resultData); exit;
                          $this->db->insert('okr_results',$edit_resultData);
                          $okr_objective_id = $this->db->insert_id();
                        // }
                      }
                      
                  }

              
              $args = array(
                          'user' => $okrdetails['lead'],
                          'module' => 'performance/show_okrdetails',
                          'module_field_id' => $okrdetailsid,
                          'activity' => user::displayName($this->session->userdata('user_id')).' Updated OKR Performance',
                          'icon' => 'fa-user',
                          'value1' => $okrdetailsid,
                      );
              App::Log($args);
               $this->session->set_flashdata('tokbox_success', 'Performance Updated Successfully');
        // redirect('performance');
        redirect($_SERVER['HTTP_REFERER']);

          }else {
            // create another objective
              $objective_result = array();
              $objective = $this->input->post('objective');
              $obj_status = $this->input->post('okr_status');
              $progress_value = $this->input->post('progress_value');
              $key_results = $this->input->post('key_result');
              $key_status = $this->input->post('key_status');
              $keyres_value = $this->input->post('keyres_value');
             
              foreach($objective as $key => $obj){
                  $objectiveData['okrdetailsid']  = $okrdetailsid;
                  $objectiveData['objective']  = $obj;
                  $objectiveData['okr_status'] = $obj_status[$key];
                  $objectiveData['progress_value']  = $progress_value[$key];
                  $this->db->insert('okr_key_results',$objectiveData);
                  $okr_objective_id = $this->db->insert_id();
                  foreach($key_results as $obj_key => $result_data){
                      $resultData['okr_id']  = $okrdetailsid;
                      $resultData['objective_id'] = $okr_objective_id;
                      $resultData['key_result']  = $result_data;
                      $resultData['key_status']  = $key_status[$obj_key];
                      $resultData['keyprog_value']  = $keyres_value[$obj_key];
                      $this->db->insert('okr_results',$resultData);
                  }

              }
              $args = array(
                          'user' => $okrdetails['lead'],
                          'module' => 'performance/show_okrdetails',
                          'module_field_id' => $okrdetailsid,
                          'activity' => user::displayName($this->session->userdata('user_id')).' Added OKR Performance',
                          'icon' => 'fa-user',
                          'value1' => $okrdetailsid,
                      );
              App::Log($args);
              $this->session->set_flashdata('tokbox_success', 'Performance Added Successfully');
              // redirect('performance');
              redirect($_SERVER['HTTP_REFERER']);
          }

      }else{

        // Create new objective and result
          // echo "<pre>";print_r($_POST); exit;
        $data = array(
          'user_id' => $this->input->post('user_id'),
          'emp_name' => $this->input->post('fullname'),
          'position' => $this->input->post('position'),
          'lead' => $this->input->post('lead'),
          'goal_year' => $this->input->post('goal_year'),
          'goal_duration' => $this->input->post('goal_duration'),
        );


        $this->db->insert('okrdetails',$data);
        $okrdetailsid=$this->db->insert_id();

        $objective_result = array();
        $objective = $this->input->post('objective');
        $obj_status = $this->input->post('okr_status');
        $progress_value = $this->input->post('progress_value');
        $key_results = $this->input->post('key_result');
        $key_status = $this->input->post('key_status');
        $keyres_value = $this->input->post('keyres_value');
       
        foreach($objective as $key => $obj){
            $objectiveData['okrdetailsid']  = $okrdetailsid;
            $objectiveData['objective']  = $obj;
            $objectiveData['okr_status'] = $obj_status[$key];
            $objectiveData['progress_value']  = $progress_value[$key];
            $this->db->insert('okr_key_results',$objectiveData);
            $okr_objective_id = $this->db->insert_id();
            foreach($key_results as $obj_key => $result_data){
                $resultData['okr_id']  = $okrdetailsid;
                $resultData['objective_id'] = $okr_objective_id;
                $resultData['key_result']  = $result_data;
                $resultData['key_status']  = $key_status[$obj_key];
                $resultData['keyprog_value']  = $keyres_value[$obj_key];
                $this->db->insert('okr_results',$resultData);
            }

        }
        $args = array(
                    'user' => $this->input->post('lead'),
                    'module' => 'performance/show_okrdetails',
                    'module_field_id' => $okrdetailsid,
                    'activity' => user::displayName($this->session->userdata('user_id')).' Added OKR Performance',
                    'icon' => 'fa-user',
                    'value1' => $okrdetailsid,
                );
        App::Log($args);
       
        $this->session->set_flashdata('tokbox_success', 'Performance Added Successfully');
        // redirect('performance');
        redirect($_SERVER['HTTP_REFERER']);
      } 
     }

     public function edit_okrdetails()
     {

        $this->db->where('okrdetailsid',$this->input->post('id'));
        $this->db->delete('okr_key_results');

        $objective=array_values($this->input->post('objective'));
        $okr_status=array_values($this->input->post('okr_status'));
        $progress_value=array_values($this->input->post('progress_value'));
        $grade_value=array_values($this->input->post('grade_value'));
        $grade_value_man=array_values($this->input->post('grade_value_man'));
        $feedback=array_values($this->input->post('obj_feedback'));
        $key_result=array_values($this->input->post('key_result'));
        $key_status=array_values($this->input->post('key_status'));
        $keyres_value=array_values($this->input->post('keyres_value'));
        $key_gradeval=array_values($this->input->post('key_gradeval'));
        $key_gradeval_man=array_values($this->input->post('key_gradeval_man'));
        $key_feedback=array_values($this->input->post('key_feedback'));

        
        for($i = 0; $i< count($objective); $i++)
        {

        $data_obj = array(
        'okrdetailsid'=>$this->input->post('id'),  
        'objective' => $objective[$i],
        'okr_status' => $okr_status[$i],
        'progress_value' => $progress_value[$i],
        'grade_value' => $grade_value[$i],
        'grade_value_man' => $grade_value_man[$i],
        'feedback'=>!empty($feedback[$i])?$feedback[$i]:'',
        'key_result' => json_encode($key_result[$i]),
        'key_status' => json_encode($key_status[$i]),
        'keyprog_value' => json_encode($keyres_value[$i]),
        'key_gradeval' => json_encode($key_gradeval[$i]),
        'key_gradeval_man' => json_encode($key_gradeval_man[$i]),
        'key_feedback' => json_encode(!empty($key_feedback[$i])?$key_feedback[$i]:''));

        $this->db->insert('okr_key_results',$data_obj);

        // $this->db->where('id',$this->input->post('id'));
        // $this->db->update('okr_key_results',$data_obj);
        }





       
        // for($i = 0; $i< count($this->input->post('objective')); $i++)
        // {

        // $data_obj = array(
        // 'objective' => $this->input->post('objective')[$i],
        // 'okr_status' => $this->input->post('okr_status')[$i],
        // 'progress_value' => $this->input->post('progress_value')[$i],
        // 'grade_value' => $this->input->post('grade_value')[$i],
        // 'key_result' => $this->input->post('key_result')[$i],
        // 'key_status' => $this->input->post('key_status')[$i],
        // 'keyprog_value' => $this->input->post('keyres_value')[$i],
        // 'key_gradeval' => $this->input->post('key_gradeval')[$i],
        // 'obj_feedback' => $this->input->post('obj_feedback')[$i],
        // 'key_feedback' => $this->input->post('key_feedback')[$i]


        // );

        // $objectives[] = $data_obj;
        //      }

        // $data = array(
        // 'id' => $this->input->post('id'),
        // 'emp_name' => $this->input->post('emp_name'),
        // 'position' => $this->input->post('position'),
        // 'lead' => $this->input->post('lead'),
        // 'goal_year' => $this->input->post('goal_year'),
        // 'goal_duration' => $this->input->post('goal_duration'),
        // 'objective' => json_encode($objectives)
        //  );

       

        //     $this->db->where('id',$this->input->post('id'));
        //     $this->db->update('dgt_okrdetails',$data);
        
         $this->session->set_flashdata('tokbox_success', 'Added Successfully');
         // redirect('performance');
         redirect($_SERVER['HTTP_REFERER']);
     }

      public function objective_status(){
       
        $data = array(        
            'okr_status' => $this->input->post('okr_status')
         );

        $this->db->where('id',$this->input->post('object_id'));
        $this->db->update('okr_key_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('object_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Upated the objective status', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('object_id', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('object_id'),
        //             'activity' => user::displayName($this->session->userdata('user_id')).' Upated the status', 
        //             'icon' => 'fa-user',
        //             // 'value1' => $this->input->post('okr_status', true),
        //         );
        // App::Log($args);
        echo 'yes'; exit;

    }

    public function key_status(){
       
        $data = array(        
            'key_status' => $this->input->post('key_status')
         );

        $this->db->where('id',$this->input->post('key_id'));
        $this->db->update('okr_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('key_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Upated the key result status', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('key_status', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('key_id'),
        //             'activity' => 'Status '.$this->input->post('key_status'),
        //             'icon' => 'fa-user',
        //             'value1' => $this->input->post('key_status', true),
        //         );
        // App::Log($args);
        echo 'yes'; exit;

    }

     public function okr_object_rating(){
       
        $data = array(        
            'grade_value' => $this->input->post('grade_value')
         );

        $this->db->where('id',$this->input->post('objective_id'));
        $this->db->update('okr_key_results',$data);         
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Upated the objective grading', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('grade_value', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('objective_id'),
        //             'activity' => $this->input->post('grade_value').' Grading',
        //             'icon' => 'fa-user',
        //             'value1' => $this->input->post('grade_value', true),
        //         );
        // App::Log($args);
        echo 'yes'; exit;

    }

     public function okr_result_rating(){

       
        $data = array(        
            'key_gradeval' => $this->input->post('key_gradeval')
         );

        $this->db->where('id',$this->input->post('result_id'));
        $this->db->update('okr_results',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Upated the key result Grading', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('key_gradeval', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('result_id'),
        //             'activity' => $this->input->post('key_gradeval').' Grading',
        //             'icon' => 'fa-user',
        //             'value1' => $this->input->post('key_gradeval', true),
        //         );
        // App::Log($args);
        echo 'yes'; exit;

    }

    public function objective_feedback(){

            $data = array(        
            'user_id' => $this->session->userdata('user_id'),
            'feed_back' =>  $this->input->post('feed_back'),
            'okr_objective_id ' =>  $this->input->post('objective_id')
         );
          $okr_id = $this->input->post('okr_id');
        // print_r($objective_created_by); exit;
        $this->db->insert('okr_feedback',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('objective_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Gave the objective feedback', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('objective_id'),
        //             'activity' => $this->input->post('feed_back'),
        //             'icon' => 'fa-user',
        //             'value1' => $this->input->post('feed_back', true),
        //         );
        // App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
                redirect('performance/show_okrdetails/'.$okr_id);

    }

     public function result_feedback(){

            $data = array(        
            'user_id' => $this->session->userdata('user_id'),
            'feed_back' =>  $this->input->post('feed_back'),
            'okr_result_id ' =>  $this->input->post('result_id')
         );
          $okr_id = $this->input->post('okr_id');
        // print_r($objective_created_by); exit;
        $this->db->insert('okr_result_feedback',$data);   
        $args = array(
                    'user' => $this->input->post('user_id'),
                    'module' => 'performance',
                    'module_field_id' => $this->input->post('result_id'),
                    'activity' => user::displayName($this->session->userdata('user_id')).' Gave the key result feedback', 
                    'icon' => 'fa-user',
                    // 'value1' => $this->input->post('feed_back', true),
                );
        App::Log($args);
        // $args = array(
        //             'user' => User::get_id(),
        //             'module' => 'performance',
        //             'module_field_id' => $this->input->post('result_id'),
        //             'activity' => $this->input->post('feed_back'),
        //             'icon' => 'fa-user',
        //             'value1' => $this->input->post('feed_back', true),
        //         );
        // App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
                redirect('performance/show_okrdetails/'.$okr_id);

    }

    
}
