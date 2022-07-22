
<div class="content container-fluid">

	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col-sm-12">
				<h3 class="page-title"><?=lang('attendance')?></h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('attendance')?></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /Page Header -->

	<!-- Search Filter -->
  <form method="post" action="" >
	<div class="row filter-row">
		<div class="col-sm-6 col-md-3">  
			<div class="form-group form-focus">
				<input type="text" class="form-control floating" name="employee_name" id="employee_name" value="" />
				<input type="hidden"  name="employee_id" id="employee_id" value="0" />
				<label class="control-label">Employee Name</label>
			</div>
		</div>
		<div class="col-sm-6 col-md-3"> 
			<?php 
			$s_year = '2015';
			$select_y = date('Y');

			$s_month = date('m');
			$e_year = date('Y');
		 ?>
			<div class="form-group form-focus select-focus">
				<select class="select floating form-control" id="attendance_month" name="attendance_month">  
				<option value="" selected="selected" disabled>Select Month</option>
				<?php 
					for ($ji=1; $ji <=12 ; $ji++) {  ?>
					<option value="<?php echo $ji; ?>" <?php echo ($attendance_month==$ji)?'selected':''; ?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>		
					<?php } ?>
				
			</select>
				<label class="control-label">Select Month</label>
			</div>
		</div>
		<div class="col-sm-6 col-md-3"> 
			<div class="form-group form-focus select-focus">
				<select class="select floating form-control" id="attendance_year" name="attendance_year"> 
					<option value="" selected="selected" disabled>Select Year</option>
					<?php for($k =$e_year;$k>=$s_year;$k--){ ?>
					<option value="<?php echo $k; ?>" <?php echo ($attendance_year==$k)?'selected':''; ?> ><?php echo $k; ?></option>
					<?php } ?>
				</select>
				<label class="control-label">Select Year</label>
			</div>
		</div> 
		<div class="col-sm-6 col-md-3">  
			<!-- <a href="#" class="btn btn-primary text-white btn-block" onclick="attendance_next_filter_page(1)"> Search </a>  -->
			<button class="btn btn-primary btn-block" type="submit"><?=lang('search')?></button> 
		</div>     
	</div>
</form>

	<!-- /Search Filter -->
	
	<div class="row">
		<div class="col-lg-12">
			<div id="attendance_table" class="text-capitalize table-responsive">
				<?php $attendance_footer = '';
       $attendance_body = '';
       $attendance_head = '';

        // datas = JSON.parse(datas);
        $last_day = $last_day;
        $current_page = $current_page;
        $total_page = $total_page;
        $attendance_list = $attendance_list;
        $recordscount = count($attendance_list);
       $attendance_head = '<table id="table-attenedance" class="table table-striped custom-table m-b-0 AppendDataTables"><thead><tr><th>Team Member</th>';
        for ($ik = 1; $ik <= $last_day; $ik++) {
            $attendance_head .= '<th>' . $ik . '</th>';
        }
        $attendance_head .= '</tr></thead>';
        $attendance_body .= '<tbody>';
        if ($recordscount > 0) {
            for ($i = 0; $i < $recordscount; $i++) {

               $record = $attendance_list[$i];

                $name = $record->fullname;
                $attendance = $record->attendance;
               $shift_details;

               $user = $this->db->get_where('users',array('id'=>$record->user_id))->row_array();
            
              if(!empty($user['designation_id'])){
                $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
                $designation_name = $designation['designation'];
                
              }else{
                $designation_name = '-';
              }
            $imgs = '';
                    if($record->avatar != 'default_avatar.jpg'){
                        $imgs = $record->avatar;
                        
                    }else{
                        $imgs = "default_avatar.jpg";
                    }
                  $id_code = ($user['id_code'] !=0)?$user['id_code']:"-";

                $attendance_body .= '<tr><td><h2 class="table-avatar">
                        <a class="avatar avatar-xs" href="'.base_url().'employees/profile_view/'.$record->user_id.'"> <img class="img-fluid" src="'.base_url().'assets/avatar/'.$imgs.'"></a><a class="text-info" href="'.base_url().'attendance/details/'.$record->user_id.'"><span class="username-info">'.user::displayName($record->user_id).'</span>
                        <span class="userrole-info text-muted"> '.$designation_name.'</span>
                      </a>
                        </h2></td>';


               // console.log(attendance);

               $j=1;
                // $.each(attendance, function (key, rec) {
                	foreach ($attendance as $key => $rec) {
                		# code...
                	// }
                    // console.log(parseInt(key) + 1);
                $user_html = "";
                    //if(record.user_id == 320){
                       
                        $status = $rec['day'];
                        $punchin = $rec['punch_in'];
                       	$punch_out = $rec['punch_out'];
                        $user_id = $record->user_id;
                        $day = $key + 1;
                    //     attendance_day: parseInt(key) + 1,
                    //     attendance_month: attendance_month,
                    //     attendance_year: attendance_year,
                    //     punch_in: rec.punch_in,
                    //     punch_out: rec.punch_out

     	
        $day = $key + 1;
        $month = $attendance_month;
        $year  = $attendance_year;
        $punch_in  = $rec['punch_in'];
        $punch_out  = $rec['punch_out'];
       
        $schedule_date = $year.'-'.$month.'-'.$day;
        $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
        $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
        if(!empty($user_schedule)){
            $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);

            $total_scheduled_minutes = $total_scheduled_hour;                                     
            
          } else{
            $total_scheduled_minutes = 0;
          }
          // production_hour
          if(!empty($punch_in) && !empty($punch_out)){
            $production_hour = time_difference(date('H:i',strtotime($punch_in)),date('H:i',strtotime($punch_out)));
          }
              
          // overtimes                    
           if($user_schedule['accept_extras'] == 1){
            $overtimes=($production_hour)-($total_scheduled_minutes);
            if($overtimes > 0)
            {
              $overtime=$overtimes;
              if($production_hour >= $total_scheduled_minutes){
                  $production_hour_achived=  $production_hour;
              }else{
                  $production_hour_achived=  0;
              }
            }
            else
            {
              if($production_hour >= $total_scheduled_minutes){
                  $production_hour_achived=  $production_hour;
              }else{
                  $production_hour_achived=  0;
              }
              $overtime=0;
            }
          } else{

            if($production_hour >= $total_scheduled_minutes){
              $production_hour_achived=  $production_hour;
            }else{
              $production_hour_achived=  0;
            }
            $overtime=0;
          }

          // later_entry_hours

          if(!empty($punch_in))
          {
             $later_entry_hours = later_entry_minutes($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$schedule_date.' '.$punch_in);

          } else {
            $later_entry_hours = 0;
          }

          // Missing worke

          $missing_work=($total_scheduled_minutes)-($production_hour);
                              // echo $missing_work; exit;
          if($missing_work > 0)
          {
            $missing_work=$missing_work;
           
          }
          else
          {
            $missing_work=0;
            
          }


          $overtimes  =  $overtime;
          $production_hour_achived  =  $production_hour_achived;
          $later_entry_hours  =  $later_entry_hours;
          $missing_work  =  $missing_work;
                 
                  
                    $attendance_body .= '<td >';
                    if ($status == '0') {
                    	 if($total_scheduled_minutes ==0){
	                    	$attendance_body .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Week off"><i class="fa fa-close text-danger"></i></a>';
	                    }else {
	                    	 if($punchin == '' && $punch_out == ''){
	                            $attendance_body .= '-';
	                        }
	                    }
                        
                    } else if ($status == '1') {
                        if(($punchin != ''  && $punch_out != '')){
                            if($overtimes!= 0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" data-toggle="tooltip" title="Extra Time Worked"><i class="fa fa-check text-warning"></i></a>';
                            }
                            if($production_hour_achived !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-success" data-toggle="tooltip" title="Workday Complete"></i></a>';
                            }if($later_entry_hours !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-yellow" data-toggle="tooltip" title="Late Arrival"></i></a>';
                            }
                            if($missing_work !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
                            }
                            // user_html += '<a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a>';
                            
                        }else{
                        	if($later_entry_hours !=0){
                                $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-yellow" data-toggle="tooltip" title="Late Arrival"></i></a>';
                            }
                            $attendance_body .= '<a href="'.base_url() . 'attendance/attendance_details/'.$user_id.'/'.$day.'/'.$attendance_month.'/'.$attendance_year.'" data-toggle="ajaxModal" ><i class="fa fa-check text-danger" data-toggle="tooltip" title="Incomplete Workday Time"></i></a>';
                        }
                    } else if ($status == '2') {
                        $attendance_body .= '<i class="text-success" data-toggle="tooltip" title="Worked Hours"></i>';
                    } else if ($status == '0') {
                        $attendance_body .= '<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="No Record for Punch in"></i>';
                    } else if ($status == '') {
                    	if($total_scheduled_minutes ==0){
	                    	$attendance_body .= '<a href="javascript:void(0);" data-toggle="tooltip" title="Week off"><i class="fa fa-close text-danger"></i></a>';
	                    }else{
	                    	 $attendance_body .= '-';
	                    }
                       
                    }
                    $attendance_body .= '</td>';
                  // }
                    
                    // $("#user_"+user_id + " td:last").after(user_html);
                    // });
                    
                    ++$j;
               // }
                
                }
                $attendance_body .= '</tr>';
            }
        } else {
            $attendance_body .= '<tr><td></td></tr>';
        }
        $attendance_body .= '</tbody>';

        $attendance_body .= '</table></div>';

        $attendance_html = $attendance_head .''. $attendance_body;
        echo $attendance_html ; ?>
			</div>
		</div>
	</div>
