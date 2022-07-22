<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Em_knowledge extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        User::logged_in();
        $this->load->model(array('App'));
        $this->load->library('form_validation');
        $this->applib->set_locale();
        $this->load->helper('security');
        if(!App::is_access('menu_knowledgebase'))
        {
          $this->session->set_flashdata('tokbox_error', lang('access_denied'));
             redirect('');
        }
    }

    public function index()
    {
        $post = $this->input->post();
       // echo 'post<pre>'; print_r($post); exit;
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('ad_knowledge').' - '.config_item('company_name'));
        $data['form'] = TRUE;
        $data['page'] = lang('ad_knowledge');
        $data['categories'] = $this->db->select('*')->get_where('category', array('status!='=>'2'))->result();

        if($post['search_value'] != '' && $post['isSearch'] == '1') {

            $this->db->like('ADT.topic', $post['search_value']);
            $data['knowledge_details'] = $this->db->select('AD.category,AD.id,AD.views,ADT.topic,ADC.category_name')
                                                  ->from('ad_knowledge AD')
                                                  ->join('ad_knowledge_topic ADT','AD.id = ADT.knowledge_id')
                                                  ->join('category ADC','AD.category = ADC.id')
                                                  ->where('AD.status','1')
                                                  ->get()->result();
        }
        
        if($post['search_value'] != '' && $post['isSearch'] == '1') {
            echo json_encode($data); exit;
        } 
        if($post['search_value'] == '' && $post['isSearch'] == '1') {
            $data['knowledge_details'] = $this->db->select('AD.category,AD.id,AD.views,ADT.topic,ADC.category_name')
                                                  ->from('ad_knowledge AD')
                                                  ->join('ad_knowledge_topic ADT','AD.id = ADT.knowledge_id')
                                                  ->join('category ADC','AD.category = ADC.id')
                                                  ->where('AD.status','1')
                                                  ->get()->result();
            echo json_encode($data); exit;
        } 
        if(!$_POST){
          $data['knowledge_details'] = $this->db->select('*')->get_where('ad_knowledge', array('status!='=>'2'))->result();
        }
        $this->template
            ->set_layout('users')
            ->build('em_knowledge',isset($data) ? $data : NULL);
    }

   public function em_knowledge_view($id)
   {
        $this->load->module('layouts');
        $this->load->library('template');
        $this->template->title(lang('ad_knowledge').' - '.config_item('company_name'));
        $data['form'] = TRUE;
        $data['page'] = lang('ad_knowledge');
        $data['id'] = $id;
        
        if($this->uri->segment(2) == 'em_knowledge_view') {
            $views = $this->db->get_where('ad_knowledge_comments', array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id'),'views'=>'0'))->row();
           if($views) {
                $data = array(
                    'views'=>'1'
                );
                App::update('ad_knowledge_comments',array('id'=>$views->id), $data);
            } 

            $new_views = $this->db->get_where('ad_knowledge_comments', array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')))->row();

            if(empty($new_views)) {
                $views_data = array(
                    'knowledge_id' => $id,
                    'user_id'   => $this->session->userdata('user_id'),
                    'views' =>'1',
                    'created_datetime' => date('Y-m-d H:i:s')
                );
                $this->db->insert('ad_knowledge_comments',$views_data);
            }
        }
        
        $data['knwldge_data'] = $this->db->select('*')->get_where('ad_knowledge', array('id'=>$id,'status!='=>'2'))->row();

        $data['knowledge_details'] = $this->db->select('ad_k.*,c.category_name,comments.id as comment_id,count(comments.id) as comment_count,comments.comments,comments.created_datetime as comments_date,u.username,count(comments.views) as view_count')
            ->from('ad_knowledge as ad_k')
            ->join('category as c', 'c.id=ad_k.category', 'LEFT')
            ->join('ad_knowledge_comments as comments', 'comments.knowledge_id=ad_k.id', 'LEFT')
            ->join('users as u', 'u.id=comments.user_id', 'LEFT')
            ->where('ad_k.id', $id)
            //->where('comments!=', '')
            ->get()
            ->row();

        $data['comments'] = $this->db->select('comments.id,comments.knowledge_id,comments.user_id,comments.comments,comments.created_datetime,u.username')
            ->from('ad_knowledge_comments as comments')
            ->join('ad_knowledge as ad_k', 'ad_k.id=comments.knowledge_id', 'LEFT')
            ->join('users as u', 'u.id=comments.user_id', 'LEFT')
            ->order_by('comments.id','DESC')
            ->where(array('ad_k.id'=>$id, 'comments!='=>'')) //
            ->get()
            ->result();
        $data['no_of_comments']=  $this->db->select('*')
            ->from('ad_knowledge_comments')
            ->where(array('knowledge_id'=>$id, 'comments !='=>''))
            ->get()
            ->num_rows();

        // $data['likes'] = $this->db->select('count(comments.likes) as likes_count')
        //     ->from('ad_knowledge_comments as comments')
        //     ->where(array('comments.knowledge_id'=>$id, 'likes'=>'1'))
        //     ->get()
        //     ->row(); 

        $data['likes'] = $this->db->get_where('ad_knowledge_likes',array('knowledge_id'=>$id,'like_count'=>'1'))->result_array();
        // echo $this->db->last_query(); exit;

        $data['categories'] = $this->db->select('*')
            ->from('category')
            ->where(array('status!='=>'2'))
            ->get()
            ->result();

         $data['popular_articles'] = $this->db->select('*')
            ->from('ad_knowledge')
            //->where('views>','1')
            ->where(array('status!='=>'2'))
            ->order_by('views', 'DESC')
            ->limit(5)
            ->get()
            ->result();
            
        /*$data['popular_articles'] = $this->db->select('ad_k.*, count(c.views) as view_count')
            ->from('ad_knowledge as ad_k')
            ->join('ad_knowledge_comments as c', 'c.knowledge_id=ad_k.id', 'LEFT')
            ->where('c.views>','1')
            ->where(array('status!='=>'2'))
            ->order_by('c.views', 'DESC')
            ->limit(5)
            ->get()
            ->result();*/


        $data['latest_articles'] = $this->db->select('*')
            ->from('ad_knowledge')
            ->where('created_datetime BETWEEN DATE_SUB(NOW(), INTERVAL 5 DAY) AND NOW()')
            ->where(array('status!='=>'2'))
            ->limit(5)
            ->get()
            ->result();
        //echo 'det<pre>'; print_r($data['popular_articles']); exit;
        $this->template
            ->set_layout('users')
            ->build('em_knowledge_view',isset($data) ? $data : NULL);
   }

   // public function add_like_details($id, $likes) 
   // {
   //      $like =  $this->uri->segment(4);
   //      //echo 'iddd<pre>'; print_r($like); exit;
   //      if($like == '1') {
   //          $knowledge_data = $this->db->select('*')->get_where('ad_knowledge_comments', array('id'=>$id, 'user_id'=>$this->session->userdata('user_id')))->row();
            
   //          if($knowledge_data == '') {
   //              if($knowledge_data->likes=='1') {
   //                  $this->db->set('likes', '1',false);
   //                  $this->db->where(array('id'=>$id,'user_id'=>$this->session->userdata('user_id')));
   //                  $this->db->update('ad_knowledge_comments');
   //              } else {
   //                  $likes_data = array(
   //                      'knowledge_id' => $id,
   //                      'user_id'   =>$this->session->userdata('user_id'),
   //                      'like_count' => '1',
   //                      'created_datetime' => date('Y-m-d H:i:s')
   //                  );
   //                  $this->db->insert('ad_knowledge_comments', $likes_data);
   //              }
   //          } else {
   //              $this->db->set('likes', '1',false);
   //              $this->db->where(array('id'=>$id,'user_id'=>$this->session->userdata('user_id')));
   //              $this->db->update('ad_knowledge_comments');

   //              $this->session->set_flashdata('tokbox_success', 'Likes Added Successfully');
   //              redirect($_SERVER['HTTP_REFERER']);
   //          }
            
   //      } 
   //      if($like == '0') {
   //          $count = '1';
   //          $this->db->set('likes', '0',false);
   //              $this->db->where(array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')));
   //              $this->db->update('ad_knowledge_comments');
   //              $this->session->set_flashdata('tokbox_success', 'Unlike Successfully');
   //      } 
       
   //      //if($this->db->affected_rows()>0) {
   //          redirect($_SERVER['HTTP_REFERER']);
   //      //} 
   //      /*else {
   //          $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
   //          redirect($_SERVER['HTTP_REFERER']);
   //      }*/
   //  }

   public function add_like_details($id, $likes) 
   {
        $like =  $this->uri->segment(4);
        // echo $id; echo "  ".$like; exit;
            $knowledge_data = $this->db->get_where('ad_knowledge_likes', array('knowledge_id'=>$id, 'user_id'=>$this->session->userdata('user_id')))->row();
            if($knowledge_data != '') {
                $update_data = array(
                    'knowledge_id' => $id,
                    'user_id'   =>$this->session->userdata('user_id'),
                    'like_count' => $like
                );
                $this->db->where('like_id',$knowledge_data->like_id);
                $this->db->update('ad_knowledge_likes',$update_data);   
            } else {
                $insert_data = array(
                    'knowledge_id' => $id,
                    'user_id'   =>$this->session->userdata('user_id'),
                    'like_count' => $like
                );
                $this->db->insert('ad_knowledge_likes',$insert_data);
            }
            if($like == '1')
            {
                $msg = 'Like Posted';
            }elseif($like == '0'){
                $msg = 'DisLike Posted';
            }
                $this->session->set_flashdata('tokbox_success', $msg);
                redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete_knowledge() {
        if($this->input->post()) {
            $det['status']= '2'; 
            App::update('ad_knowledge',array('id'=>$this->input->post('knowledge_id')),$det);
            $this->session->set_flashdata('tokbox_success', 'Knowledge Details Deleted Successfully');
            redirect('em_knowledge');
        } else {
            $this->session->set_flashdata('tokbox_error', 'Something went wrong, Please try again');
            redirect('em_knowledge');
        }
    }
}