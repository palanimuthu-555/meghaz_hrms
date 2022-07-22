<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.pure-table .table td {
    vertical-align: middle !important;
}
</style>
<?php 
ini_set('memory_limit', '-1');
$cur = App::currencies(config_item('default_currency')); 
$customer = ($client > 0) ? Client::view_by_id($client) : array();
$report_by = (isset($report_by)) ? $report_by : 'all';
?>


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><b><?=lang('leave_report')?></b></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>



         <table border="0"cellpadding="0" cellspacing="0" height="100%" width="1200px" class="inside-report">
            <thead>
              <tr class="attendance_record">                
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('employee')?></th>
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('date')?></th>
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><?=lang('department')?></th>
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">Leave Type</th>
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">No of Days</th>
                  <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">Remaining Leave</th>
                  <th align="left" valign="middle" style="font-weight:700;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">Total Leaves</th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">Total Leave Taken</th>
                  <th align="left" valign="middle" style="font-weight:900;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;">Leave Carry Forward</th>
                 
              </tr>
            </thead>
            <tbody>
				<?php 

      if(!empty($_POST['user_id']) || !empty($_POST['department_id']) || !empty($_POST['teamlead_id']) || !empty($_POST['range']))
                { 
                 
                

                  if(isset($_POST['user_id']) && !empty($_POST['user_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = U.id
                    where ul.user_id = '".$_POST['user_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['department_id']) && !empty($_POST['department_id'])){
                    //$dept_users= $this->db->get_where('users',array('subdomain_id'=>$subdomain_id,'department_id'=>$_POST['department_id']))->result_array();
                    $dept_id = $_POST['department_id'];
                   $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where U.department_id = '".$dept_id."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }
                  if(isset($_POST['teamlead_id']) && !empty($_POST['teamlead_id'])){
                     $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where U.teamlead_id = '".$_POST['teamlead_id']."' AND DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  and ul.status =1 order by ul.id  DESC ")->result_array();
                  }

                  if(isset($_POST['range']) && !empty($_POST['range'])){
                   
                    $date_range = explode('-', $_POST['range']);
                    $start_date = $date_range[0];
                    $end_date = $date_range[1];
                    $start_time=strtotime($start_date);
                    $start_day=date("d",$start_time);
                    $start_month=date("m",$start_time);
                    $start_year=date("Y",$start_time);
                    $end_date=strtotime($end_date);
                    $end_day=date("d",$end_date);
                    $end_month=date("m",$end_date);
                    $end_year=date("Y",$end_date);
                   
                    $from_date = date("Y-m-d", $start_time);       
                      $to_date = date("Y-m-d", $end_date);
                      $earlier = new DateTime($from_date);
                      $later = new DateTime($to_date);

                      $col_count = $later->diff($earlier)->format("%a");

                      $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_users U on U.id = ul.user_id 
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where ((ul.leave_from >= '".$from_date."' AND ul.leave_from >= '".$to_date."') || (ul.leave_to <= '".$from_date."' AND ul.leave_to <= '".$to_date."'))  and ul.status =1 order by ul.id  DESC ")->result_array();
                  } 
                } else{              
                 $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname,ad.doj,ad.gender,ad.avatar
                    FROM `dgt_user_leaves` ul
                    left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
                    left join dgt_account_details ad on ad.user_id = ul.user_id 
                    where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and ul.status =1 order by ul.id  DESC ")->result_array();
            }?>
                    
                <?php if(!empty($leave_list)){
                        $leave_count = array();
                        $job_experience = 0;
                        $doj = '';
                        $sno=1;
                        foreach($leave_list as $key => $levs){ 

                          $doj = $levs['doj'];
                          $cr_date = date('Y-m-d');

                          $ts1 = strtotime($doj);
                          $ts2 = strtotime($cr_date);
                          $year1 = date('Y', $ts1);
                          $year2 = date('Y', $ts2);
                          $month1 = date('m', $ts1);
                          $month2 = date('m', $ts2);
                          $job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);
                          $this->db->select_sum('leave_days');
                          if($job_experience < 3){
                            $this->db->where('leave_id !=','6');
                            $this->db->where('leave_id !=','7');
                            $this->db->where('leave_id !=','8');
                            $this->db->where('leave_id !=','9');
                          } else {
                            if($levs['gender'] =='male'){
                              $this->db->where('leave_id !=','6');
                            }
                            if($levs['gender'] =='female'){
                              $this->db->where('leave_id !=','7');
                            }
                          }
                          $this->db->where('status','0');
                          $total_leave = $this->db->get('dgt_common_leave_types')->row_array();

                          $this->db->select_sum('leave_days');
                          $leave_count = $this->db->get_where('user_leaves',array('user_id'=> $levs['user_id'],'status'=>1,"DATE_FORMAT(leave_from,'%Y')" => date('Y')))->row_array();  
                          $carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id '=>'2'))->row_array();

                          $last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
                          $carry_days = $this->db->select_sum('leave_days')
                                       ->from('dgt_user_leaves')
                                       ->where('user_id',$levs['user_id'])
                                       ->like('leave_from',$last_yr)
                                       ->like('leave_to',$last_yr)
                                       ->get()->row()->leave_days;

                            if($carry_days != '')
                          {
                            $bl_leaves = $carry_days - $total_leave['leave_days']; 
                          }else{
                            $bl_leaves = 0; 
                          }
                          if($bl_leaves < 0){     
                            $ext_leaves = abs($bl_leaves);
                            $ext_leaves = $carry_leaves;
                          }else{
                            $ext_leaves = 0;
                          }
                          $user = $this->db->get_where('users',array('id'=>$levs['user_id']))->row_array();
            
                          if(!empty($user['designation_id'])){
                            $designation = $this->db->get_where('designation',array('id'=>$user['designation_id']))->row_array();
                            $designation_name = $designation['designation'];
                            
                          }else{
                            $designation_name = '-';
                          }
                          if(!empty($user['department_id'])){
                            $department = $this->db->get_where('departments',array('deptid'=>$user['department_id']))->row_array();
                            $department_name = $department['deptname'];
                            
                          }else{
                            $department_name = '-';
                          }
                          $imgs = '';
                                  if($levs['avatar'] != 'default_avatar.jpg'){
                                      $imgs = $levs['avatar'];
                                      
                                  }else{
                                      $imgs = "default_avatar.jpg";
                                  }
                        ?>
                          <tr class="<?php echo $sno;?>" style="background-color:<?php echo ($sno % 2 == 0)?'#fff':'#cbcbcb';?>;font-family:'Open Sans',arial,sans-serif!important;">
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                          <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                            <tr>
                               <td width="40px" style="min-width: 40px">
                               <img class="avatar" style="border-radius:50px;background-color: transparent;border-radius: 30px;display: inline-block;font-weight: 500;height: 38px;line-height: 38px;overflow: hidden;text-align: center;text-decoration: none;text-transform: uppercase;vertical-align: middle;width: 38px;position: relative;" src="<?php echo base_url();?>assets/avatar/<?php echo  $imgs;?>">
                            </td>
                            </tr>
                            <tr>
                              <td align="left" style="text-transform:capitalize;font-size:14px;color:#333;">
                               <?php echo user::displayName($levs['user_id']);?>
                              </td>
                            </tr>
                          </table>
                            
                        </td>
                          <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                  <?=date('d-m-Y',strtotime($levs['leave_from']))?>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                  <?php echo ucfirst($department_name);?>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                             <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                    <button class="btn btn-outline-info btn-sm"><?php echo (!empty($levs['l_type']))?$levs['l_type']:''?></button>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                             <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                   <span class="label " style="background:#ff1a75;padding: 5px 20px ;
    font-size: 18px;min-width: 110px;display: inline-block; font-weight: bold;"><?php 
                                    echo $levs['leave_days'];
                                    if($levs['leave_day_type'] == 1){
                                      echo ' ( Full Day )';
                                    }else if($levs['leave_day_type'] == 2){
                                      echo ' ( First Half )';
                                    }else if($levs['leave_day_type'] == 3){
                                      echo ' ( Second Half )';
                                    }?></span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                             <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                    <span class="btn btn-warning btn-sm"><b><?php echo $total_leave['leave_days'] - $leave_count['leave_days'];?></b></span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                   <span class="btn btn-success btn-sm"><b><?php echo($total_leave['leave_days'] != 0)?$total_leave['leave_days']:0;?></b></span>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                    <?php echo ($leave_count['leave_days'] !='')?$leave_count['leave_days']:0;?>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                            <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                              <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                                <tr>
                                  <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:18px;">
                                   <?php echo $ext_leaves;?>
                                  </td>
                                </tr>
                                
                              </table>
                            </td> 
                        
                        </tr>
                      
                      <?php $sno++;}
                    }?>     
                     
                     
					
				</tbody>
			</table>
    </div>
