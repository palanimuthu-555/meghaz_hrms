<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Smartgoal extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->library(array('form_validation'));
        $this->load->model(array('Client', 'App', 'Invoice', 'Expense', 'Project', 'Payment', 'Estimate','Smart_goals'));
        $all_routes = $this->session->userdata('all_routes');
        // echo '<pre>'; print_r($all_routes); exit;
        // foreach($all_routes as $key => $route){
        //     if($route == 'Smartgoal'){
        //         $routname = Smartgoal;
        //     } 
            
        // }
        // if (!User::is_admin())
        
        // if(empty($routname)){
        //     // $this->session->set_flashdata('message', lang('access_denied'));
        //     $this->session->set_flashdata('tokbox_error', lang('access_denied'));
        //     redirect('');
        // }
        $this->load->helper(array('inflector'));
        $this->applib->set_locale();
    }

    public function index()
    {
        // echo "dsd"; exit;
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('Smartgoal'));
        $data['page'] = lang('smartgoal');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        $user_id = $this->session->userdata('user_id');
        $data['user_id'] = $user_id;
        
        $data['role']       = $this->tank_auth->get_role_id();
        $smartgoal = Smart_goals::get_smartgoal_manager($user_id);
        $is_manager = $this->db->get_where('users',array('teamlead_id '=>$this->session->userdata('user_id')))->row_array();
         // print_r($smartgoal); exit;
        $data['smartgoal'] = Smart_goals::get_smartgoal($user_id);
          $this->template
                 ->set_layout('users')
                 ->build('smartgoal',isset($data) ? $data : NULL);
        // if($data['role'] == '3' && $smartgoal == Array()) {
        //     $data['smartgoal'] = Smart_goals::get_smartgoal($user_id);
        //     // print_r($data['smartgoal']); exit;
        //     $this->template
        //          ->set_layout('users')
        //          ->build('smartgoal',isset($data) ? $data : NULL);
        // } 
        // elseif($data['role'] == '1') {
        //     $data['smartgoal'] = $this->db->select()
        //             ->group_by('user_id')
        //             ->from('smartgoal')
        //             ->get()->result_array();                     
        //     $this->template
        //          ->set_layout('users')
        //          ->build('list',isset($data) ? $data : NULL);
        // } elseif($is_manager != Array() &&  $data['role'] == '3'){

        //     $data['smartgoal'] = Smart_goals::get_smartgoal_manager($user_id);
        //      $this->template
        //      ->set_layout('users')
        //      ->build('list',isset($data) ? $data : NULL);
        //     // $data['competencies'] = Performance360::get_competencies_manager($user_id);
        // }
                // {
                //     $this->template
                //      ->set_layout('users')
                //      ->build('okr-view',isset($data) ? $data : NULL);
                // }
               
                //  if($performance_details == '' &&  $data['role'] == '3') {
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance',isset($data) ? $data : NULL);
                // }
                // elseif($data['role'] == '1') {
                // $data['datatables'] = TRUE;
                // $this->template
                //      ->set_layout('users')
                //      ->build('performance_manager',isset($data) ? $data : NULL);
                // }
                
                // elseif($performance_details != '' &&  $data['role'] == '3')
                // {
                //     $this->template
                //      ->set_layout('users')
                //      ->build('okr-view',isset($data) ? $data : NULL);
                // }
           // echo "<pre>";print_r($data['competencies']); exit;

    }

     function manager_performance()
    {
        if($this->tank_auth->is_logged_in()) { 
            $this->load->module('layouts');
            $this->load->library('template');
            $this->template->title(lang('smartgoal'));
            $data['page'] = lang('smartgoal');
            $data['datatables'] = true;
            $data['form'] = true;
            $data['currencies'] = App::currencies();
            $data['languages'] = App::languages();
            $data['countries'] = App::countries();
            $data['role']       = $this->tank_auth->get_role_id();              
            $user_id = $this->session->userdata('user_id');
            $data['user_id'] = $user_id;    
            $data['smartgoal'] = Smart_goals::get_smartgoal($user_id);
            $this->template
             ->set_layout('users')
             ->build('smartgoal',isset($data) ? $data : NULL);
               
                
        }else{
           redirect('');    
        }
     } 
  

    public function create_360()
    {
         // echo "<pre>";print_r($_POST); exit;


        if ($this->input->post()) {
             // echo "<pre>";print_r($_POST); exit;

                    if($this->input->post('id') !=''){
                    $id= $this->input->post('id');
                    $_POST['user_id'] = $this->session->userdata('user_id');
                    $_POST['create_date'] = date('Y-m-d',strtotime($_POST['create_date']));
                    $_POST['completed_date'] = date('Y-m-d',strtotime($_POST['completed_date']));
 


                     $data = array(
                    'user_id' => $_POST['user_id'],
                    'goals'   => $_POST['goals'],
                    'goal_duration'   => $_POST['goal_duration'],
                    'action'   => serialize($_POST['action']),
                    'result'   => serialize($_POST['result']),
                    'status'   => $_POST['status'],
                    'create_date'   =>  $_POST['create_date'] ,
                    'completed_date'   => $_POST['completed_date'],
                    'progress'   => $_POST['progress'],
                    'teamlead_id'   => $_POST['teamlead_id']
                    );

                $performance_360_id = Smart_goals::update_golas($id,$data);

                $args = array(
                            'user' => $_POST['teamlead_id'],
                            'module' => 'smartgoal/show_smartgoal',
                            'module_field_id' => $performance_360_id,
                            'activity' => user::displayName($_POST['user_id']).' Updated SMART Goal',
                            'icon' => 'fa-user',
                            'value1' => $_POST['user_id'],
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', 'SMART Goal Updated successfully');


                }else {
                    $_POST['action'] = serialize($_POST['action']);
                    $_POST['result'] = serialize($_POST['result']);
                 
                    $_POST['user_id'] = $this->session->userdata('user_id');
                    $_POST['create_date'] = date('Y-m-d',strtotime($_POST['create_date']));
                    $_POST['completed_date'] = date('Y-m-d',strtotime($_POST['completed_date']));
                  // $data = array(
                  //   'user_id' => $_POST['user_id'],
                  //   'goals'   => $_POST['goals'],
                  //   'goal_duration'   => $_POST['goal_duration'],
                  //   'action'   => serialize($_POST['action']),
                  //   'status'   => $_POST['status'],
                  //   'self_rating'   => $_POST['self_rating'],
                  //   'rating'   => $_POST['rating'],
                  //   'progress'   => $_POST['progress'],
                  //   'feedback'   => $_POST['feedback'],
                  //   'teamlead_id'   => $_POST['teamlead_id']
                  //   );

                  // print_r($data);exit;
                $performance_360_id = Smart_goals::save_golas($this->input->post(null,true));
                // print_r($this->db->last_query());exit;
                $args = array(
                            'user' => $_POST['teamlead_id'],
                            'module' => 'smartgoal/show_smartgoal',
                            'module_field_id' => $performance_360_id,
                            'activity' => user::displayName($_POST['user_id']).' Created SMART Goal',
                            'icon' => 'fa-user',
                            'value1' => $_POST['user_id'],
                        );
                App::Log($args);    
                $this->session->set_flashdata('tokbox_success', 'SMART Goal Created successfully');
                }
               
                // redirect('smartgoal');
                redirect($_SERVER['HTTP_REFERER']);
            
        } else {

        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('smartgoal'));
        $data['page'] = lang('smartgoal');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();

        // $data['companies'] = Client::get_all_clients();

        $data['countries'] = App::countries();
        $this->template
                ->set_layout('users')
                ->build('smartgoal', isset($data) ? $data : null);
        }
          
    }

    public function smartgoal_rating(){

        $id = $this->input->post('id');
        // $_POST['user_id'] = $this->session->userdata('user_id');
        $_POST['rating'] = $this->input->post('rating');

                   // echo "<pre>";print_r($_POST); exit;

        $smartgoal = Smart_goals::update_golas($id,$this->input->post(null, true));
        $user_id= $this->db->get_where('smartgoal',array('id'=>$id))->row()->user_id;
         // echo print_r($this->db->last_query()); exit;
        $args = array(
                    'user' => $user_id,
                    'module' => 'smartgoal',
                    'module_field_id' => $smartgoal,
                    'activity' => user::displayName($this->session->userdata('user_id')).' Gave the Rating',
                    'icon' => 'fa-user',
                    // 'value1' => $user_id,
                );
        App::Log($args);
        echo 'yes'; exit;

    }

    

    public function delete_goal($id)
    {
        $this->db->where('id',$id)->delete('dgt_smartgoal');
        $this->session->set_flashdata('tokbox_success', lang('goal_deleted_successfully'));
        // redirect('smartgoal');
        redirect($_SERVER['HTTP_REFERER']);

    }

     



    public function show_smartgoal($userid)
    {
          $user_id = $this->uri->segment(3);

         $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('smartgoal'));
        $data['page'] = lang('smartgoal');
        $data['datatables'] = true;
        $data['form'] = true;
        $data['currencies'] = App::currencies();
        $data['languages'] = App::languages();
        // $user_id = $this->session->userdata('user_id');
        $data['user_id'] = $user_id;
        $data['smartgoals'] = Smart_goals::get_smartgoal($user_id);
        $data['role']       = $this->tank_auth->get_role_id();
        $this->template
             ->set_layout('users')
             ->build('manager_view',isset($data) ? $data : NULL);
       
     } 


   
    public function smartgoal_feedback(){

            $goal_created_by = $this->input->post('user_id', true);
            unset($_POST['user_id']);
            $_POST['user_id'] = $this->session->userdata('user_id');

                    // echo "<pre>";print_r($_POST); exit;

                $smartgoal = Smart_goals::save_feedback($this->input->post(null, true));

                $args = array(
                            'user' => $goal_created_by,
                            'module' => 'smartgoal',
                            'module_field_id' => $smartgoal,
                            'activity' => user::displayName($this->session->userdata('user_id')).' Gave the feedback',
                            'icon' => 'fa-user',
                            // 'value1' => $this->input->post('feedback', true),
                        );
                App::Log($args);
                $this->session->set_flashdata('tokbox_success', lang('feedback_updated_successfully'));
                redirect('smartgoal/show_smartgoal/'.$goal_created_by);

    }
     
    public function smartgoal_status()
    {
        $goal_id = $this->uri->segment(3);
        $status = $this->uri->segment(4);
        $goal_created_by = $this->uri->segment(5);
        $data = array('status' => $status);
        $smartgoal = Smart_goals::update_golas($goal_id,$data);
        $args = array(
                    'user' => $goal_created_by,
                    'module' => 'smartgoal',
                    'module_field_id' => $smartgoal,
                    'activity' => user::displayName($this->session->userdata('user_id')).' Updated the status',
                    'icon' => 'fa-user',
                    // 'value1' => $status,
                );
        App::Log($args);
        $this->session->set_flashdata('tokbox_success', 'Smartgoal Status Updated Successfully');
        redirect('smartgoal/show_smartgoal/'.$goal_created_by);
    }

    

    public function get_approvers()
    {
        $approvers = User::team();

        

         $data=array();
            foreach($approvers as $r)
            {
                $data['value']=$r->id;
                $data['label']=ucfirst($r->username);
                $json[]=$data;
                
                
            }
        echo json_encode($json);
        exit;
    }


   


}
/* End of file contacts.php */
