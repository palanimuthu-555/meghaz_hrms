<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sidebar extends MX_Controller {
	


	function __construct()
	{
		parent::__construct();
		$this->load->model(array('Message','Project'));

	}
	public function admin_menu()
	{

		$this->load->model('chats/Chats_model');
        $where['to_id'] = $this->session->userdata('user_id');
        
        $where['read_status'] = 0;
         $where['msg_type'] = 'one';
        $single_messages  = $this->Chats_model->get_msg_count($where);
        
        $total_single_msg = array();
        foreach ($single_messages as $key => $msg) {
        	$total_single_msg[] = $msg->msg_id;
        	$total_single_user_msg[$msg->from_id][] = $msg->msg_id;
        }
       // print_r($total_single_user_msg);
        $where1['to_user_id'] = $this->session->userdata('user_id');
        $where1['read_status'] = 0;
        $group_messages  = $this->Chats_model->get_group_msg_count($where1);
        $total_group_msg = array();
        foreach ($group_messages as $key => $gmsg) {
        	$total_group_msg[] = $gmsg->id;
        	$total_group_user_msg[$gmsg->group_id][] = $gmsg->id;
        }
        $data['total_group_user_msg'] = $total_group_user_msg;
        $data['total_single_user_msg'] = $total_single_user_msg;

        $data['total_unread_msg'] = count($total_group_msg)+count($total_single_msg);
        
		$data['languages'] = App::languages();
        $this->load->view('admin_menu',isset($data) ? $data : NULL);
	}
	public function collaborator_menu()
	{
		$data['languages'] = App::languages();
		$this->load->view('collaborator_menu',isset($data) ? $data : NULL);
	}
	public function client_menu()
	{
		$data['languages'] = App::languages();
        $this->load->view('user_menu',isset($data) ? $data : NULL);
	}
	public function top_header()
	{

                        $this->db->where(array('status'=>'1'));
                        $project_timers = $this->db->get('project_timer')->result_array();

                        $this->db->where(array('status'=>'1'));
                        $task_timers = $this->db->get('tasks_timer')->result_array();

                $data['timers'] = array_merge($project_timers,$task_timers);
                $data['updates'] = $this->applib->get_updates();

                $this->load->view('top_header',isset($data) ? $data : NULL);
	}
	public function candidate_top_header()
	{
	 $this->load->view('candidate_top_header',isset($data) ? $data : NULL);
	}
	
	
	public function scripts()
	{
		$this->load->view('scripts/uni_scripts',isset($data) ? $data : NULL);
	}
	public function flash_msg()
	{
		$this->load->view('flash_msg',isset($data) ? $data : NULL);
	}
}
/* End of file sidebar.php */