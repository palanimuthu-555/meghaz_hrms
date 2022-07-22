<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH.'libraries/phpass-0.1/PasswordHash.php');

class Api_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();

		$this->user = 'dgt_users U';
		$this->users = 'dgt_users';
		$this->account_details = 'dgt_account_details AD';
		$this->account_detail = 'dgt_account_details';
		$this->companies = 'dgt_companies C';
		$this->categories = 'dgt_categories CA';
		$this->countries = 'dgt_countries COU';
		$this->designation = 'dgt_designation D';
		$this->designation_chk = 'dgt_designation';
		$this->departments = 'dgt_departments DE';
		$this->department = 'dgt_departments';
		$this->holidays = 'dgt_holidays H';
		$this->holiday = 'dgt_holidays';
		$this->user_leaves = 'dgt_user_leaves UL';
		$this->user_leave = 'dgt_user_leaves';
		$this->leave_types = 'dgt_leave_types LT';
		$this->payslips = 'dgt_payslip PS';
		$this->salary = 'dgt_salary SA';
		$this->projects = 'dgt_projects PJ';
		$this->files = 'dgt_files FL';
		$this->tasks = 'dgt_tasks TK';
		$this->tasks_timer = 'dgt_tasks_timer TT';
		$this->taskfiles = 'dgt_task_files TKF';
		$this->comments = 'dgt_comments CM ';
		$this->expenses = 'dgt_expenses EX ';
		$this->estimate = 'dgt_estimates ES ';
		$this->estimate_items = 'dgt_estimate_items ESI ';
		$this->invoice = 'dgt_invoices IN';
		$this->items = 'dgt_items IT';
		$this->payments = 'dgt_payments PY';
		$this->payment_methods = 'dgt_payment_methods PYM';

	 
	}

	public function employee_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 if($role == 1 || $role == 4){	
			$user_id = $record['user_id'];
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(DE.deptid IS NOT NULL,DE.deptid,0) as department_id,IF(COU.value IS NOT NULL,COU.value,"-")as countryname');

		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
		//$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
		$this->db->join($this->designation,'D.id=U.designation_id','LEFT');
		$this->db->join($this->departments,'DE.deptid=U.department_id','LEFT');
		$this->db->join($this->countries,'COU.id=AD.country','LEFT');
		$this->db->where('U.role_id',3);
		$this->db->where('U.activated',1);
		$this->db->where('U.banned',0);
 		if(!empty($inputs['email'])){
			$this->db->like('U.email', $inputs['email'], 'BOTH');
		} 
		if(!empty($inputs['fullname'])){
			$this->db->like('AD.fullname', $inputs['fullname'], 'BOTH');
		} 
		if(!empty($inputs['designation'])){
			$this->db->like('U.designation_id', $inputs['designation'], 'BOTH');
		}
		if(!empty($inputs['department'])){
			$this->db->like('D.department_id', $inputs['department'], 'BOTH');
		} 
		if(!empty($inputs['employee_id'])){
			$id =  $inputs['employee_id'];
			$id = str_replace('FP-', '', $id);
			$this->db->like('U.id', $id, 'BOTH');
		} 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('U.id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result();
		 }


		  }else{
		  	if($type==1){
		  		return 0;
		  	}else{
		  		return array();
		  	}
		  }
		} 
	}

	public function remove_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $input['user_id'];
		 	if($role == 1 || $role == 4){
				$this->db->where('role_id',3);
				$this->db->where('id',$user_id);
				return $this->db->update($this->user, array('banned'=>1,'activated'=>0));
		 	}
		}
		return $records;
	}

	public function view_profile($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

		 	if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}
		 	// 	$this->db->select('U.id as user_id,username,U.email,U.role_id,U.designation_id,U.activated,DATE_FORMAT(U.created,"%d %M %Y") as created,AD.fullname,IF(AD.dob !="0000-00-00",AD.dob,"-") as dob,AD.gender,AD.avatar,AD.phone,IF(DE.deptname IS NOT NULL,DE.deptname,"-") AS department,IF(D.designation IS NOT NULL,D.designation,"-") AS designation,IF(D.department_id IS NOT NULL,D.department_id,0) as department_id,IF(AD.city IS NOT NULL,AD.city,"-") as city,IF(AD.country IS NOT NULL,AD.country,"-") as country,address,IF(COU.value IS NOT NULL,COU.value,"-") as countryname');

				// $this->db->from($this->user);
				// $this->db->join($this->account_details,'AD.user_id=U.id','LEFT');
				// //$this->db->join($this->companies,'C.co_id=AD.company','LEFT');
				// $this->db->join($this->designation,'D.id=U.designation_id','LEFT');
				// $this->db->join($this->departments,'DE.deptid=D.department_id','LEFT');
				// $this->db->join($this->countries,'COU.id=AD.country','LEFT');
				// $this->db->where('U.role_id',3);
				// $this->db->where('U.id',$user_id);
				// $records = $this->db->get()->row_array();
				// $records['education_details'] = json_encode($this->education_details($user_id));
				// $records['experience_information'] = json_encode($this->experience_information($user_id));

				$bank_statutories = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array(); 
				$all_addtional = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array();
				$overtime=$this->db->where('user_id',$user_id)->get('overtime')->result_array(); 
							

				 $records['employee_details'] = !empty($this->get_employeedetailById($user_id))?$this->get_employeedetailById($user_id):(object)[];
                 $personal_details = $this->get_employeepersonalById($user_id);
                 $records['personal_info'] = !empty($personal_details['personal_info'])?json_decode($personal_details['personal_info']):(object)[];
                 $records['emergency_info'] = !empty($personal_details['emergency_info'])?json_decode($personal_details['emergency_info']):(object)[];
                 $records['bank_info'] = !empty($personal_details['bank_info'])?json_decode($personal_details['bank_info']):(object)[];

                 $records['family_members_info'] = !empty($personal_details['family_members_info'])?json_decode($personal_details['family_members_info']):[];
                 $records['education_details'] = !empty($personal_details['education_details'])?json_decode($personal_details['education_details']):[];
                 $records['experience_details'] = !empty($personal_details['personal_details'])?json_decode($personal_details['personal_details']):[];

                 $records['in_bank_statutories'] = !empty($bank_statutories['bank_statutory'])?json_decode($bank_statutories['bank_statutory']):(object)[];
                 $records['addtional'] = !empty($all_addtional['pf_addtional'])?json_decode($all_addtional['pf_addtional'],TRUE):[];
                 $records['deduction'] = !empty($all_addtional['pf_deduction'])?json_decode($all_addtional['pf_deduction'],TRUE):[];



		 	}
		}
		return $records;
	}


	Public function get_employeedetailById($id)
	{
	   // return $this->db->get_where('dgt_users',array('id'=>$id))->row_array();
	   return $this->db->select('U.*,AD.*,d.deptname,ds.designation,r.fullname as reporting_to')
	            ->from('dgt_users U')
	            ->join('dgt_account_details AD', 'U.id = AD.user_id')
	            ->join('dgt_account_details r', 'U.teamlead_id = r.user_id')
	            ->join('dgt_designation ds', 'U.designation_id = ds.id','LEFT')
	            ->join('dgt_departments d', 'U.department_id = d.deptid','LEFT')
	            ->where('U.id',$id)
	            ->get()->row_array();
	}

	public function get_employeepersonalById($id)
	{
		return $this->db->get_where('dgt_users_personal_details',array('user_id'=>$id))->row_array();
	}

	public function education_details($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('dgt_profile_education_details')->result();
	}

	public function experience_information($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get('dgt_profile_experience_informations')->result();
	}

	public function profile_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}
		 		$email_check = $this->db->get_where('users',array('id !='=>$user_id,'email'=>$input['email']))->num_rows();

		        if($email_check > 0){
		           return 2;
		        }else{
			 		$account_details = array();
					
					$account_details = array('fullname'=>$input['fullname'],'phone'=>$input['phone']);
			 		if(!empty($input['city'])){
			 			$account_details['city'] = $input['city'];  
			 		}
			 		if(!empty($input['country'])){
			 			$account_details['country'] = $input['country'];  
			 		}
			 		if(!empty($input['address'])){
			 			$account_details['address'] = $input['address'];  
			 		}

					if(!empty($input['gender'])){
						$account_details['gender'] = $input['gender'];	
					}
					if(!empty($input['dob']) && $input['dob'] !='0000-00-00'){
						$account_details['dob'] = date('y-m-d',strtotime($input['dob']));	
					}
					if(!empty($input['emp_doj']) && $input['emp_doj'] !='0000-00-00'){
						$account_details['doj'] = $input['emp_doj'];	
					}
					if(!empty($input['state'])){
						$account_details['state'] = $input['state'];	
					}
					if(!empty($input['pincode'])){
						$account_details['pincode'] = $input['pincode'];	
					}

					$user = array('email'=>$input['email']);
					
					$user['designation_id'] = !empty($input['designation_id'])?$input['designation_id']:"0";	
					
					$user['department_id'] = !empty($input['department_id'])?$input['department_id']:"0";	
					
					$user['teamlead_id'] = !empty($input['reporting_to'])?$input['reporting_to']:'';

					$user['user_type'] = !empty($input['user_type'])?$input['user_type']:'';

					if(!empty($input['reporting_to'])){
			 			$ro = $input['reporting_to'];
			 			
			 		  	$res = array(
		                'is_teamlead' =>'yes' 
		                );
		                $this->db->where('id',$ro);
		                $this->db->update('dgt_users',$res);
			 		}


					$this->db->where('user_id', $user_id);
					$this->db->update($this->account_detail, $account_details);
	 				
	 				$this->db->where('id', $user_id);
					$this->db->update($this->users, $user);
						if($role == 3){
						$this->db->where('user_id', $user_id);
						$this->db->delete('dgt_profile_education_details');
						
						$education_details  = !empty($input['education_details'])?$input['education_details']:'';
						$education_new = array();
						if(!empty($education_details)){
							$education_new = json_decode($education_details,true);
							$final = array();
							foreach ($education_new as $keyvalue) {
								$keyvalue['user_id'] = $user_id;
								$final[] = $keyvalue;
							}
							$education_new = $final;
						}
						 
						
						if(count($education_new)>0){
							$this->db->insert_batch('dgt_profile_education_details', $education_new);
							 
						}

						$this->db->where('user_id', $user_id);
						$this->db->delete('dgt_profile_experience_informations');

						$experience_information  = !empty($input['experience_information'])?$input['experience_information']:'';
						$experience_information_new = array();
						if(!empty($experience_information)){
							$experience_information_new = json_decode($experience_information,true);
							$final = array();
							foreach ($experience_information_new as $keyvalue) {
								$keyvalue['user_id'] = $user_id;
								$final[] = $keyvalue;
							}
							$experience_information_new = $final;
						}
					 	 
				 
						if(count($experience_information_new) > 0){
							$this->db->insert_batch('dgt_profile_experience_informations', $experience_information_new);	
							 
						}
					}
					 
					return 1;
				}
			}
		}		
	}

	public function basic_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		
		 		$basic_info = array(
                'fullname' =>$input['fullname'],
                'dob' =>date("Y-m-d", strtotime($input['dob'])),
                'gender' =>$input['gender'],
                'address' =>$input['address'],
                'state' =>$input['state'],
                'country' =>$input['country'],
                'pincode' =>$input['pincode'],
                'phone' =>$input['phone']
            );

		 		
        $pers_check = $this->db->get_where('account_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $basic_info['user_id'] = $user_id;
            $this->db->insert('account_details',$basic_info);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('account_details',$basic_info);
        }

        $some_details = array(
            'department_id' => $input['department_id'],
            'designation_id'=> $input['designation_id'],
            'teamlead_id'=> $input['teamlead_id']
        );
        $this->db->where('id',$user_id);
        $this->db->update('users',$some_details);
				 
				return 1;
			}
		}		
	}

	public function personal_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$personal_info = array(
                'passport_no' =>$input['passport_no'],
                'passport_expiry' =>$input['passport_expiry'],
                'tel_number' =>$input['tel_number'],
                'nationality' =>$input['nationality'],
                'religion' =>$input['religion'],
                'marital_status' =>$input['marital_status'],
                'spouse' =>$input['spouse'],
                'no_children' =>$input['no_children']
            );
        $result = array(
                'personal_info' => json_encode($personal_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
           $this->db->where('user_id',$user_id);
           $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function emergency_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$emergency_info = array(
                'contact_name1' =>$input['contact_name1'],
                'relationship1' =>$input['relationship1'],
                'contact1_phone1' =>$input['contact1_phone1'],
                'contact1_phone2' =>$input['contact1_phone2'],
                'contact_name2' =>$input['contact_name2'],
                'relationship2' =>$input['relationship2'],
                'contact2_phone1' =>$input['contact2_phone1'],
                'contact2_phone2' =>$input['contact2_phone2']
            );
        $result = array(
                'emergency_info' => json_encode($emergency_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function bank_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		 $bank_info = array(
                'bank_name' =>$input['bank_name'],
                'bank_ac_no' =>$input['bank_ac_no'],
                'ifsc_code' =>$input['ifsc_code'],
                'pan_no' =>$input['pan_no']
            );
        $result = array(
                'bank_info' => json_encode($bank_info)
            );
        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
        if($pers_check == 0)
        {
            $result['user_id'] = $user_id;
            $this->db->insert('dgt_users_personal_details',$result);
        }else{
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }
				 
				return 1;
			}
		}		
	}

	public function family_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		$member_names = $input['member_name']; 
		        $member_relationship = $input['member_relationship']; 
		        $member_dob = $input['member_dob']; 
		        $member_phone = $input['member_phone']; 
		        $family_members = array();
		        for($i = 0; $i< count($member_names); $i++)
		            {
		                $members = array(
		                    'member_name'=>$member_names[$i],
		                    'member_relationship'=>$member_relationship[$i],
		                    'member_dob'=>$member_dob[$i],
		                    'member_phone'=>$member_phone[$i]
		                );
		                $family_members[] = $members;
		            }
		        $result = array(
		                'family_members_info' => json_encode($family_members)
		            );
		        $pers_check = $this->db->get_where('dgt_users_personal_details',array('user_id'=>$user_id))->num_rows();
		        if($pers_check == 0)
		        {
		            $result['user_id'] = $user_id;
		            $this->db->insert('dgt_users_personal_details',$result);
		        }else{
		           $this->db->where('user_id',$user_id);
		           $this->db->update('users_personal_details',$result);
		        }
				 
				return 1;
			}
		}		
	}

	public function education_info_update1($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$institute = $input['institute']; 
		  //      $subject = $input['subject']; 
		  //      $start_date = $input['start_date']; 
		  //      $end_date = $input['end_date']; 
		  //      $degree = $input['degree']; 
		  //      $grade = $input['grade']; 
		  //      $educations = array();
		  //      for($i = 0; $i< count($institute); $i++)
    //         {
    //             $education = array(
    //                 'institute'=>$institute[$i],
    //                 'subject'=>$subject[$i],
    //                 'start_date'=>$start_date[$i],
    //                 'end_date'=>$end_date[$i],
    //                 'degree'=>$degree[$i],
    //                 'grade'=>$grade[$i]
    //             );
    //             $educations[] = $education;
    //         }
    //         // echo $user_id; exit;
    //         print_r($educations); exit;
        $result = array(
                'education_details' => json_decode($input['education_info'])
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}

public function education_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$institute = $input['institute']; 
		  //      $subject = $input['subject']; 
		  //      $start_date = $input['start_date']; 
		  //      $end_date = $input['end_date']; 
		  //      $degree = $input['degree']; 
		  //      $grade = $input['grade']; 
		  //      $educations = array();
		  //      for($i = 0; $i< count($institute); $i++)
    //         {
    //             $education = array(
    //                 'institute'=>$institute[$i],
    //                 'subject'=>$subject[$i],
    //                 'start_date'=>$start_date[$i],
    //                 'end_date'=>$end_date[$i],
    //                 'degree'=>$degree[$i],
    //                 'grade'=>$grade[$i]
    //             );
    //             $educations[] = $education;
    //         }
    //         // echo $user_id; exit;
    //         print_r($educations); exit;
        $result = array(
                'education_details' => $input['education_info']
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => $input['education_info']
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}
	public function experience_info_update($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 	// 	$company_name = $input['company_name']; 
		  //      $location = $input['location']; 
		  //      $job_position = $input['job_position']; 
		  //      $period_from = $input['period_from']; 
		  //      $period_to = $input['period_to'];
		  //      $personals = array();
		  //      for($i = 0; $i< count($company_name); $i++)
		  //          {
		  //              $personal = array(
		  //                  'company_name'=>$company_name[$i],
		  //                  'location'=>$location[$i],
		  //                  'job_position'=>$job_position[$i],
		  //                  'period_from'=>$period_from[$i],
		  //                  'period_to'=>$period_to[$i]
		  //              );
		  //              $personals[] = $personal;
		  //          }
            // echo $user_id; exit;
        $result = array(
                'personal_details' => $input['experience_info']
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();
        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'personal_details' => $input['experience_info']
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}

public function experience_info_update_backup($token,$input)
	{

		$record =  $this->get_role_and_userid($token);
		// print_r($record);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];

			if($role == 3 || $role == 1 || $role == 4){
		 		if($role == 1 || $role == 4){
		 			$user_id = $input['user_id'];
		 		}

		 		//$n=$input['experience_info'][0];
		 		// print_r($input['company_name']);

		 		 $personals = array();
				for($i = 0; $i< count($input['experience_info']); $i++)
				{
				$info=$input['experience_info'][$i];	
				$personal['company_name'] = $info['company_name']; 
				$personal['location'] = $info['location']; 
				$personal['job_position'] = $info['job_position']; 
				$personal['period_from'] = $info['period_from']; 
				$personal['period_to'] = $info['period_to'];
				$personals[] = $personal;
				}
		        
		       
				print_r($personals);

            // echo $user_id; exit;
        $result = array(
                'personal_details' => json_encode($personals)
            );
        // print_r($result); exit;
        $check_user = $this->db->get_where('users_personal_details',array('user_id'=>$user_id))->row_array();

        if(count($check_user) != 0)
        {
            $this->db->where('user_id',$user_id);
            $this->db->update('users_personal_details',$result);
        }else{
            $res = array(
                'user_id' =>$user_id,
                'education_details' => json_encode($educations)
            );
            $this->db->insert('users_personal_details',$res);
        }
				 
				return 1;
			}
		}		
	}



	
	public function forgot_password($token,$input)
	{
		$username = $input['username'];
		// $record =  $this->get_role_and_userid($token);
		$record =  $this->get_role_and_username($username);
	    $records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];
		 	if($role == 3){
		 		
		 		$this->db->select('U.id as user_id,U.username,AD.fullname,U.unique_code,U.email,U.role_id');
		 		$this->db->from($this->user);
		 		$this->db->join($this->account_details, 'AD.user_id = U.id', 'left');
			 	$where = array('activated'=>1,'banned'=>0);
			 	$this->db->where($where);
			 	$this->db->where('(role_id = 3)');
		 	
		 		if (count($this->check_username_email($username)) >1) {
		 				$this->db->where('email', $username);
		 			}else{
		 		 		$this->db->where('username',$username);	
		 			}
			 		$records = $this->db->get('')->row_array();
			 		if(!empty($records)){
			 			$new_pass_key = md5(rand().microtime());
			 			$data = array(
							'id'		=> $records['user_id'],
							'username'		=> $records['username'],
							'email'			=> $records['email'],
							'new_password_key'	=> $new_pass_key,
							'new_password_requested'	=> date('Y-m-d h:i:s'),
						);
						$this->db->where('id', $records['user_id']);
						$result = $this->db->update($this->users, $data);
						$auth = modules::load('auth/auth/');
						
						$data['user_id'] =  $records['user_id'];
						$data['new_pass_key'] =  $new_pass_key;

						 $auth->_send_email('forgot_password', $data['email'], $data);

						 return $result;
			 		}
		 	}
		}
		return FALSE;
	}

		 public function check_username_email($username){

	 	if(!empty($username)){
	 		return $user_or_email = explode('@', $username);
	 	}else{
	 		return FALSE;
	 	}
	 }


	public function change_password($token,$input)
	{
	    $record =  $this->get_role_and_userid($token);
	    $password = !empty($input['current_password'])?$input['current_password']:'';
	    $new_password = $input['confirm_password'];
		$records = array();
		if(!empty($record)){
			$role     = $record['role_id'];
			$user_id  = $record['user_id'];

		 	if($role == 1 || $role == 4){
		 			if(empty($input['user_id'])){
		 				return FALSE;
		 			}
		 			$user_id  = $input['user_id'];
		 			$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));

		 	}elseif($role == 3){

		 		$hasher = new PasswordHash($this->config->item('phpass_hash_strength', 'tank_auth'),
			    $this->config->item('phpass_hash_portable', 'tank_auth'));
	 			
	 			$this->db->where('id', $user_id);
	 			$user_details = $this->db->get($this->users)->row_array();
	 			if(!empty($user_details['password'])){
			 		$check_password = $user_details['password'];	
	 			}else{
	 				$check_password  = '';
	 			}

	 			if($hasher->CheckPassword($password, $check_password)){
	 				$hasher = new PasswordHash(
					$this->config->item('phpass_hash_strength', 'tank_auth'),
					$this->config->item('phpass_hash_portable', 'tank_auth'));
					$hashed_password = $hasher->HashPassword($new_password);
					$this->db->where('id', $user_id);
	 				return $this->db->update($this->users,array('password'=>$hashed_password));
	 			}

		 	}
		}
		return FALSE;	
	}


	public function departments($token)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		$this->db->order_by('deptname', 'ASC');
		 		$this->db->select('deptid,deptname');
		 		$records = $this->db->get($this->departments)->result();
		 	}
		}
		return $records;
	}

	public function user_type($token)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		$this->db->order_by('role','asc');
		 		$this->db->select('r_id,role');
		 		$records = $this->db->get('dgt_roles')->result();
		 	}
		}
		return $records;
	}
	public function leave_type($token)	
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){

		 		$user_id =$record['user_id'];

		 		$annual_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'1'))->row_array();
				$carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'2'))->row_array();
				$earned_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'3'))->row_array();
				$sck_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'4'))->row_array();
				$hospiatality_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'5'))->row_array();

				// $total_hosp_leave = $this->db->get_where('user_leaves',array('user_id'=>$this->session->userdata('user_id'),'leave_type'=>'5'))->result_array();

				// $total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days'] + $earned_leaves['leave_days']);
				$last_yr = date("Y",strtotime("-1 year"));
				// echo $last_yr; exit;
				$carry_days = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$user_id)
									   ->like('leave_from',$last_yr)
									   ->like('leave_to',$last_yr)
									   ->get()->row()->leave_days;
				$total_hosp_leave = $this->db->select_sum('leave_days')
											   ->from('dgt_user_leaves')
											   ->where('user_id',$user_id)
											   ->where('leave_type','5')
											   ->where('status','1')
											   ->get()->row()->leave_days;
									   // echo $this->db->last_query(); exit;

				$last_yr_leaves = $this->db->get_where('yearly_leaves',array('years'=>$last_yr))->row_array();
				if(count($last_yr_leaves) != 0 )
				{
					$l = json_decode($last_yr_leaves['total_leaves'],TRUE);

					$lst_anu_leaves = $l['annual_leaves'];
					$lst_cr_leaves = $l['cr_leaves'];
					$last_total = $lst_anu_leaves +  $lst_cr_leaves;
					if($carry_days != '')
					{
						$bl_leaves = $carry_days - $last_total; 
					}else{
						$bl_leaves = 0; 
					}
					// echo $bl_leaves; exit;
					if($bl_leaves < 0){			
						$ext_leaves = abs($bl_leaves);
					}else{
						$ext_leaves = 0;
					}
					if($ext_leaves == $carry_leaves['leave_days'])
					{
						$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
					}
					if($ext_leaves > $carry_leaves['leave_days'])
					{
						$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
					}
					if($ext_leaves < $carry_leaves['leave_days']){
						$total_count = ($annual_leaves['leave_days'] + $ext_leaves);
					}
					if($ext_leaves == 0)
					{
						$total_count = $annual_leaves['leave_days'];
					}


				}else{
					$total_count = $annual_leaves['leave_days'];
				}

		 		$total_leaves = array();
		  		$normal_leaves = array();
		  		$medical_leaves = array();
		  		$sick_leaves = array();
		  		$leaves = $this->check_leavesById($user_id);
		  		$nor_leaves = $this->check_leavesBycat($user_id,'1');
		  		$med_leaves = $this->check_leavesBycat($user_id,'2');
		  		$sick_leav = $this->check_leavesBycat($user_id,'4');
		  		$sk_leaves = $this->check_leavesBycat($user_id,'3');
		  		for($i=0;$i<count($leaves);$i++)
		  		{
		  			$total_leaves[] = $leaves[$i]['leave_days'];
		  		}
		  		foreach($nor_leaves as $n_leave)
		  		{
		  			$normal_leaves[] = $n_leave['leave_days'];
		  		}
		  		foreach($med_leaves as $md_leave)
		  		{
		  			$medical_leaves[] = $md_leave['leave_days'];
		  		}
		  		foreach($sk_leaves as $sk_leave)
		  		{
		  			$sick_leaves[] = $sk_leave['leave_days'];
		  		}
		  		foreach($sick_leav as $sick_lea)
		  		{
		  			$all_sick_leaves[] = $sick_lea['leave_days'];
		  		}

		  		$t_leaves = array_sum($total_leaves);
		  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
		  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
		  		if($lop > 0 )
		  		{
		  			$lop_days = $lop;
		  		}else{
		  			$lop_days = 0;
		  		}

		  		$re_leaves = (12 - $t_leaves);

		  		$an_leaves        = array();
		  		$crfd_leaves      = array();
		  		$ernd_leaves      = array();
		  		$anu_leaves       = $this->check_leavesBycat($user_id,'1');
		  		$cr_leaves 		  = $this->check_leavesBycat($user_id,'2');
		  		$er_leaves 		  = $this->check_leavesBycat($user_id,'3');
		  		foreach($anu_leaves as $anu_leave)
		  		{
		  			$an_leaves[] = $anu_leave['leave_days'];
		  		}
		  		foreach($cr_leaves as $cr_leave)
		  		{
		  			$crfd_leaves[] = $cr_leave['leave_days'];
		  		}
		  		foreach($er_leaves as $er_leave)
		  		{
		  			$ernd_leaves[] = $er_leave['leave_days'];
		  		}

		  		// $tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves) + array_sum($ernd_leaves));
		  		$tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves));

		  		$tot_sk_leaves = array_sum($all_sick_leaves)?array_sum($all_sick_leaves):'0';


		  		$extra_leaves = $this->db->get_where('assigned_policy_user',array('user_id'=>$user_id));

		  		$extra_policy_leaves = array();
		  		$all_extra_policy_leaves = array();

		  		foreach ($extra_leaves->result_array() as $extra) {
		  			$extra_days = $this->db->get_where('custom_policy',array('policy_id'=>$extra['policy_id']))->row_array();
		  			$extra_policy_leaves[] = $extra_days['policy_leave_days'];
		  		}

		  		$user_detail = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();

		  		$maternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'6'))->row_array();
		  		$paternity_leaves = $this->db->get_where('common_leave_types',array('leave_id'=>'7'))->row_array();



		  		$total_maternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$user_id)
												  ->where('leave_type','6')
												  ->where('status','1')
												  ->get()->row()->leave_days;


		  		$total_paternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$user_id)
												  ->where('leave_type','7')
												  ->where('status','1')
												  ->get()->row()->leave_days;



		  		$cr_yr = date('Y');
		  		$total_user_leaves = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$user_id)
									   ->where('status','1')
									   ->where('leave_type','1')
									   ->like('leave_from',$cr_yr)
									   ->like('leave_to',$cr_yr)
									   ->get()->row()->leave_days;

				if($extra_leaves->num_rows() != 0){
					$total_count = ($total_count + array_sum($extra_policy_leaves));
				}


				$sk_lops = ($sck_leaves['leave_days'] - $tot_sk_leaves);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($total_count - $total_user_leaves);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}else{
					$anu_lop = 0;
				}
				$tot_hosp_count = ($hospiatality_leaves['leave_days'] - $total_hosp_leave);
				if($tot_hosp_count < 0 )
				{
					$hosp_lop = abs($tot_hosp_count);
				}else{
					$hosp_lop = 0;
				}

				$total_lop = ($anu_lop + $sick_lop + $hosp_lop);


				// Maternity Leave Conditions..

			$doj = $user_detail['doj'];
			$cr_date = date('Y-m-d');

			$ts1 = strtotime($doj);
			$ts2 = strtotime($cr_date);
			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);
			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);
			$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);


			if($total_user_leaves != 0){ $t_anu_leaves = $total_user_leaves; }else{ $t_anu_leaves = 0; }
                        $annual_leaves=($t_anu_leaves.' / '.$total_count); 

                        $sick_leave='';
                        $maternity_leave='';
						$paternity_leave='';
						$hospital_levave='';

                        if($sck_leaves['status'] != 1)
                        { 
                        	$sick_leave=$tot_sk_leaves.' / '.$sck_leaves['leave_days'];
                        }

                        if($total_maternity_leave != 0){
                             $maternity_leave=$maternity_leaves['leave_days'];
                        }
                        if($total_paternity_leave != 0){
                              $paternity_leave=$paternity_leaves['leave_days'];
                        }
                        if($total_hosp_leave != 0){
                              $hospital_levave=$total_hosp_leave.' / '.$hospiatality_leaves['leave_days'];  
                        }



					// $all_leave_types = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->result_array();
					// 		foreach($all_leave_types as $all_leave){

					// 		    	if($job_experience < 3){ // More than 3 months
					// 		    if(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 6) && ($all_leave['leave_id'] != 7) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){
							
					// 			$row['leave_id']=$all_leave['leave_id'];
					// 			$row['leave_type']=$all_leave['leave_type'];
					// 		 } }elseif(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){ 
					// 			$row['leave_id']=$all_leave['leave_id'];
					// 			$row['leave_type']=$all_leave['leave_type'];
					// 	 } 

					// 	 $leave_types[]=$row;

					// 	}
                        $annual_leave = $this->db->get_where('dgt_common_leave_types',array('leave_id'=> 1))->row_array();
		$sick_leave = $this->db->get_where('dgt_common_leave_types',array('leave_id'=> 4))->row_array();
		$this->db->select_sum('leave_days');
		$this->db->where('leave_id !=','1');
		$this->db->where('leave_id !=','4');
		if($username['gender'] =='male'){
			$this->db->where('leave_id !=','6');
		}
		if($username['gender'] =='female'){
			$this->db->where('leave_id !=','7');
		}
		$this->db->where('status','0');
		$other_leave = $this->db->get('dgt_common_leave_types')->row_array();
		$this->db->select_sum('leave_days');
		$annual_leave_count = $this->db->get_where('user_leaves',array('leave_type'=> 1,'user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();
		// echo $this->db->last_query(); exit;
		$this->db->select_sum('leave_days');
		$sick_leave_count = $this->db->get_where('user_leaves',array('leave_type'=> 4,'user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();

		$this->db->select_sum('leave_days');
		$this->db->where('leave_type !=','1');
		$this->db->where('leave_type !=','4');
		$other_leave_count = $this->db->get_where('user_leaves',array('user_id'=>$user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();	
		$this->db->select_sum('leave_days');
		$leave_dayss = $this->db->get('common_leave_types')->row_array();

		$sk_lops = ($sick_leave['leave_days'] - $sick_leave_count['leave_days']);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($annual_leave['leave_days'] - $annual_leave_count['leave_days']);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}else{
					$anu_lop = 0;
				}
				// $tot_hosp_count = ($hospiatality_leaves['leave_days'] - $total_hosp_leave);
				// if($tot_hosp_count < 0 )
				// {
				// 	$hosp_lop = abs($tot_hosp_count);
				// }else{
				// 	$hosp_lop = 0;
				// }

				$tot_other_count = ($other_leave['leave_days'] - $other_leave_count['leave_days']);
				if($tot_other_count < 0 )
				{
					$other_lop = abs($tot_other_count);
				}else{
					$other_lop = 0;
				}


				$total_lop = ($anu_lop + $sick_lop + $other_lop);

							$all_leave_types = $this->db->order_by('leave_type','ASC')->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->result_array();
							// echo print_r($all_leave_types); exit;
							foreach($all_leave_types as $all_leave){
							    	if($job_experience < 3){ // More than 3 months
							    if(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 6) && ($all_leave['leave_id'] != 7) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){
								$row['leave_id']=$all_leave['leave_id'];
								$row['leave_type']=$all_leave['leave_type'];
								$leave_types[] = $row;
							 } }elseif(($all_leave['leave_id'] != 2) && ($all_leave['leave_id'] != 8) && ($all_leave['leave_id'] != 9)){

								if($user_detail['gender'] == 'female'){ 
									if($all_leave['leave_id'] != 7){ 
										$row['leave_id']=$all_leave['leave_id'];
										$row['leave_type']=$all_leave['leave_type'];
										$leave_types[] = $row;
								 }
							} else if($user_detail['gender'] == 'male') {
								if($all_leave['leave_id'] != 6){
									$row['leave_id']=$all_leave['leave_id'];
									$row['leave_type']=$all_leave['leave_type'];
									$leave_types[] = $row;
								 }
							}
							 
							
							
							} 
							// $leave_types[]=$row;
						} 		


						$all_common_leave_types = $this->db->get_where('common_leave_types',array('status '=>'0'))->result_array(); 
						// echo print_r($all_common_leave_types); 
				if(count($all_common_leave_types) != 0){
					foreach($all_common_leave_types as $common_leave){
						$cr_yr = date('Y');
						
						

							 if($job_experience < 3){ // More than 3 months
							    if(($common_leave['leave_id'] != 2) && ($common_leave['leave_id'] != 6) && ($common_leave['leave_id'] != 7) && ($common_leave['leave_id'] != 8) && ($common_leave['leave_id'] != 9)){

							
								
								
								 $other_all_leaves = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$record['user_id'])
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

										  		
									$rows['leave_type_name'] = $common_leave['leave_type'];
										$other_all_leave = ($other_all_leaves !='')?$other_all_leaves:0;
									$rows['leaves_taken'] = $other_all_leave.'/'.$common_leave['leave_days'];
									$leaves_remaning[] = $rows;
								 } }elseif(($common_leave['leave_id'] != 2) && ($common_leave['leave_id'] != 8) && ($common_leave['leave_id'] != 9)){						
								

								
								 if($common_leave['leave_id'] == 6){ 
									if($user_detail['gender'] == 'female'){ 

										$total_maternity_leave = $this->db->select_sum('leave_days')
										  	->from('dgt_user_leaves')
										  	->where('user_id',$record['user_id'])
										  	->where('leave_type',$common_leave['leave_id'])
										  	->where('status','1')
								   			->like('leave_from',$cr_yr)
							  				->like('leave_to',$cr_yr)
										  ->get()->row()->leave_days;
									
									$rows['leave_type_name'] = $common_leave['leave_type'];
									$total_maternity_leaves = ($total_maternity_leave !='')?$total_maternity_leave:0;
									$rows['leaves_taken'] = $total_maternity_leaves.'/'.$common_leave['leave_days'];
									$leaves_remaning[] = $rows;
								
								  } } elseif($common_leave['leave_id'] == 7){ 

									if($user_detail['gender'] == 'male'){ 
									$total_paternity_leave = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$record['user_id'])
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

							  		$rows['leave_type_name'] = $common_leave['leave_type'];
							  		$total_paternity_leaves = ($total_paternity_leave !='')?$total_paternity_leave:0;
									$rows['leaves_taken'] = $total_paternity_leaves.'/'.$common_leave['leave_days'];
									$leaves_remaning[] = $rows;
									
								
								 }  } else{ 
								 
								 $other_all_leaves = $this->db->select_sum('leave_days')
											  	->from('dgt_user_leaves')
											  	->where('user_id',$record['user_id'])
											  	->where('leave_type',$common_leave['leave_id'])
										  		->where('status','1')
								   				->like('leave_from',$cr_yr)
							  					->like('leave_to',$cr_yr)
										  		->get()->row()->leave_days;

										  		// echo print_r($other_all_leaves); exit;
								
									$rows['leave_type_name'] = $common_leave['leave_type'];
									$other_all_leave = ($other_all_leaves !='')?$other_all_leaves:0;
									$rows['leaves_taken'] = $other_all_leave.'/'.$common_leave['leave_days'];
								$leaves_remaning[] = $rows;
								
							 }
						}  

					 }
				}
								
		$this->db->select_sum('leave_days');
		if($job_experience < 3){
			$this->db->where('leave_id !=','6');
			$this->db->where('leave_id !=','7');
			$this->db->where('leave_id !=','8');
			$this->db->where('leave_id !=','9');
		} else {
			if($username['gender'] =='male'){
				$this->db->where('leave_id !=','6');
			}
			if($username['gender'] =='female'){
				$this->db->where('leave_id !=','7');
			}
		}
		
		$this->db->where('status','0');
		$total_leave = $this->db->get('dgt_common_leave_types')->row_array();
		$total_leave = ($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;
		$this->db->select_sum('leave_days');
		$leave_count = $this->db->get_where('user_leaves',array('user_id'=> $user_id,'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();  
		$leave_count = ($leave_count['leave_days'] !='')?$leave_count['leave_days']:0;
		$t_leaves = $leave_count.'/'.$total_leave;
		return $records= array('total_leaves'=>$t_leaves,'loss_of_pay'=>$total_lop,'leave_type' =>$leave_types,'leaves_remaning' =>$leaves_remaning);

                        // return $records= array('annual_leaves'=>$annual_leaves,'sick_leave'=>$sick_leave,'maternity_leave'=>$maternity_leave,'paternity_leave'=>$paternity_leave,'hospital_levave'=>$hospital_levave,'total_leaves'=>$t_leaves,'loss_of_pay'=>$total_lop,'leave_type' =>$leave_types,'leaves_remaning' =>$leaves_remaning);
		 		
		 	}

		}		
	}

	public function check_leavesById($user_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'status'=>1))->result_array();
 	}

 	public function check_leavesBycat($user_id,$cat_id)
 	{
 		return $this->db->get_where('dgt_user_leaves',array('user_id'=>$user_id,'leave_type'=>$cat_id,'status'=>1))->result_array();
 	}

	public function create_department($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){
				$where['deptname'] = $input['department'];

				$alreay = $this->check_input_exists($this->departments,$where);
				if($alreay==0){
					$this->db->insert($this->department, array('deptname'=>$input['department']));
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function leave_apply($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			$teamlead_details = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();


			if($role == 3){
				$where['leave_from'] = date('Y-m-d',strtotime($input['leave_from']));
				$where['leave_to'] = date('Y-m-d',strtotime($input['leave_to']));
				$where['leave_type'] = $input['leave_type'];
				$where['user_id'] = $user_id;
				$where['teamlead_id'] = $teamlead_details['teamlead_id'];

				$alreay = $this->check_input_exists($this->user_leave,$where);
				// echo $alreay; exit;


				if($alreay==0){
					$leave_approver= $this->db->get('leave_approver_settings')->result_array();
					

	 	 	foreach ($leave_approver as $key => $value) {
	 	 		$approvers_id[] = $value['approvers'];
	 	 	}
	 	 	if(!empty($approvers_id)){
	 	 		if (in_array(9, $approvers_id))
			  	{
				  	$approvers[] = $teamlead_details['teamlead_id'];
				  	$teamlead_id_details = $this->db->get_where('users',array('id'=>$teamlead_details['teamlead_id']))->row_array();
				  	$recipient[] = $teamlead_id_details['email'];
				  	if (($key = array_search(9, $approvers_id)) !== false) {
						    unset($approvers_id[$key]);
						}
						if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->result_array();
								if(!empty($user_details)){
									foreach($user_details as $u)
									{
									$approvers[] = $u['id'];	
									$recipient[] = $u['email'];
								    }

								}
								
							}
						}
						
				  }
				else
			  	{
				  	if(!empty($approvers_id)){
							foreach ($approvers_id as $key => $value) {
								$user_details = $this->db->get_where('users',array('designation_id'=>$value))->result_array();
								if(!empty($user_details)){
									foreach($user_details as $u)
									{
									$approvers[] = $u['id'];	
									$recipient[] = $u['email'];
								    }
								}
								
							}
						}
			  	}
	 	 	} else {
	 	 		$approvers[] = $this->input->post('teamlead_id');
	 	 		$teamlead_id_details = $this->db->get_where('users',array('id'=>$teamlead_details['teamlead_id']))->row_array();
			  	$recipient[] = $teamlead_id_details['email'];
	 	 	}
	 	 	
	  	// 	echo "<pre>"; print_r($approvers_id); 
	  	// 	echo "<pre>"; print_r($approvers); 
	  	// 	echo "<pre>"; print_r($recipient); 
			 // echo "<pre>"; print_r($_POST); exit;
	 	 	$approvers_array = $approvers;
	 	 

	 	 	$leave_approvers = $this->db->get('designation')->result_array();			
  			$det['user_id']       = $user_id;
			$det['leave_type']    = $this->input->post('leave_type'); 
			
			$get_customtype = $this->db->get_where('common_leave_types',array('leave_id'=>$this->input->post('leave_type')))->row_array();
			// $det['custom_leave']    = $get_customtype['custom_leave'];
			$det['teamlead_id']    = $approvers; 
 			$det['leave_from']    = date('Y-m-d',strtotime($this->input->post('leave_from')));
			$det['leave_to']      = date('Y-m-d',strtotime($this->input->post('leave_to')));
  			$qry                  =  "SELECT * FROM `dgt_user_leaves` WHERE user_id = ".$user_id."
									  and (leave_from >= '".$det['leave_from']."' or leave_to <= '".$det['leave_to']."')   and status = 0 "; 
 			$contdtn    		  = true;					  
 			$leave_list 		   = $this->db->query($qry)->result_array();
  			$d1 		 		   = strtotime($this->input->post('leave_from'));
 			$d2 		 		   = strtotime($this->input->post('leave_to'));
 			$array1     		   = array();
			for($i = $d1; $i <= $d2; $i += 86400 ){  $array1[] = $i; }  
  			if(!empty($leave_list)){ 
				foreach($leave_list as $key => $val)
				{ 
					$d11  = strtotime($val['leave_from']);
 			        $d22  = strtotime($val['leave_to']);
					for($i = $d11; $i <= $d22; $i += 86400 ){
						if(in_array($i,$array1)){
							$contdtn = false;	
							break;
						} 
					}  
					if(!$contdtn) { break; }
  				}
 			}  
 			if($contdtn){
				$det['leave_days']    = $this->input->post('leave_days');  
				if($det['leave_days'] <= 1){
				   $det['leave_day_type'] = $this->input->post('leave_type'); 
				}
				$det['leave_reason']  = $this->input->post('leave_reason');
                 $det['teamlead_id']  = $teamlead_details['teamlead_id'];
				
				$this->db->insert('dgt_user_leaves',$det);   
				$leave_tbl_id  = $this->db->insert_id();
				// echo $this->db->last_query();
				// exit();
				
				// echo count($approvers_array);
				// exit();
				if (count($approvers_array) > 0) {
					$i = 1;
                    foreach ($approvers_array as $key => $value) {
                        $approvers_details = array(
                            'approvers' => $value,
                            'leave_id' => $leave_tbl_id,
                            'status' => 0,
                            'created_by'=>$user_id,
                            //'lt_incentive_plan' => ($this->input->post('long_term_incentive_plan')?1:0),

                            );//print_r($approvers_details);exit;

                        if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                        		if($i ==1){
	                        		$approvers_details['view_status'] = 1;
		                        } else{
		                        	$approvers_details['view_status'] = 0;
		                        }   
                        	}else{
                        		$approvers_details['view_status'] = 1;
                        	}
                       $this->db->insert('dgt_leave_approvers',$approvers_details);   
                       $login_user_name = user::displayName($user_id);  
                       if($leave_approver[0]['default_leave_approval'] == "seq-approver"){
                    		if($i ==1){   
		                        $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($user_id),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('leave_reason', true),
		                        );
		                		App::Log($args);     
		                	} 
		                }else{
		                	  $args = array(
		                            'user' => $value,
		                            'module' => 'leaves',
		                            'module_field_id' => $leave_tbl_id,
		                            'activity' => 'Leave Requested by '.user::displayName($user_id),
		                            'icon' => 'fa-user',
		                            'value1' => $this->input->post('leave_reason', true),
		                        );
		                		App::Log($args);
		                }   
		                $i++;       

                    }
                     $subject = 'Leave Request';
                    $message = 'Leave approval Request';
                    foreach ($recipient as $key => $u) 
                    {
                        
                        $params['recipient'] = $u;
                        $params['subject'] = '['.config_item('company_name').']'.' '.$subject;
                        $params['message'] = $message;
                        $params['attached_file'] = '';
                        modules::run('fomailer/send_email',$params);
                    }

                    
                }


 				$leave_day_str = $det['leave_days'].' days';
				if($det['leave_days'] < 1){
				 	$leave_day_str = 'Half day';
 				}
				//This is admin alert Email   
				$base_url = base_url();
				
				$login_user_name = ucfirst(user::displayName($user_id)); 
				
				// $this->db->select('value');
				// $records = $this->db->get_where('dgt_config',array('config_key'=>'company_email'))->row_array();

				$log_detail = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
				// if($log_detail['teamlead_id'] != 0)
				// {
					$this->db->select('email');
					$send_mail = $this->db->get_where('dgt_users',array('id'=>$log_detail['teamlead_id']))->row_array();
					$send_mail = !empty($send_mail)?$send_mail:'';
				// }else{
				// 	$send_mail = '';
				// }
				// if($send_mail != '')
				// {
				// 	$recipient       = $send_mail['email'];
				// }
				// else{
				// 	$recipient       = array($records['value']);
				// }
					// <a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/4"> 
					// 							<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
					// 							</a>
					// 							<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/5"> 
					// 							<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
					// 							</a>  
				$from_leave = date('d M Y',strtotime($det['leave_from']));
				$to_leave = date('d M Y',strtotime($det['leave_to']));
				$lead_emails = $this->db->get('dgt_lead_reporter')->result_array(); 
				$emails = array(); 
				foreach($lead_emails as $lead_email){
					$emails[] = $lead_email['reporter_email'];
				}
				 
				$subject         = " Employee Leave Request ";
				$message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' ) </p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/0/0"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>'; 			 
				$params      = array(
										'recipient' => $recipient,
										'subject'   => $subject,
										'message'   => $message
									);   
				$succ = Modules::run('fomailer/send_email',$params);


				$mgt_message         = '<div style="height: 7px; background-color: #535353;"></div>
										<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
											<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">New Leave Request</div>
											<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
												<p> Hi,</p>
												<p> '.$login_user_name.' want to '.$leave_day_str.' Leave ( from :'.$from_leave.' to '.$to_leave.' )</p>
												<p> Reason : <br> <br>
													'.$det['leave_reason'].'
												</p>
												<br> 
												<a style="text-decoration:none" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/1"> 
												<button style="background:#00CC33; border-radius: 5px;; cursor:pointer"> Approve </button> 
												</a>
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves/sts_update/'.$leave_tbl_id.'/2"> 
												<button style="background:#FF0033; border-radius: 5px;; cursor:pointer"> Reject </button> 
												</a>  
												&nbsp;&nbsp;  
												OR 
												<a style="text-decoration:none; margin-left:15px" href="'.$base_url.'leaves"> 
												<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Just Login </button> 
												</a>
												<br>
												</big><br><br>Regards<br>The '.config_item('company_name').' Team
											</div>
									 </div>';

					$params1      = array(
										'recipient' => $emails,
										'subject'   => $subject,
										'message'   => $mgt_message
									);   
				// $succ = Modules::run('fomailer/send_email',$params1);




 			}else{
				// $this->session->set_flashdata('alert_message', 'error');
				return FALSE;
			}
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				} 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function check_input_exists($table,$where)
	{
		$this->db->where($where);
		return $this->db->count_all_results($table);
	}

	public function holidays($token,$input)
	{
		$hyear  = !empty($input['hyear'])?$input['hyear']:date('Y');
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){

		 		$this->db->select('id,title,description,holiday_date');
		 		$this->db->order_by('title', 'ASC');
		 		$this->db->where('status',0);
		 		$this->db->like('holiday_date', $hyear, 'BOTH');
		 		$records = $this->db->get($this->holidays)->result();
		 	}
		}
		return $records;
	}

	public function remove_holiday($token,$input)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 4){
				$this->db->where('id',$input['id']);
				return $this->db->update($this->holidays,array('status'=>1));
		 	}
		}
		return $records;
	}

	public function leaves($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];
			$leave_type    = !empty($input['leave_type'])?$input['leave_type']:'';
			$leave_status    = ($input['leave_status'] != '')?$input['leave_status']:'';

		 		$this->db->select('UL.*,CL.leave_type as l_type,AD.fullname');
		 		$this->db->from($this->user_leaves);
		 		$this->db->join('dgt_common_leave_types CL', 'CL.leave_id = UL.leave_type', 'left');
		 		$this->db->join($this->account_details, 'AD.user_id = UL.user_id', 'left');
		 		$this->db->where(array('UL.status !='=>'4'));
		 		if(!empty($leave_type)){
		 			$this->db->where('UL.leave_type',$leave_type);
		 		}
		 		if($leave_status != ''){
		 			$this->db->where('UL.status',$leave_status);
		 		}
		 		$this->db->group_by('UL.id');
		 		if($role == 3){	
		 			$this->db->where('UL.user_id',$user_id);
		 		}
		 		$this->db->where("DATE_FORMAT(UL.leave_from,'%Y')",date('Y'));
		 			
		 			
		 		if($type == 1){
		 			 
		 			$records = $this->db->count_all_results($this->holidays);
		 			
		 		}elseif($type == 2){

		 			$this->db->order_by('UL.id', 'DESC');
		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 			$records = $this->db->get($this->holidays)->result();
		 			//echo 'tere<pre>'; print_r($this->db->last_query()); exit;
		 			
		 		}
	
		}
		return $records;
	}


	public function team_leaves($token,$input,$type)
	{
		
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
			$user_id    = $record['user_id'];
			$leave_type    = !empty($input['leave_type'])?$input['leave_type']:'';
			$leave_status    = ($input['leave_status'] != '')?$input['leave_status']:'';

		 		$this->db->select('UL.*,lt.leave_type as l_type,AD.fullname');
		 		$this->db->from($this->user_leaves);
		 		$this->db->join('dgt_leave_approvers la', 'la.leave_id = UL.id', 'left');
		 		$this->db->join('dgt_common_leave_types lt', 'lt.leave_id = UL.leave_type', 'left');
		 		$this->db->join($this->account_details, 'AD.user_id = UL.user_id', 'left');		 		
		 		$this->db->where(array('UL.status !='=>'7'));

		 		// $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
					// 								FROM `dgt_user_leaves` ul
					// 								left join dgt_leave_approvers la on la.leave_id = ul.id
					// 								left join dgt_common_leave_types_subdomain lt on lt.leave_id = ul.leave_type
					// 								where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
					// 								ul.status != 7 and la.approvers =".$check_teamlead['id']." and la.view_status = 1 order by ul.id  ASC ")->result_array();

		 		if(!empty($leave_type)){
		 			$this->db->where('UL.leave_type',$leave_type);
		 		}
		 		if($leave_status != ''){
		 			$this->db->where('UL.status',$leave_status);
		 		}
		 		$this->db->group_by('UL.id');
		 		if($role == 3){	
		 			$this->db->where('la.approvers',$user_id);
		 		}
		 		$this->db->where("DATE_FORMAT(UL.leave_from,'%Y')",date('Y'));
		 			
		 			
		 		if($type == 1){
		 			 
		 			$records = $this->db->count_all_results($this->holidays);
		 			
		 		}elseif($type == 2){

		 			$this->db->order_by('UL.id', 'DESC');
		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 			$records = $this->db->get($this->holidays)->result();

		 			// echo 'query<pre>'; print_r($this->db->last_query()); exit;
		 		}
		 		//echo 'query<pre>'; print_r($this->db->last_query()); exit;
	
		}
		return $records;
	}

	public function designations($token,$id)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record)){
			$role    = $record['role_id'];
		 	if($role == 1 || $role == 3 || $role == 4){
		 		
		 		$this->db->select('id,designation');
		 		$this->db->where('department_id', $id);
		 		$this->db->order_by('designation', 'ASC');
		 		$records = $this->db->get($this->designation)->result();
		 	}
		}
		return $records;
	}


	public function create_designation($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){
				$where['designation'] = $input['designation'];
				$where['department_id'] = $input['department_id'];
				$where['grade'] = $input['grade'];
				$alreay = $this->check_input_exists($this->designation_chk,$where);
				if($alreay==0){
					$this->db->insert($this->designation_chk,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}
	
	public function create_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){

				$where['title'] = $input['title'];
				
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->insert($this->holiday,$where);
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function edit_holiday($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			if($role == 1 || $role == 4){

				$where['title'] = $input['title'];
				$where['holiday_date'] = date('Y-m-d',strtotime($input['holiday_date']));

				$alreay = $this->check_input_exists($this->holiday,$where);
				if($alreay==0){
					$where['description'] = $input['description'];
					$this->db->where('id',$input['id']);
					$this->db->update($this->holiday,$where);
					 
					return 1;// Insert Successfully..
				}else{
					return 2;// Already exists
				}
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}



	public function leave_cancel($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];

			if($role == 3){

				$where['id'] = $input['leave_id'];
				$this->db->where($where);
				return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	// public function leave_approve_reject($token,$input)
	// {
	// 	$record =  $this->get_role_and_userid($token);
	// 	$records = array();
	// 	if(!empty($record['role_id'])){
	// 		$role    = $record['role_id'];
	// 		$user_id = $record['user_id'];

	// 		if($role == 1 || $role == 4 || $role==3){

	// 			$where['id'] = $input['leave_id'];
	// 			$this->db->where($where);
	// 			return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 
	// 		}else{
	// 			return FALSE;
	// 		}
	// 	}else{
	// 		return FALSE;
	// 	}
		
	// }
	public function leave_approve_reject($token,$input)
	{
		$record =  $this->get_role_and_userid($token);
		$records = array();
		if(!empty($record['role_id'])){
			$role    = $record['role_id'];
			$user_id = $record['user_id'];
			$approver = $record['user_id'];
			$base_url = base_url();
			if($role == 1 || $role == 4 || $role==3){

				// $where['id'] = $input['leave_id'];
				// $this->db->where($where);
				// return $this->db->update($this->user_leave,array('status'=>$input['leave_status']));
 				if($input['leave_status'] == 1){
 					$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$input['leave_id']." ")->result_array();
					$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
					$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();			
					$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

					

					// if($this->input->post('approve') == 'teamlead')
					// {
					// 	$det['reason']      = $this->input->post('reason');  // Teamlead Approval
					// 	// $det['status']      = 4; 
					// 	$det['status']      = 1; 
					// }
					// if($this->input->post('approve') == 'management')
					// {
					// 	$det['reason']      = $this->input->post('reason'); 
					// 	$det['status']      = 1; 
					// }
					$det['reason']      = $input['reason']; 
					$det['status']      = 1; 
					$approvers_status['status'] = 1;
					$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$input['leave_id'],'approvers'=>$approver)); 

						$leave_approvers = $this->db->get('leave_approver_settings')->result_array();
					if($leave_approvers[0]['default_leave_approval'] == "seq-approver"){
					// next approvers view
					// if($_POST['status'] == 1){				
				        $get_approver_record = $this->db->get_where('dgt_leave_approvers',array('leave_id'=>$input['leave_id'],'approvers'=>$approver))->row_array();
				        	        
						
						$view_next = $this->db->query('select * from dgt_leave_approvers where id = (select min(id) from dgt_leave_approvers where id > '.$get_approver_record['id'].')')->row_array();
						$view_status['view_status'] = 1;
						if(!empty($view_next)){
							$this->db->update('dgt_leave_approvers',$view_status,array('leave_id'=>$input['leave_id'],'id'=>$view_next['id'])); 

							$data = array(
										'module' => 'leaves',
										'module_field_id' => $input['leave_id'],
										'user' => $view_next['approvers'],
										'activity' => 'Leave Requested by '.$acc_det[0]['fullname'],
										'icon' => 'fa-plus',
										// 'value1' => $cur.' '.$this->input->post('amount'),
										'value2' => $leave_det[0]['user_id']
										);
							// print_r($data);
						App::Log($data);	

						}	


					// }
				}

					$leave_approvers_status   = $this->db->get_where('leave_approvers',array('status !='=>1,'leave_id'=>$input['leave_id']))->num_rows();
				 	if($leave_approvers_status == 0){
			         	$this->db->update('dgt_user_leaves',$det,array('id'=>$input['leave_id'])); 
			        } else {
			             $this->db->set(array('reason'=>$det['reason']))->where('id',$input['leave_id'])->update('dgt_user_leaves');
			        }

					// $this->db->update('dgt_user_leaves',$det,array('id'=>$this->input->post('req_leave_tbl_id'))); 
					

			         	$data = array(
								'module' => 'leaves',
								'module_field_id' => $input['leave_id'],
								'user' => $leave_det[0]['user_id'],
								'activity' => 'Leave Approved by '.$approver_det['fullname'],
								'icon' => 'fa-plus',
								'value1' => $cur.' '.$input['reason'],
								'value2' => $leave_det[0]['user_id']
								);
							App::Log($data);

		 			if(!empty($acc_det) && !empty($user_det)){
						$recipient       = array();
						if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
						$subject         = " Leave Request Approved ";
						$message         = '<div style="height: 7px; background-color: #535353;"></div>
												<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
													<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Approved</div>
													<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
														<p> Hi '.$acc_det[0]['fullname'].',</p>
														<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been approved by '.$approver_det['fullname'].' </p> 
														<br>  
														<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
															<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
														</a>
														<br>
														</big><br><br>Regards<br>The '.config_item('company_name').' Team
													</div>
											 </div>';  
						if(!empty($recipient) && count($recipient) > 0){		 
							$params      = array(
													'recipient' => $recipient,
													'subject'   => $subject,
													'message'   => $message
												);   
							$succ = Modules::run('fomailer/send_email',$params); 	
						}
					}  
					return TRUE;
 				}
 				if($input['leave_status'] == 2){
 						$det['reason']      = $input['reason']; 
						$det['status']      = 2; 
						$approver = $record['user_id'];
					// }
					$this->db->update('dgt_user_leaves',$det,array('id'=>$input['leave_id'])); 
					$approvers_status['status'] = 2;
					$this->db->update('dgt_leave_approvers',$approvers_status,array('leave_id'=>$input['leave_id'],'approvers'=>$approver)); 
		  			$leave_det = $this->db->query("SELECT * FROM dgt_user_leaves where id = ".$input['leave_id']." ")->result_array();
					$acc_det   = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$leave_det[0]['user_id']." ")->result_array();
					$user_det  = $this->db->query("SELECT * FROM `dgt_users` where id = ".$leave_det[0]['user_id']." ")->result_array();						
					$approver_det  = $this->db->query("SELECT * FROM `dgt_account_details` where user_id = ".$approver." ")->row_array();

					$data = array(
								'module' => 'leaves',
								'module_field_id' => $input['leave_id'],
								'user' => $leave_det[0]['user_id'],
								'activity' => 'Leave Rejected by '.$approver_det['fullname'],
								'icon' => 'fa-plus',
								'value1' => $cur.' '.$input['reason'],
								'value2' => $leave_det[0]['user_id']
								);
							App::Log($data);
		 			if(!empty($acc_det) && !empty($user_det)){
						$recipient       = array();
						if($user_det[0]['email'] != '') $recipient[] = $user_det[0]['email'];
						$subject         = " Leave Request Rejected ";
						$message         = '<div style="height: 7px; background-color: #535353;"></div>
												<div style="background-color:#E8E8E8; margin:0px; padding:55px 20px 40px 20px; font-family:Open Sans, Helvetica, sans-serif; font-size:12px; color:#535353;">
													<div style="text-align:center; font-size:24px; font-weight:bold; color:#535353;">Leave Request Rejected</div>
													<div style="border-radius: 5px 5px 5px 5px; padding:20px; margin-top:45px; background-color:#FFFFFF; font-family:Open Sans, Helvetica, sans-serif; font-size:13px;">
														<p> Hi '.$acc_det[0]['fullname'].',</p>
														<p> Your Leave Request for '.date('d-m-Y',strtotime($leave_det[0]['leave_from'])).' to '.date('d-m-Y',strtotime($leave_det[0]['leave_to'])).' has been Rejected by '.$approver_det['fullname'].' </p> 
														<br>  
														<a style="text-decoration:none;" href="'.$base_url.'leaves"> 
															<button style="background: #CCCC00; border-radius: 5px;; cursor:pointer"> Click to Login </button> 
														</a>
														<br>
														</big><br><br>Regards<br>The '.config_item('company_name').' Team
													</div>
											 </div>';  
						if(!empty($recipient) && count($recipient) > 0){		 
							$params      = array(
													'recipient' => $recipient,
													'subject'   => $subject,
													'message'   => $message
												);   
							$succ = Modules::run('fomailer/send_email',$params); 	
						}
		 			}  
		 			return TRUE;  
			
 				}

			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
		
	}

	public function get_role_and_userid($token)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('unique_code'=>$token,'activated'=>1))->row_array();
	}

	public function get_role_and_username($username)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('username'=>$username,'activated'=>1))->row_array();
	}
	public function check_username($inputs)
	{
		$this->db->select('id as user_id, role_id');
		return $this->db->get_where($this->user,array('username'=>$inputs['username'],'activated'=>1))->row_array();
	}

	public function change_username($inputs)
	{
		
				
		$this->db->where('id',$inputs['user_id']);
		return $this->db->update($this->user, array('username'=>$inputs['username']));		 
		
	}

	public function users_list_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_salary($user_id)
	{
		
		return $this->db->get_where($this->salary,array('user_id'=>$user_id))->row_array();
	}

	public function get_payslips($user_id,$year,$month,$input,$type)
	{	
			   if(!empty($user_id))
				{
					$this->db->where('user_id',$user_id);
				}
				if(!empty($year))
				{
					$this->db->where('p_year',$year);
				}
				if(!empty($month))
				{
					$this->db->where('p_month',$month);
				}

				if($type == 1){
		 			 
		 			$records = $this->db->count_all_results($this->payslips);
		 			
		 		}elseif($type == 2){

		 			$limit = $input['limit'];
		 			$page  = !empty($input['page'])?$input['page']:1;
		 			$start = 0;
		 			if($page > 1){
		 				$page -=1;
		 				$start = ($page * $limit);
		 			} 

		 			$this->db->limit($limit,$start);
		 			$records =  $this->db->get($this->payslips)->result_array();

		 			
		 		}



		return $records;
	}

	public function check_payslip_exist($user_id,$year,$month)
	{
		return $this->db->get_where('dgt_payslip',array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->num_rows();
	}

	public function check_net_exist($user_id,$amount)
	{
		return $this->db->get_where('dgt_salary',array('user_id'=>$user_id,'amount'=>$amount))->num_rows();
	}

	public function update_user_payslip($user_id,$year,$month,$result)
	{
		$where = array(
			'user_id' => $user_id,
			'p_year' =>$year,
			'p_month' => $month
		);
		$this->db->where($where);
		return $this->db->update('dgt_payslip',$result);
	}

	public function get_user_payslip($user_id)
	{
		$this->db->select('*');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->join($this->designation,'D.id = U.designation_id');
		$this->db->where('U.id',$user_id);
		$this->db->where('U.role_id !=',1);
		return $this->db->get()->row_array();
	}

	public function get_payslip_user($user_id,$year,$month)
	{
		return $this->db->get_where($this->payslips,array('user_id'=>$user_id,'p_year'=>$year,'p_month'=>$month))->row_array();
	}

	public function all_users($user_id,$page)
	{
		$this->db->select('U.id as user_id,AD.fullname');
		$this->db->from($this->user);
		$this->db->join($this->account_details,'AD.user_id = U.id');
		$this->db->where('U.id !=',$user_id);
		if($page == 'salary')
		{
			$this->db->where('U.role_id !=',2);
		}
		$this->db->where('U.role_id !=',4);
		$this->db->where('U.role_id !=',1);
		$this->db->where('U.activated',1);
		return $this->db->get()->result_array();
	}

	public function over_all_projects()
	{
		return $this->db->get($this->projects)->result_array();
	}

	public function project_list($token,$inputs,$type=1)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('*');
		$this->db->from($this->projects);
		if(!empty($inputs['project_title'])){
			$this->db->like('project_title', $inputs['project_title'], 'BOTH');
		} 
		if(!empty($inputs['client'])){
			$this->db->where('client', $inputs['client']);
		}
		 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('project_id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result_array();
		 }
		  
		} 
	}

	public function project_listByUserId($token,$inputs,$type,$user_id)
	{
		
		$record =  $this->get_role_and_userid($token);
		if(!empty($record)){
			
			$role    = $record['role_id'];
		 
			$page    = !(empty($inputs['page']))?$inputs['page']:1;
			// $search  = !(empty($inputs['search']))?$inputs['search']:'';

		$this->db->select('PJ.*');
		$this->db->from($this->projects);
		$this->db->join('dgt_assign_projects ap','ap.project_assigned=PJ.project_id','LEFT');
		$this->db->where(array('ap.assigned_user'=>$user_id));
		if(!empty($inputs['project_title'])){
			$this->db->like('PJ.project_title', $inputs['project_title'], 'BOTH');
		} 
		if(!empty($inputs['client'])){
			$this->db->where('PJ.client', $inputs['client']);
		}
		 

		if($type == 1){
			  return $this->db->count_all_results();	
		}else{
			$page = !empty($inputs['page'])?$inputs['page']:'';
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
		 	$this->db->order_by('PJ.project_id', 'ASC');
		 	$this->db->limit($limit,$page);
			return $this->db->get()->result_array();
		 }
		  
		} 
	}


	public function get_projectByUserId($user_id)
	{
		return $this->db->get_where($this->projects,array('client'=>$user_id))->result_array();
	}
	
	// public function task_by_project($project_id)
	// {
	// 	return $this->db->select('TK.t_id,TK.task_name,TK.estimated_hours,TK.description,TK.task_progress,TK.start_date,TK.due_date,IFNULL(AD.user_id, "0") AS user_id,IFNULL(AD.fullname, "0") AS fullname,IFNULL(AD.avatar, "0") AS avatar,IFNULL(TT.start_time, "0") AS start_time, IFNULL(TT.end_time, "0") AS end_time,IFNULL(TKF.file_id, "0") AS file_id,IFNULL(TKF.file_name, "0") AS file_name,IFNULL(TKF.path, "0") AS path,IFNULL(TKF.title, "0") AS file_title,IFNULL(TKF.size, "0") AS file_size,IFNULL(TKF.description, "0") AS file_description,IFNULL(TKF.uploaded_by, "0") AS file_uploaded_by,IFNULL(TKF.date_posted, "0") AS date_posted')
	// 			 ->from($this->tasks)
	// 			 ->join($this->account_details,'TK.added_by = AD.user_id','left')
	// 			 ->join($this->tasks_timer,'TT.task = TK.t_id','left')
	// 			 ->join($this->taskfiles,'TKF.task = TK.t_id','left')
	// 			 ->where('project',$project_id)
	// 			 ->get()->result_array();
	// 	// return $this->db->get_where($this->tasks,array('project'=>$project_id))->result_array();
	// }

	public function task_by_project($project_id)
	{
		$res = $this->db->select('TK.t_id,TK.assigned_to,TK.task_name,TK.estimated_hours,TK.description,TK.task_progress,TK.start_date,TK.due_date,IFNULL(AD.user_id, "0") AS user_id,IFNULL(AD.fullname, "0") AS fullname,IFNULL(AD.avatar, "0") AS avatar,IFNULL(TT.start_time, "0") AS start_time, IFNULL(TT.end_time, "0") AS end_time')
				 ->from($this->tasks)
				 ->join($this->account_details,'TK.added_by = AD.user_id','left')
				 ->join($this->tasks_timer,'TT.task = TK.t_id','left')
				 ->where('project',$project_id)
				 ->get()->result_array();
				 $nn = array();
		 foreach($res as $result){
		 	$task_comments['task_detail'] = $result;
		 	$task_comments['task_comment'] = $this->db->get_where($this->comments,array('project'=>$project_id,'task_id'=>$result['t_id']))->result_array();
		 	$task_files = $this->db->select('TKF.task as file_task_id,TKF.file_id as task_file_id,TKF.title as file_title,TKF.file_name,TKF.path as file_path,TKF.size as file_size,TKF.description as file_description,TKF.uploaded_by as upload_person_id,TKF.date_posted as upload_date,AD.fullname,AD.avatar')
		 			->from($this->taskfiles)
		 			->join($this->account_details,'AD.user_id = TKF.uploaded_by')
		 			->where('TKF.task',$result['t_id'])
		 			->get()->result_array();
		 	$task_comments['task_files'] = $task_files;
		 	$task_comments['assigned_to']= $this->get_user_detail('members',$result['assigned_to']);

		 	$nn[] = $task_comments;
		 }
		 return $nn;
	}

	public function get_project_files($project_id)
	{
		$this->db->select('FL.path AS project_file_path,FL.file_name AS project_file_name');
		return $this->db->get_where($this->files,array('project'=>$project_id))->result_array();
	}


	public function get_task_status($project_id,$status)
	{
		if($status == "open")
		{
			$result = $this->db->get_where($this->tasks,array('task_progress !='=>100,'project'=>$project_id))->result_array();
		}else{
			$result = $this->db->get_where($this->tasks,array('task_progress ='=>100,'project'=>$project_id))->result_array();
		}
		return $result;
	}

	public function get_task_files($tasks)
	{
		$file_count = array();
		foreach($tasks as $task)
		{
			$res = $this->db->get_where($this->taskfiles,array('task'=>$task['t_id']))->result_array();
			if(($res != 0) || ($res != ''))
			{
				$file_count[] = count($res);
			}
		}
		return array_sum($file_count);
	}

	public function get_comment_project($project_id)
	{
		return $this->db->get_where($this->comments,array('project'=>$project_id))->result_array();
	}

	public function get_user_detail($designation,$ids)
	{
		$all_users = array();
		if($designation == 'lead')
		{
			$all_users = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
			->from($this->user)
			->join($this->account_details,'U.id=AD.user_id')
			->where('U.id',$ids)
			->get()->row_array();
		}else{
			$ids = unserialize($ids); 
			foreach ($ids as $id) {
				$all_users[] = $this->db->select('U.id as user_id,AD.fullname,AD.avatar')
				->from($this->user)
				->join($this->account_details,'U.id=AD.user_id')
				->where('U.id',$id)
				->get()->row_array();
			}
		}
		return array_filter($all_users);;
	}

	public function get_all_clients()
	{
		return $this->db->select('*')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->get()
				->result_array();
	}


	public function client_list($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		        $this->db->select('*');
				$this->db->from($this->companies);
				$this->db->join($this->account_details,'AD.id = C.primary_contact','left');
				$this->db->where('C.is_lead','0');
				if(!empty($inputs['email'])){
					$this->db->like('C.company_email', $inputs['email'], 'BOTH');
				} 
				if(!empty($inputs['client'])){
					$this->db->like('C.company_name
						', $inputs['client'], 'BOTH');
				} 
				if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->order_by('C.co_id', 'ASC');
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_clientById($co_id)
	{
		return $this->db->select('*')
				->from($this->companies)
				->join($this->account_details,'AD.id = C.primary_contact','left')
				->where('C.is_lead','0')
				->where('C.co_id',$co_id)
				->get()
				->row_array();
	}

	// public function get_all_estimate()
	// {
	// 	return $this->db->select('*') 
	// 				->from($this->estimate)
	// 				->join($this->user,'ES.client = U.id')
	// 				->join($this->account_details,'AD.user_id = U.id')
	// 				->get()->result_array();
	// }


	public function get_all_estimate($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	                $this->db->select('ES.status as est_status, ES.*,U.*,AD.*'); 
					$this->db->from($this->estimate);
					$this->db->join($this->user,'ES.client = U.id',LEFT);
					$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);
					if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}

	public function get_estimateByClient($token,$inputs,$type=1,$co_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	                $this->db->select('*'); 
					$this->db->from($this->estimate);
					$this->db->join($this->user,'ES.client = U.id');
					$this->db->join($this->account_details,'AD.user_id = U.id');
					$this->db->where('ES.client',$co_id);
					if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_estimateByClients($co_id)
	{
		return $this->db->select('*') 
					->from($this->estimate)
					->join($this->user,'ES.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('ES.client',$co_id)
					->get()->result_array();
	}

	public function get_estimate_cost($estimate_id)
	{
		return $this->db->get_where($this->estimate_items,array('estimate_id'=>$estimate_id))->result_array();
	}

	public function get_company_details($company_id)
	{
		return $this->db->get_where($this->companies,array('co_id'=>$company_id))->row_array();
	}

	// public function get_all_invoices()
	// {
	// 	return $this->db->select('*') 
	// 				->from($this->invoice)
	// 				->join($this->user,'IN.client = U.id')
	// 				->join($this->account_details,'AD.user_id = U.id')
	// 				->get()->result_array();
	// }


	public function get_all_invoices($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		            $this->db->select('IN.status as inv_status, IN.*, U.*, AD.* '); 
					$this->db->from($this->invoice);
					$this->db->join($this->user,'IN.client = U.id',LEFT);
					$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);

					 if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }

				 if($type == 1){
			   return $this->db->count_all_results();
			   // echo $this->db->last_query(); exit;	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					 return $this->db->get()->result_array();
			   // echo $this->db->last_query(); exit;	
				 }
	}

	

	public function get_invoicesbyClients($client_id)
	{
		return $this->db->select('*') 
					->from($this->invoice)
					->join($this->user,'IN.client = U.id')
					->join($this->account_details,'AD.user_id = U.id')
					->where('IN.client',$client_id)
					->get()->result_array();
	}


	public function get_invoicesbyClient($token,$inputs,$type=1,$client_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

		            $this->db->select('*'); 
					$this->db->from($this->invoice);
					$this->db->join($this->user,'IN.client = U.id');
					$this->db->join($this->account_details,'AD.user_id = U.id');
					$this->db->where('IN.client',$client_id);
					 if(isset($inputs["fromdate"]) && !empty($inputs["fromdate"]))
		            {
		                $fromdate=date('Y-m-d',strtotime($inputs["fromdate"]));
		                 $this->db->where('date(date_saved) >=',$fromdate);
		            }

		             if(isset($inputs["todate"]) && !empty($inputs["todate"]))
		            {
		                $todate=date('Y-m-d',strtotime($inputs["todate"]));
		                $this->db->where('date(date_saved) <=',$todate);
		            }

		            if(isset($inputs["status"]) && !empty($inputs["status"]))
		            {
		                $this->db->where('status',$inputs["status"]);
		            }

				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}

	// public function get_all_expenses()
	// {
	// 	return $this->db->select('EX.*,PJ.project_title,CA.cat_name') 
	// 				->from($this->expenses)
	// 				->join($this->user,'EX.client = U.id','LEFT')
	// 				->join($this->account_details,'AD.user_id = U.id','LEFT')
	// 				->join($this->projects,'PJ.project_id = EX.project','LEFT')
	// 				->join($this->categories,'CA.id = EX.category','LEFT')
	// 				->get()->result_array();
	// }


	public function get_all_expenses($token,$inputs,$type=1)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	            $this->db->select('EX.*,PJ.project_title,CA.cat_name'); 
				$this->db->from($this->expenses);
				$this->db->join($this->user,'EX.client = U.id',LEFT);
				$this->db->join($this->account_details,'AD.user_id = U.id',LEFT);
				$this->db->join($this->projects,'PJ.project_id = EX.project',LEFT);
				$this->db->join($this->categories,'CA.id = EX.category',LEFT);
				 if($type == 1){
			  		return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}


	public function get_expensesbyClient($token,$inputs,$type=1,$client_id)
	{
		        $page    = !(empty($inputs['page']))?$inputs['page']:1;

	            $this->db->select('EX.*,PJ.project_title,CA.cat_name'); 
				$this->db->from($this->expenses);
				$this->db->join($this->user,'EX.client = U.id','LEFT');
				$this->db->join($this->account_details,'AD.user_id = U.id','LEFT');
				$this->db->join($this->projects,'PJ.project_id = EX.project','LEFT');
				$this->db->join($this->categories,'CA.id = EX.category','LEFT');
				$this->db->where('EX.client',$client_id);
				 if($type == 1){
			  return $this->db->count_all_results();	
				}else{
					$page = !empty($inputs['page'])?$inputs['page']:'';
					$limit = $inputs['limit'];
					if($page>=1){
						$page = $page - 1 ;
					}
					$page =  ($page * $limit);	
				 	$this->db->limit($limit,$page);
					return $this->db->get()->result_array();
				 }
	}



	// public function get_expensesbyClient($client_id)
	// {
	// 	return $this->db->select('EX.*,PJ.project_title,CA.cat_name') 
	// 				->from($this->expenses)
	// 				->join($this->user,'EX.client = U.id','LEFT')
	// 				->join($this->account_details,'AD.user_id = U.id','LEFT')
	// 				->join($this->projects,'PJ.project_id = EX.project','LEFT')
	// 				->join($this->categories,'CA.id = EX.category','LEFT')
	// 				->where('EX.client',$client_id)
	// 				->get()->result_array();
	// }

	public function get_invoice_items($invoice_id)
	{
		return $this->db->get_where($this->items,array('invoice_id'=>$invoice_id))->result_array();
	}

	public function get_invoice_payment($invoice_id)
	{
		return $this->db->get_where($this->payments,array('invoice'=>$invoice_id))->result_array();
	}

	public function get_userById($user_id)
	{
		return $this->db->get_where($this->account_detail,array('user_id'=>$user_id))->row_array();
	}

	public function get_invoiceByClientId($client_id)
	{
		// echo $client_id; exit;
		return $this->db->get_where('dgt_invoices',array('client'=>$client_id))->result_array();
	}

	public function get_allProjectsByClient($client_id)
	{
		return $this->db->get_where('dgt_projects',array('client'=>$client_id))->result_array();
	}

	public function get_allEstimateByClient($client_id)
	{
		return $this->db->select('*')
		->from('dgt_estimates E')
		->join('dgt_estimate_items EI','E.est_id = EI.estimate_id','left')
		->where('E.client',$client_id)
		->get()->result_array();
	}

	public function get_deviceIdByUser($user_id)
	{
		return $this->db->get_where('dgt_device_details',array('user_id'=>$user_id))->row_array();
	}

	public function get_taskDetails($task_id)
	{
		// return $this->db->select('*')
		// 		->from($this->tasks)
		// 		->join($this->projects,'PJ.project_id = TK.project')
		// 		->where('TK.t_id',$task_id)
		// 		->get()->row_array();
		return $this->db->get_where('dgt_tasks',array('t_id'=>$task_id))->result_array();
	}

	public function get_taskfileById($task_id)
	{
		return $this->db->get_where('dgt_task_files',array('task'=>$task_id))->result_array();
	}

	public function get_common_session()
	{
		return $this->db->get('dgt_chat_common_session',array('com_sess_id'=>1))->row_array();
	}

	public function check_connectionidByUser($user_id)
	{
		return $this->db->get_where('dgt_chat_connectionids',array('user_id'=>$user_id))->row_array();
	}

	public function connectionid_status($user_id,$connection_id,$status)
	{
		if($status == 'update'){
			$res = array('connection_id' => $connection_id);
			$this->db->where('user_id',$user_id);
			$result = $this->db->update('dgt_chat_connectionids',$res);
		}elseif($status == 'insert'){
			$res = array('user_id' => $user_id,'connection_id' => $connection_id);
			$result = $this->db->insert('dgt_chat_connectionids',$res);
		}
		return $result;
	}

	public function get_all_chat_messagesByUserId($from_id,$to_id)
	{
		$sql= "SELECT * FROM `dgt_chat_conversations` WHERE (`from_id` = '$from_id' AND `to_id` = '$to_id' OR `from_id` = '$to_id' AND `to_id` = '$from_id') AND `msg_type` = 'one'";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_group_members($group_id)
	{
		return $this->db->get_where('dgt_chat_group_members',array('group_id'=>$group_id))->result_array();
	}

	public function get_group_message($group_id)
	{
		$this->db->group_by('message');
		return $this->db->get_where('dgt_chat_conversations',array('msg_type'=>'group','group_id'=>$group_id))->result_array();
	}

	public function get_all_chat_detailsByUserId($user_id)
	{
		$sql= "SELECT * FROM `dgt_chat_conversations` WHERE (`from_id` = '$user_id' OR `to_id` = '$user_id') AND `msg_type` = 'one' ORDER BY `msg_id`  DESC";
			$query = $this->db->query($sql);
			return $query->result_array();
	}

	public function get_all_groupmembers($user_id)
	{
		return $this->db->get_where('dgt_chat_group_members',array('login_id'=>$user_id))->result_array();
	}

	public function get_groupname($group_id)
	{
		return $this->db->get_where('dgt_chat_group_details',array('group_id'=>$group_id))->row_array();
	}

	public function get_last_msg($group_id)
	{
		$this->db->order_by('msg_id','DESC');
		$this->db->limit(1);
		return $this->db->get_where('dgt_chat_conversations',array('group_id' => $group_id,'msg_type' =>'group'))->row_array();

	}

	public function get_all_members($group_id)
	{
		$this->db->select('*');
		$this->db->group_by('login_id');
		$this->db->where('group_id',$group_id);
		return $this->db->get('dgt_chat_group_members')->result_array();
		// return $this->db->get_where('dgt_chat_group_members',array('group_id'=>$group_id))->result_array();
	}

	// public function get_all_timesheetById($user_id)
	// {
	// return $this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname')
	// 				->from('dgt_timesheet TS')
	// 				->join('dgt_projects PS','TS.project_id = PS.project_id')
	// 				->join('dgt_account_details ADS','ADS.user_id = TS.user_id')
	// 				->where('TS.user_id',$user_id)
	// 				->order_by('TS.time_id','DESC')
	// 				->get()->result_array();
	// }

	public function get_all_timesheetById($token,$inputs,$type)
	{
		if($token == 0)
		{
			$user_id = $inputs['user_id'];
		}else{
			$re =  $this->get_role_and_userid($token);
			$user_id = $re['user_id'];
		}

		$this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname');
		$this->db->from('dgt_timesheet TS');
		$this->db->join('dgt_projects PS','TS.project_id = PS.project_id');
		$this->db->join('dgt_account_details ADS','ADS.user_id = TS.user_id');
		$this->db->where('TS.user_id',$user_id);
		if((!empty($inputs['from_date'])) && (!empty($inputs['to_date']))){
			$this->db->where('TS.timeline_date >=', $inputs['from_date']);
			$this->db->where('TS.timeline_date <=', $inputs['to_date']);
		}else if((!empty($inputs['from_date'])) || (!empty($inputs['to_date']))){
			if($inputs['from_date'] !='')
				$this->db->where('TS.timeline_date >=', $inputs['from_date']);
			if($inputs['to_date'] !='')
				$this->db->where('TS.timeline_date <=', $inputs['to_date']);
		} 
		if($type == 1)
		{
			$rr = $this->db->get()->num_rows();
		}else{
			$limit = $inputs['limit'];
			if($page>=1){
				$page = $page - 1 ;
			}
			$page =  ($page * $limit);	
			$this->db->order_by('TS.time_id','DESC');
		 	$this->db->limit($limit,$page);
			$rr = $this->db->get()->result_array();
		}
		return $rr; exit;
	}

	public function get_timesheet($timesheet_id)
	{
		$this->db->select('TS.time_id,TS.user_id,TS.project_id,TS.hours,TS.timeline_date,TS.timeline_desc,PS.project_title as project_name,ADS.fullname');
		$this->db->from('dgt_timesheet TS');
		$this->db->join('dgt_projects PS','TS.project_id = PS.project_id');
		$this->db->join('dgt_account_details ADS','ADS.user_id = TS.user_id');
		$this->db->where('TS.time_id',$timesheet_id);
		return $this->db->get()->row_array();
	}

	public function getAllPayments()
	{
		return $this->db->select('*,IN.reference_no,IN.date_saved,IN.inv_id')
				 ->from($this->payments)
				 ->join($this->companies,'PY.paid_by = C.co_id')
				 ->join($this->payment_methods,'PY.payment_method = PYM.method_id')
				 ->join($this->invoice, 'PY.invoice = IN.inv_id')
				 ->where('PY.inv_deleted','No')
				 ->get()->result_array();
		// return $this->db->get_where($this->payments,array('PY.inv_deleted'=>'No'))->result_array();
	}


	public function get_project_counts($role,$id)
	{
		if($role == 'admin')
		{
			$res = $this->db->get_where('dgt_projects',array('status'=>'Active'))->result_array();
		}else if($role == 'staff'){
			$res = $this->db->get_where('dgt_assign_projects',array('assigned_user'=>$id))->result_array();
		}else if($role == 'client'){
			$res = $this->db->get_where('dgt_projects',array('status'=>'Active','client'=>$id))->result_array();
		}
		return $res;
	}

	public function get_clients_counts($role_id)
	{
		return $this->db->get_where('dgt_users',array('role_id'=>$role_id,'activated'=>1,'banned'=>0))->result_array();
	}

	public function get_tasks_counts($role_id)
	{
		return $this->db->get_where('dgt_assign_tasks',array('assigned_user'=>$role_id))->result_array();
	}

	public function get_estimate_counts($id)
	{
		return $this->db->get_where('dgt_estimates',array('client'=>$id))->result_array();
	}

	public function get_invoice_counts($role,$id)
	{
		if($role == 'admin')
		{
			return $this->db->get('dgt_invoices')->result_array();
		}else if($role == 'client'){
			return $this->db->get_where('dgt_invoices',array('client'=>$id))->result_array();
		}
	}


	public function attendance_list($inputs){

        $page = $inputs['page'];
        $employee_name = $inputs['employee_name'];
        $employee_id = $inputs['employee_id'];
       
       
        $query_string = "SELECT count(U.id) as total_records  FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 ";
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }

        
        
        $total_pages  = $this->db->query($query_string)->row_array();
        
        if(!empty($total_pages)){
            $total_pages  = $total_pages['total_records'];
            if($total_pages > 0){
                $total_pages = ceil($total_pages/10);
            }    
        }else{
             $total_pages = 0 ;
        }
         
        $query_string = "SELECT U.id as user_id,AD.fullname FROM dgt_users U LEFT JOIN dgt_account_details AD ON AD.user_id = U.id WHERE U.role_id = 3 ";
        
        if(!empty($employee_name)){
            $query_string .= " AND AD.fullname LIKE '%".$employee_name."%'";
        }
        if($employee_id !=0){
            $query_string .= " AND U.id =  $employee_id";    
        }
        $query_string .= " ORDER BY AD.fullname ASC ";
        $results =  $this->db->query($query_string)->result();
        $records = array();
        if(!empty($results)){
            foreach ($results as $result) {
                $user_id   = $result->user_id;
                $attendance  = $this->attendance($user_id,$inputs);
                $result->attendance = unserialize($attendance['month_days']);
                $records[] = $result;
            }
        }
        return array($total_pages,$records);
        
    }

    public function attendance($user_id,$inputs)
    {   
        $a_month = $inputs['attendance_month'];
        $a_year =  $inputs['attendance_year'];
        $result = $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        if(!empty($result)){
            return $result;
        }else{
            $days = array();
            $days_in_out = array();
            $lat_day = date('t',strtotime($a_year.'-'.$a_month.'-'.'1'));
            for($i=1;$i<=$lat_day;$i++){
                $day = date('D',strtotime($a_year.'-'.$a_month.'-'.$i));
                $day = (strtolower($day)=='sun')?0:'';
                $day_details = array('day'=>$day,'punch_in'=>'','punch_out'=>'');
                $days[] = $day_details;
                $days_in_out[] = array($day_details);
            }
            $insert = array(
                'user_id'=>$user_id,
                'month_days'=>serialize($days),
                'month_days_in_out'=>serialize($days_in_out),
                'a_month'=>$a_month,
                'a_year'=>$a_year
                );
            $this->db->insert("dgt_attendance_details",$insert);

        return  $this->db->query("SELECT month_days,month_days_in_out FROM dgt_attendance_details WHERE user_id = $user_id AND a_month = $a_month AND a_year = $a_year ")->row_array();
        }
       
    }
    Public function get_languages()
	{
	   
	   return $this->db->select('*')
	            ->from('dgt_languages')
	            ->where('active','1')
	            ->get()->result();
	}
	
	 Public function get_lang($language)
	{
	   
	   return $this->db->select('name')
	            ->from('dgt_languages')
	            ->where('code',$language)
	            ->get()->result();
	}


}

/* End of file Api_model.php */
/* Location: ./application/controllers/Api_model.php */