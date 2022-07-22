<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Promotion extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->load->model(array( 'App'));
        $this->load->model('promotionmodel','promotions');
       
		if(!App::is_access('menu_promotion'))
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
                $this->template->title(lang('promotion')); 
 				$data['datepicker'] = TRUE;
 				$data['datatables'] = TRUE;
				$data['form']       = TRUE; 
                $data['page']       = lang('promotion');
                $data['role']       = $this->tank_auth->get_role_id();
                $this->template
					 ->set_layout('users')
					 ->build('promotion',isset($data) ? $data : NULL);
		}
		else
		{
		   redirect('');	
		}
     }


     public function get_departments()
     {
     	$id=$this->input->post('employeeid');

     	$result=$this->db->query("SELECT u.designation_id,d.grade,g.grade_name FROM dgt_users AS u LEFT JOIN dgt_designation AS d ON u.designation_id=d.id LEFT JOIN dgt_grades AS g ON g.grade_id=d.grade WHERE u.id='".$id."'")->row_array();
     	echo json_encode($result);
        exit;	

     }

     public function get_grades()
     {
     	$id=$this->input->post('employeeid');

     	$results=$this->db->query("SELECT u.designation_id,d.grade,g.grade_name,g.grade_id FROM dgt_users AS u LEFT JOIN dgt_designation AS d ON u.designation_id=d.id LEFT JOIN dgt_grades AS g ON g.grade_id=d.grade WHERE u.id='".$id."'")->row_array();



     		$this->db->where('grade_id !=',$results['grade_id']);
     		$query=$this->db->get('grades');
     		$result= $query->result();
	        $data=array();
			foreach($result as $r)
			{
				$data['value']=$r->grade_id;
				$data['label']=$r->grade_name;
				$json[]=$data;
				
				
			}
		echo json_encode($json);
		exit;
     	
     }


        public function promotion_list()
    {
        $list = $this->promotions->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $a=1;
		$style_w="display:none";
		$style_d="display:none";
		if(App::is_permit('menu_promotion','write'))
		{
			$style_w="";	
		}
		if(App::is_permit('menu_promotion','delete'))
		{
			$style_d="";
		}
		
		
								
			
			
         foreach ($list as $promotion) {

           $no++;
            $row = array();
            $row[] = $a++;
            $row[] = $promotion->fullname;
            $row[] = $promotion->deptname;
            $row[] = $promotion->promotion_from;
            $row[] = $promotion->promotion_to;
            $row[] = date('d M Y',strtotime($promotion->promotiondate));



			if(App::is_permit('menu_promotion','write')==true || App::is_permit('menu_promotion','delete')==true)
			{
				$row[]='<div class="dropdown dropdown-action float-right">
				<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
				<div class="dropdown-menu float-right">
				<a class="dropdown-item" href="#" onclick="edit_promotion('.$promotion->id.')" style="'.$style_w.'"><i class="fa fa-pencil m-r-5"></i> Edit</a>
				<a class="dropdown-item" href="#" onclick="delete_promotions('.$promotion->id.')" style="'.$style_d.'"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
				</div>
				</div>';

			}
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->promotions->count_all(),
            "recordsFiltered" => $this->promotions->count_filtered(),
            "data" => $data,
            );
//output to json format
        echo json_encode($output);
        exit;
    }


    public function promotion_edit($id)
    {
		
        $data = $this->promotions->get_by_id($id);
        echo json_encode($data);
        exit;
    }

   




    public function add_promotion()
    {

    	
        $employee=$this->input->post('employee');
        $designation=$this->input->post('designation');
        $grade=$this->input->post('grade');
        $promotionto=$this->input->post('promotionto');
        $promotiondate=date('Y-m-d',strtotime($this->input->post('promotiondate')));
       
       
            $data = array(
                'employee'=>$employee,
                'designation'=>$designation,
                'grade'=>$grade,
                'promotionto'=>$promotionto,
                'promotiondate'=>$promotiondate,
               'posted_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('promotion',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Promotion added successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Promotion added failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


    public function update_promotion()
    {

        $id=$this->input->post('id');
        $employee=$this->input->post('employee');
        $designation=$this->input->post('designation');
        $grade=$this->input->post('grade');
        $promotionto=$this->input->post('promotionto');
        $promotiondate=date('Y-m-d',strtotime($this->input->post('promotiondate')));

        
            $data = array(
                'employee'=>$employee,
                'designation'=>$designation,
                'grade'=>$grade,
                'promotionto'=>$promotionto,
                'promotiondate'=>$promotiondate,
                
                );
            $this->db->where('id',$id);
            $this->db->update('promotion',$data);
            $result=($this->db->affected_rows()!= 1)? false:true;

            if($result==true) 
            {
                $datas['result']='yes';
                $datas['status']='Promotion update successfully';
            }   
            else
            {
                $datas['result']='no';
                $datas['status']='Promotion update failed!';
            }
        
        echo json_encode($datas);

        exit;

    }


   

    public function promotion_delete($id)
    {
        $data = array(
            'status' =>1,
            );
        $this->promotions->update(array('id' => $id), $data);
        echo json_encode(array("status" => TRUE));
        exit;
    }  
	 





}
