<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
        $this->load->helper('security');
        if(!App::is_access('menu_faq'))
        {
            $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
    }

    public function index()
    {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('faq'));
        $data['form'] = TRUE;
        $data['editor'] = TRUE;
        $data['page'] = lang('faq');
        $status = $this->uri->segment(3);
        if($status == 1){
            
            $data['faq_details'] = $this->db->select('*')->from('faq')->where(array('answer!='=> '','status!=' =>'2'))->order_by('id', 'DESC')->get()->result();
        }
        else if($status == 2){
            
            $data['faq_details'] = $this->db->select('*')->from('faq')->where(array('answer'=>'','status!=' =>'2'))->order_by('id', 'DESC')->get()->result();
        }else{
            $data['faq_details'] = $this->db->select('*')->from('faq')->where(array('status!=' =>'2'))->order_by('id', 'DESC')->get()->result();            
        }
        $data['all_question'] = $this->db->select('count(id) as all_qustn')->get_where('faq', array('status!='=>'2'))->row();
       
        $data['answered_qustn'] = $this->db->select('count(id) as answered')->get_where('faq', array('answer!='=> '', 'status!='=>'2'))->row();
        $data['not_answered_qustn'] = $this->db->select('count(id) as not_answered')->get_where('faq', array('answer'=>'', 'status!='=>'2'))->row();
        // $data['user_permission'] = $this->db->get_where('permission_settings', array('user_id'=>$this->session->userdata('user_id')))->result();
        // $data['policy_permission'] = $this->db->select('PD.*, PP.*')
        //         ->from('policy_details PD')
        //         ->join('policy_permission PP', 'PP.policy_id=PD.id')
        //         ->where("FIND_IN_SET(".$this->session->userdata('user_id').", PD.assigned_user) !=", 0)
        //         ->get()
        //         ->result();

        $this->template
            ->set_layout('users')
            ->build('faq',isset($data) ? $data : NULL);
    }

   

   public function addFaq()
   {
        $post = $this->input->post();
        //echo 'post<pre>'; print_r($post); exit;
        if($post!='') {
            $post_data_1 = array(
                'question'  =>$this->input->post('question'),
                'answer' => ($this->input->post('answer'))?$this->input->post('answer'):''

            );

            if($this->input->post('faq_id') == '') {
                $post_data_2 = array('created_datetime' => date('Y-m-d H:i:s'), 'created_user' => $this->session->userdata('user_id'));
                $post_data = array_merge($post_data_1, $post_data_2);
                $this->db->insert('faq',$post_data);
                $inserted_id = $this->db->insert_id();
                 $args = array(
                                'user' => $this->session->userdata('user_id'),
                                'module' => 'Faq',
                                'module_field_id' => $inserted_id,
                                'activity' => $this->input->post('question').' FAQ Created',
                                'icon' => 'fa-user',
                                'value1' => User::displayName($this->session->userdata('user_id')),
                                'value2' => $this->input->post('question')
                            );
                            App::Log($args);
            } else {
                $post_data_2 = array('modified_datetime' => date('Y-m-d H:i:s'), 'modified_user' => $this->session->userdata('user_id'));
                $post_data = array_merge($post_data_1, $post_data_2);
                App::update('faq',array('id'=>$this->input->post('faq_id')),$post_data);
                 $args = array(
                                'user' => $this->session->userdata('user_id'),
                                'module' => 'Faq',
                                'module_field_id' => $this->input->post('faq_id'),
                                'activity' => 'Updated Faq details',
                                'icon' => 'fa-user',
                                'value1' => User::displayName($this->session->userdata('user_id')),
                                'value2' => $this->input->post('question')
                            );
                            App::Log($args);
            }
            if($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('tokbox_success', 'FAQ Details Added Successfully');
                redirect($_SERVER['HTTP_REFERER']);
            } 
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect($_SERVER['HTTP_REFERER']);
        }
   }

  
    public function edit_faq($id) {
        //echo 'id----'.$id; exit;
        $data['form'] = TRUE;
        $data['editor'] = TRUE;
        $data['faq'] = $this->db->get_where('faq',array('id'=>$id, 'status!='=>''))->row();
        $data['id'] = $id;

        echo json_encode($data); exit;
        //$this->load->view('edit_faq',$data);
    }

    public function delete_faq($id) {
        //echo $id; exit;
        if($this->input->post()) {
            $det['status']= '2'; 
            App::update('faq',array('id'=>$this->input->post('faq_id')),$det);

             $args = array(
                                'user' => $this->session->userdata('user_id'),
                                'module' => 'Faq',
                                'module_field_id' => $this->input->post('faq_id'),
                                'activity' => 'FAQ Deleted Successfully',
                                'icon' => 'fa-user',
                                'value1' => User::displayName($this->session->userdata('user_id')),
                                'value2' => $this->input->post('question')
                            );
                            App::Log($args);
            $this->session->set_flashdata('tokbox_success', 'FAQ Deleted Successfully');
            redirect('Faq');
        }else{
            $data['faq_id'] = $id;
            $this->load->view('delete_faq',$data);
        } 
    }

    public function search_details() {

        $this->db->where('status!=', '2');

        if($this->input->post('search_value') != '') {
            $this->db->like('question', $this->input->post('search_value'));
            // $this->db->or_like('answer', $this->input->post('search_value'));
        }

        $data['faq'] = $this->db->select('*')->get('faq')->result();
        //$data['answered_qustn'] = $this->db->select('count(id) as answered')->get_where('faq', array('answer!='=> '', 'status!='=>'2'))->row();
        //echo 'post<pre>'; print_r($data['answered_qustn']); exit; 
        //if($post['search_value'] != '') {
            echo json_encode($data); exit;
        //} 
    } 
}


/*
ALTER TABLE `dgt_faq` ADD `status` ENUM('0','1','2') NOT NULL COMMENT '\'0\'-\'Inactive\',\'1\'-\'Active\',\'2\'-\'Delete\'' AFTER `answer`;
ALTER TABLE `dgt_faq` CHANGE `answer` `answer` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `dgt_faq` ADD `answer` TEXT NOT NULL DEFAULT '' AFTER `question`;

https://bbbootstrap.com/snippets/multi-step-form-wizard-30467045
https://www.codeply.com/go/HB3TlHl1cs/bootstrap-bootstrap-tabs-next-%26-previous-buttons

db: https://sg3plcpnl0040.prod.sin3.secureserver.net:2083/cpsess3544457166/frontend/paper_lantern/index.html?login=1&post_login=5384990342384


https://sg3plcpnl0040.prod.sin3.secureserver.net:2083/cpsess3544457166/3rdparty/phpMyAdmin/sql.php?db=tokyo_hrms&table=dgt_ad_knowledge_topic&pos=0*/