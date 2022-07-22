  <?php 

  date_default_timezone_set('Asia/Kolkata');
  $punch_in_date = date('Y-m-d');
  $punch_in_time = date('H:i');
  $punch_in_date_time = date('Y-m-d H:i');


   $strtotime = strtotime($punch_in_date_time);
   $a_year    = date('Y',$strtotime);
   $a_month   = date('m',$strtotime);
   $a_day     = date('d',$strtotime);
   $a_days     = date('d',$strtotime);
   $a_dayss     = date('d',$strtotime);
   $a_cin     = date('H:i',$strtotime);
   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
   $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

    $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-d'));
    $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array(); 
    if(!empty($today_work_hour)){
      $today_work_hours = work_hours($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['break_time']);

      $today_work_minutes = $today_work_hours; 

      $today_work_hours = floor($today_work_minutes / 60).' hrs '.($today_work_minutes -   floor($today_work_minutes / 60) * 60).' mins';
      // echo print_r($today_work_hours); exit;
    } else{
      $today_work_hours = 0;
    }
    $dt_min = new DateTime("last saturday"); // Edit
    $dt_min->modify('+1 day'); // Edit
    $dt_max = clone($dt_min);
    $dt_max->modify('+6 days');
    $week_start = $dt_min->format('Y-m-d');
    $week_end = $dt_max->format('Y-m-d');
    $week_work_where     = array('employee_id'=>$user_id,'schedule_date >='=> $week_start,'schedule_date <='=>$week_end);
    $week_work_hour = $this->db->get_where('shift_scheduling',$week_work_where)->result_array(); 
     // echo "<pre>";print_r($week_work_hour); 
    if(!empty($week_work_hour)){
      foreach ($week_work_hour as $key => $value) {
        $week_work_hours = work_hours($value['schedule_date'].' '.$value['start_time'],$value['schedule_date'].' '.$value['end_time'],$value['break_time']);

          // $week_hours = explode(' ', $week_work_hours);
           // echo print_r($week_hours); exit;
        $total_week_minutes +=$week_work_hours;
        // $total_week_minutes +=$week_hours[1];
      }
        // echo $total_week_hours/60;  
         $total_week_hours = floor($total_week_minutes / 60).' hrs '.($total_week_minutes -   floor($total_week_minutes / 60) * 60).' mins';
      // if($total_week_minutes >= 60){
      //   $total_week_minutes
      //   $total_week_hours = $total_week_hours + 
      // }
      
    } else{
      $week_work_hours = 0;
    }
    
    $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
    $last_day_this_month  = date('Y-m-t');
    $month_work_where     = array('employee_id'=>$user_id,'schedule_date >='=> $first_day_this_month,'schedule_date <='=>$last_day_this_month);
    $month_work_hour = $this->db->get_where('shift_scheduling',$month_work_where)->result_array(); 
     // echo $this->db->last_query(); exit();
     // echo "<pre>";print_r($month_work_hour); exit();
      if(!empty($month_work_hour)){
      foreach ($month_work_hour as $key => $value) {
        $month_work_hours = work_hours($value['schedule_date'].' '.$value['start_time'],$value['schedule_date'].' '.$value['end_time'],$value['break_time']);
          //$week_hours = explode(' ', $week_work_hours);
           // echo print_r($week_hours); exit;
        //$total_month_hours +=$week_hours[0];
        //$total_month_minutes +=$week_hours[1];
         $total_month_minutes +=$month_work_hours;
      }
      $total_month_hours = floor($total_month_minutes / 60).' hrs '.($total_month_minutes -   floor($total_month_minutes / 60) * 60).' mins';
      
    } else{
      $week_work_hours = 0;
    }
    

   $punchin_id = 1;
   if(!empty($record['month_days'])){
     
    
      $month_days =  unserialize($record['month_days']);
      $month_days_in_out =  unserialize($record['month_days_in_out']);
     
     $a_day -=1;

     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

      $day = $month_days[$a_day];
      $day_in_out = $month_days_in_out[$a_day];


      $latest_inout = end($day_in_out);

    
        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
          $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 1;
        }else{
           $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 0;
        }
     }
         
            
     

     $punchin_time = date("g:i a", strtotime($day['punch_in']));
     $punchout_time = date("g:i a", strtotime($day['punch_out']));
   }

  ?>


  <?php
        $a_dayss -=1;
        $production_hour=0;
        $break_hour=0;

         if(!empty($record['month_days_in_out'])){

         $month_days_in_outss =  unserialize($record['month_days_in_out']);
  // echo "<pre>";print_r($a_dayss); exit;
            $j=1;                  
          foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
          {

              if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
              {
                $day = $a_dayss+1;
                   $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$day));
                    $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                    // echo $day.'' .print_r($today_work_hour); 
                    if($j == 1){
                      
                       $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in']);   
                      // $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in']);     
                       // echo $days; exit;
                      // $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                       
                    }
                    // $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$day).' '.$punch_detailss['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 
                  
                    // if($punch_detailss['punch_out'] > $today_work_hour['max_end_time']){
                    //     $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                    // }else{
                    //     $between_endto_max_end = 0;
                    // }
                                      
    
                    // }
                    
                   $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out']))); 
                   // echo $production_hour; exit;

                    
              }
               

               $j++;
          }
           // echo $later_entry_hours;   exit;    
            // if($production_hour > 0 && $later_entry_hours>0){
            //   $production_hour = $production_hour-$end_between;
            // } else{
            //   $production_hour = $production_hour-$start_between-$end_between;
            // }
           for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                      if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                      {
                          
                          $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                      }

                      
            }
        }

       
    ?>

<div class="content container-fluid">

	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col-sm-8">
				<h3 class="page-title"><?=lang('attendance')?> - <i><?=ucfirst(User::displayName($user_id))?></i></h3>
				<!-- <ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('attendance')?></li>
				</ul> -->
			</div>
      <div class="col-sm-4  text-right m-b-20">     
            <a class="btn back-btn" href="<?php echo base_url()?>attendance"><i class="fa fa-chevron-left"></i> Back</a>
      </div>
		</div>
	</div>
	<!-- /Page Header -->
	
          <div class="row">
            
            <div class="col-md-6">
              <div class="card att-statistics">
               <div class="card-body">
                  <h5 class="card-title">Statistics</h5>
                  <div class="stats-list">
                    <div class="stats-info">

                      <?php
                            // $maxTime = (8*3600);
                            $maxTime = ($today_work_minutes*60);
                           
                            $today_percentage = (($production_hour*60) / $maxTime) * 100;
                           

                      ?>

                      <p>Today <strong><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> <small>/ <?php echo $today_work_hours ?></small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $today_percentage;?>%" aria-valuenow="<?php echo $today_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>

                    

                    <?php
                        $week_production_hour=0;
                        $month_production_hour=0;

                      

                         if(!empty($record['month_days_in_out'])){

                             $month_days_in_out_week =  unserialize($record['month_days_in_out']);

                               $dt_min = new DateTime("last saturday"); // Edit
                               $dt_min->modify('+1 day'); // Edit
                               $dt_max = clone($dt_min);
                               $dt_max->modify('+6 days');
                               $week_start_date = $dt_min->format('d');
                               $week_end_date = $dt_max->format('d');
                               $all_month_days = date('t');
                               $balance_days = ($all_month_days - $week_start_date) + 1;
                               $r = 7 - $balance_days;
                                // echo $r; exit;
                              $week_start_month = $dt_min->format('m');
                              $week_end_month = $dt_max->format('m');
                              $months = explode(',', $week_start_month.','.$week_end_month);
                              $years = explode(',', $week_start_year.','.$week_end_year);
                              $where     = array('user_id'=>$user_id,'a_year'=>$a_year);
                              $this->db->select('month_days,month_days_in_out');
                              $this->db->where_in('a_month',$months);
                              $record_next_month  = $this->db->get_where('dgt_attendance_details',$where)->result_array();
                               $next_month_days =  unserialize($record_next_month[1]['month_days_in_out']);

                               $current_month_days = unserialize($record['month_days']);
                              // echo "<pre>"; print_r($current_month_days); exit;
                              $overall = 0;
                               for($f=0;$f<$r;$f++){
                                $overall += $next_month_days[$f]['production'];
                               }
                              // echo 'over all - '.$overall; exit;

                              $week_start_date = date("d",strtotime('monday this week'));
                              $week_end_date=date("d",strtotime("friday this week"));
                              
                              // echo $week_start_date.'end date : '.$week_end_date; 
                              if($week_start_date >= $week_end_date){
                                $week_start_date = 01;
                              }
                             for ($week=$week_start_date-1; $week <= $week_end_date ; $week++) { 
                                // echo '<pre>'; print_r($month_days_in_out_week);  
                                $w=1;                                         
                              foreach ($month_days_in_out_week[$week] as $punch_detail_week) 
                              {

                                  if(!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out']))
                                  {

                                   $days = $week;
                                        $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                        $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                      if($w == 1){                                       
                                         // print_r($today_work_hour); exit();
                                        $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail_week['punch_in']);   
                                       // $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail_week['punch_in']);     
                                       // echo $days; exit;
                                      //  $first_punch_in = $punch_detail_week['punch_in'];
                                      // $start_between += start_between($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail_week['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                                      
                                    }
                                    // $end_between += end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail_week['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                    //   $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                    //   if($punch_detail_week['punch_out'] > $today_work_hour['max_end_time']){
                                    //     $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                    //   }else{
                                    //     $between_endto_max_end = 0;
                                    //   }
                   
                                      $week_production_hour += time_difference(date('H:i',strtotime($punch_detail_week['punch_in'])),date('H:i',strtotime($punch_detail_week['punch_out'])));


                                  }
                                $w++;
                              }

                            // $week_production_hours = $week_production_hour;
                            // $end_betweens = $end_between;
                            // $start_betweens = $start_between;
                            //  if($week_production_hours > 0 && $later_entry_hours>0){
                            //       $week_production_hours = $week_production_hours-$end_betweens;
                            //     } else{
                            //       $week_production_hours = $week_production_hours-$start_betweens-$end_betweens;
                            //     }
                            //     if($week_production_hours<0){
                            //       $week_production_hours = 0;
                            //     }

                             }
                             
      
                        }
                        // $week_production_hour = $week_production_hours;
                           // echo $week_production_hour; exit;         
                         // $week_maxTime = (40*3600);
                         $week_maxTime = ($total_week_minutes*60);
                         $week_percentage = (($week_production_hour*60) / $week_maxTime) * 100;

                         // $working_hours=working_days(date('n'), date('Y'))*8;
   

                     
                      if(!empty($record['month_days_in_out'])){

                           $month_days_in_out_month =  unserialize($record['month_days_in_out']);

                             $month_start_date = date('01', strtotime(date('Y-m-d')));
                             $month_end_date=date('t', strtotime(date('Y-m-d')));
                             // echo $month_start_date; exit;
                             // $m= 0;
                           for ($month=$month_start_date-1; $month <= $month_end_date-1 ; $month++) { 
                            $m= 1;                                            
                            foreach ($month_days_in_out_month[$month] as $punch_detail_month) 
                            {

                                if(!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out']))
                                {
                                    $days = $month+1;
                                        $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                        $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                      if($m == 1){                                       
                                         // print_r($today_work_hour); exit();
                                      //   $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['max_start_time'],date('Y-m-'.$days).' '.$punch_detail_month['punch_in']);   
                                      //  $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail_month['punch_in']);     
                                      //  // echo $days; exit;
                                      //  $first_punch_in = $punch_detail_month['punch_in'];
                                      // $start_between += start_between($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],date('Y-m-'.$days).' '.$punch_detail_month['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                                      
                                    }
                                    // $end_between += end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail_month['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']); 

                                    //   $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['min_start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                    //   if($punch_detail_month['punch_out'] > $today_work_hour['max_end_time']){
                                    //     $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['max_end_time']);
                                    //   }else{
                                    //     $between_endto_max_end = 0;
                                    //   }

                                    $month_production_hour += time_difference(date('H:i',strtotime($punch_detail_month['punch_in'])),date('H:i',strtotime($punch_detail_month['punch_out'])));


                                }
                                $m++;
                            }
                            // $month_production_hours = $month_production_hour;
                            // $end_betweens = $end_between;
                            // $start_betweens = $start_between;
                            //  if($month_production_hours > 0 && $later_entry_hours>0){
                            //       $month_production_hours = $month_production_hours-$end_betweens;
                            //     } else{
                            //       $month_production_hours = $month_production_hours-$start_betweens-$end_betweens;
                            //     }
                            //     if($month_production_hours<0){
                            //       $month_production_hours = 0;
                            //     }

                           }
      
                        }

                         // $month_production_hour = $month_production_hours;
                           // echo print_r($later_entry_minutes) ; exit;
                          $working_hours= $total_month_minutes;

                                    
                         // $month_maxTime = ($working_hours*3600);
                         $month_maxTime = ($working_hours*60);
                         $month_percentage = (($month_production_hour*60) / $month_maxTime) * 100;


                         $remaining_hour=($working_hours)-$month_production_hour;



                           $month_overtimes=($month_production_hour)-($working_hours);
                          if($month_overtimes > 0)
                          {
                            $month_overtime=$month_overtimes;
                          }
                          else
                          {
                            $month_overtime=0;
                          }

                          $overtime_percentage = (($month_overtime*60) / $month_maxTime) * 100;
                          

                        

                      ?>
                    <div class="stats-info">
                      <p>This Week <strong><?php echo intdiv($week_production_hour, 60).'.'. ($week_production_hour % 60);?> <small>/ <?php echo $total_week_hours;?></small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $week_percentage;?>%" aria-valuenow="<?php echo $week_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>This Month <strong><?php echo intdiv($month_production_hour, 60).'.'. ($month_production_hour % 60);?> <small>/ <?php echo $total_month_hours;?> </small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>Remaining Hours <strong><?php echo intdiv($remaining_hour, 60).'.'. ($remaining_hour % 60);?> <small>/ <?php echo $total_month_hours;?></small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>Overtime <strong><?php echo intdiv($month_overtime, 60).'.'. ($month_overtime % 60);?> hrs</strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $overtime_percentage;?>%" aria-valuenow="<?php echo $overtime_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>

          <?php 
              $s_year = '2015';
              $select_y = date('Y');

              $s_month = date('m');
              $e_year = date('Y');



             ?>

          <!-- Search Filter -->
            <form method="post" action="">
          <div class="row filter-row">
           <div class="col-sm-3 col-6">  
              <div class="form-group form-focus select-focus">
                <label class="control-label"> Month</label>
                <select class="select floating form-control" id="attendance_month" name="attendance_month">  
                <option value="" selected="selected" disabled>Select Month</option>
                <?php 
                  for ($ji=1; $ji <=12 ; $ji++) {  
                    $sele1='';
                    

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      if($_POST['attendance_month']==$ji)
                      {
                        $sele1='selected';
                      }

                    }

                    

                    ?>
                  <option value="<?php echo $ji; ?>" <?php echo $sele1;?>><?php echo date('F',strtotime($select_y.'-'.$ji)); ?></option>    
                  <?php } ?>
                
              </select>
              </div>
            </div>

              <div class="col-sm-3 col-6">  
              <div class="form-group form-focus select-focus">
                <label class="control-label"> Year</label>
                <select class="select floating form-control" id="attendance_year" name="attendance_year"> 
                  <option value="" selected="selected" disabled>Select Year</option>
                  <?php for($k =$e_year;$k>=$s_year;$k--){ 
                    $sele2='';
                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      if($_POST['attendance_year']==$k)
                      {
                        $sele2='selected';
                      }
                    }

                    ?>
                  <option value="<?php echo $k; ?>" <?php echo $sele2;?> ><?php echo $k; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
           
            <div class="col-sm-3 col-6">  
               <button type="submit" class="btn btn-success btn-block">Search</button> 
            </div>     
          </div>
          <!-- /Search Filter -->
          </form>
        
                    <div class="row">
                        <div class="col-lg-12">
              <div class="table-responsive">
                <table class="table table-bordered table-striped custom-table mb-0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Date </th>
                      <th>Punch In</th>
                      <th>Punch Out</th>
                      <th>Production</th>
                      <th>Break</th>
                      <th>Overtime</th>
                      <th>Late Entry</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php

                    if(isset($_POST['attendance_month']) && !empty($_POST['attendance_month']))
                    {
                      $a_month=$_POST['attendance_month'];
                    }

                     if(isset($_POST['attendance_year']) && !empty($_POST['attendance_year']))
                    {
                      $a_year=$_POST['attendance_year'];
                    }





                     $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
                     $this->db->select('month_days,month_days_in_out');
                     $results  = $this->db->get_where('dgt_attendance_details',$where)->result_array();
                     
                     $sno=1;
                     foreach ($results as $rows) {

                          $list=array();


                          $month = $a_month;
                          $year = $a_year;

                          $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);



                            for($d=1; $d<=$number; $d++)
                           {
                              $time=mktime(12, 0, 0, $month, $d, $year);          
                              if (date('m', $time)==$month)       
                                  $date=date('d M Y', $time);
                                  $schedule_date=date('Y-m-d', $time);
                                  $a_day =date('d', $time);

                                  $user_schedule_where     = array('employee_id'=>$user_id,'schedule_date'=>$schedule_date);
                                  $user_schedule = $this->db->get_where('shift_scheduling',$user_schedule_where)->row_array(); 
                                  if(!empty($user_schedule)){
                                      $total_scheduled_hour = work_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$user_schedule['schedule_date'].' '.$user_schedule['end_time'],$user_schedule['break_time']);

                                      $total_scheduled_minutes = $total_scheduled_hour;                                     
                                      
                                    } else{
                                      $total_scheduled_minutes = 0;
                                    }

                                if(!empty($rows['month_days'])){
     
    
                                $month_days =  unserialize($rows['month_days']);
                                $month_days_in_out =  unserialize($rows['month_days_in_out']);
                                $day = $month_days[$a_day-1];
                                $day_in_out = $month_days_in_out[$a_day-1];
                                $latest_inout = end($day_in_out);

                                 $production_hour=0;
                                 $break_hour=0;
                                 $k = 1;
                                foreach ($month_days_in_out[$a_day-1] as $punch_detail) 
                                {

                                    if(!empty($punch_detail['punch_in']) && !empty($punch_detail['punch_out']))
                                    {
                                       $days = $a_day;
                                        $today_work_where     = array('employee_id'=>$user_id,'schedule_date'=>date('Y-m-'.$days));
                                        $today_work_hour = $this->db->get_where('shift_scheduling',$today_work_where)->row_array();
                                      if($k == 1){                                       
                                         // print_r($today_work_hour); exit();
                                        $later_entry_hours = later_entry_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);   
                                       $extra_hours = extra_minutes($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in']);     
                                       // echo $days; exit;
                                      //  $first_punch_in = $punch_detail['punch_in'];
                                      // $start_between = start_between($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],date('Y-m-'.$days).' '.$punch_detail['punch_in'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']); 
                       
                                    }
                                    // $end_between = end_between($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],date('Y-m-'.$days).' '.$punch_detail['punch_out'],$today_work_hour['schedule_date'].' '.$today_work_hour['end_time']); 

                                    //   $between_minstartto_start = between_minstartto_start($today_work_hour['schedule_date'].' '.$today_work_hour['start_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['start_time']);
                                    //   if($punch_detail['punch_out'] > $today_work_hour['end_time']){
                                    //     $between_endto_max_end = between_endto_max_end($today_work_hour['schedule_date'].' '.$today_work_hour['end_time'],$today_work_hour['schedule_date'].' '.$today_work_hour['end_time']);
                                    //   }else{
                                    //     $between_endto_max_end = 0;
                                    //   }
                                      

                                        $production_hour += time_difference(date('H:i',strtotime($punch_detail['punch_in'])),date('H:i',strtotime($punch_detail['punch_out'])));
                                    }
                                              
                                  $k++;                              
                                     
                                }
                                 // echo 'between_minstartto_start'.$between_minstartto_start; exit;
                                // if($production_hour > 0 && $later_entry_hours>0){
                                //   $production_hour = $production_hour-$end_between;
                                // } else{
                                //   $production_hour = $production_hour-$start_between-$end_between;
                                // }
                                // if($production_hour<0){
                                //   $production_hour = 0;
                                // }

                             for ($i=0; $i <count($month_days_in_out[$a_day-1]) ; $i++) { 

                                        if(!empty($month_days_in_out[$a_day-1][$i]['punch_out']) && $month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])
                                        {
                                            
                                            $break_hour += time_difference(date('H:i',strtotime($month_days_in_out[$a_day-1][$i]['punch_out'])),date('H:i',strtotime($month_days_in_out[$a_day-1][ $i+1 ]['punch_in'])));
                                        }

                                        
                              }

                              // $overtimes=($production_hour+$break_hour)-($total_scheduled_minutes);
                              if($user_schedule['accept_extras'] == 1){
                                $overtimes=($production_hour)-($total_scheduled_minutes);
                                if($overtimes > 0)
                                {
                                  $overtime=$overtimes;
                                }
                                else
                                {
                                  $overtime=0;
                                }
                              } else{
                                $overtime=0;
                              }
                              

                                                  

                    ?>


                    <tr>
                      <td><?php echo $sno++;?></td>
                      <td><?php echo $date;?></td>
                      <?php

                      // if(date('D', $time)=='Sat' || date('D', $time)=='Sun')
                      if(empty($user_schedule))
                      {
                        if(!empty($day['punch_in']))
                        {
                          if(!empty($day['punch_in']))
                          {
                             $later_entry_hours = later_entry_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$schedule_date.' '.$day['punch_in']);
                          } else {
                            $later_entry_hours = '-';
                          }
                        ?>

                          <td><?php echo !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';?></td>
                          <td><?php echo !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';?></td>
                          <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?> </td>
                          <td><?php echo !empty($break_hour)?intdiv($break_hour, 60).'.'. ($break_hour % 60).' hrs':'-';?></td>
                          <td><?php
                           // if($today_work_hour['accept_extras'] == 0){
                        // echo "-";
                      // }else {
                          echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';
                      // }
                      ?>
                        
                      </td>
                          <td><?php echo '-';?></td>
                       
                       <?php   
                        }
                        else
                        {
                           echo'<td colspan="6" align="center" style="color:red;text-align: center;"> Week Off  </td>';
                        }

                      }
                      else
                      {
                        if(!empty($day['punch_in']))
                        {
                           $later_entry_hours = later_entry_hours($user_schedule['schedule_date'].' '.$user_schedule['start_time'],$schedule_date.' '.$day['punch_in']);
                        } else {
                          $later_entry_hours = '-';
                        }
                      ?>
                     
                      <td><?php echo !empty($day['punch_in'])?date("g:i a", strtotime($day['punch_in'])):'-';?></td>
                      <td><?php echo !empty($latest_inout['punch_out'])?date("g:i a", strtotime($latest_inout['punch_out'])):'-';?></td>
                      <td><?php echo !empty($production_hour)?intdiv($production_hour, 60).'.'. ($production_hour % 60).' hrs':'-';?> </td>
                      <td><?php echo !empty($break_hour)?intdiv($break_hour, 60).'.'. ($break_hour % 60).' hrs':'-';?></td>
                      <td><?php if($today_work_hour['accept_extras'] == 0){
                        echo "-";
                      }else {
                         echo !empty($overtime)?intdiv($overtime, 60).'.'. ($overtime % 60).' hrs':'-';
                      }?></td>
                       <td><?php echo $later_entry_hours;?></td>
                      <?php  
                      }
                     ?>
                    </tr>
                    <?php } } } ?>

                  </tbody>
                </table>
              </div>
                        </div>
                    </div>
                </div>
        <!-- /Page Content -->