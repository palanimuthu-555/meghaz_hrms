<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<style type="text/css">
   .pure-table td, .pure-table th {
   border-bottom: 1px solid #cbcbcb;
   border-width: 0 0 0 1px;
   margin: 0;
   overflow: visible;
   padding: .5em 1em;
   white-space: nowrap;
   }
   .pure-table .table td {
   vertical-align: middle !important;
   }
   .badge {
    display: inline-block;
    padding: .25em .4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: .25rem;
    color: #fff;
   }
</style>
<?php 
   ini_set('memory_limit', '-1');
   $cur = App::currencies(config_item('default_currency')); 
   $company_id = (isset($company_id)) ? $company_id : '';
   $company_details = $this->db->get_where('companies',array('co_id'=>$company_id))->row_array();

   ?> 
<div class="rep-container">
   <div class="page-header text-center">
      <h3 style="font-weight:bold!important;padding:10px 8px 10px 8px;font-size:22px;"><b><?=lang('user_report')?></b></h3>
      <?php if($role_id != NULL){ ?>
      <h5><b><span><?=lang('company_name')?>:</span>&nbsp;<?=$company_details['company_name']?>&nbsp;</b></h5>
      <?php } ?>
   </div>
   <div class="table-responsive" style="display: block;    width: 100%;    overflow-x: auto;">
     <table border="0"cellpadding="0" cellspacing="0" height="100%" width="1920px" class="inside-report pure-table">
      <thead>
         <tr>               
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?php echo lang('s_no')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('name')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('employee_type')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('email')?></b></th>
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('department')?></b></th>
            <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('designation')?></b></th>
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('joining_date')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('dob')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('marital_status')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('gender')?></b></th>   
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('terminated_date')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('relieving_date')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('salary')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('address')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('contact_number')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('emergency_contact_details')?></b></th>  
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;"><b><?=lang('total_years_months_experience')?></b></th>                      
             <th align="left" valign="middle" style="font-weight:bold!important;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;font-size:16px;" class="col-title"><b><?=lang('status')?></b></th>
         </tr>
      </thead>
      <tbody>
         <?php $i =1 ; foreach ($employees as $key => $p) { 
                    if(!empty($p['personal_info'])){
                      $personal_info = json_decode($p['personal_info']); 
                    }else{
                      $personal_info = array();
                    }
                    if(!empty($p['personal_details'])){
                      $personal_details = json_decode($p['personal_details']); 
                    }else{
                      $personal_details = array();
                    }
                    if(!empty($p['emergency_info'])){
                      $emergency_info = json_decode($p['emergency_info']); 
                    }else{
                      $emergency_info = array();
                    }

                    if(($p['doj'] != 0000-00-00)){
                      if(!empty($p['terminationdate']) || !empty($p['resignationdate'])){
                        if(!empty($p['terminationdate'])){
                          $datetime1 = new DateTime($p['doj']);
                          $datetime2 = new DateTime($p['terminationdate']);
                          $interval = $datetime1->diff($datetime2);
                          $experience = $interval->format('%y years %m months and %d days');
                        }else{
                          $datetime1 = new DateTime($p['doj']);
                          $datetime2 = new DateTime($p['resignationdate']);
                          $interval = $datetime1->diff($datetime2);
                          $experience = $interval->format('%y years %m months and %d days');
                        }
                      }else{
                        $datetime1 = new DateTime($p['doj']);
                        $datetime2 = new DateTime(date('Y-m-d'));
                        $interval = $datetime1->diff($datetime2);
                        $experience = $interval->format('%y years %m months and %d days');
                      }
                      
                    }else{
                      $experience = '-';
                    }
                     
                     // $company_name = $this->db->get_where('companies',array('co_id'=>$p['company']))->row_array(); 
                     // $users = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array();
                     // $designation = $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array();
                     // $department = $this->db->get_where('departments',array('deptid'=>$p['department_id']))->row_array();

                   if($p['status'] == 1)
                    {
                      $cls = 'active';
                      $btn_actions='Active';
                    }else{
                      $cls = 'inactive';
                      $btn_actions='Inactive';
                    }
                   
                  
                                           
                  ?> 
                  <tr style="background-color:#fff;font-family:'Open Sans',arial,sans-serif!important;">
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $i?></td>
                  <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                  <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
                    <tr>
                      <td align="left" style="font-family:'Open Sans',arial,sans-serif!important;font-size:14px;color:#333333;">
                      <?=$p['fullname']?> 
                      </td>
                    </tr>
                    <tr>
                      <td align="left" style="text-transform:capitalize;font-size:12px;color:#333;">
                      <?php echo $p['id']?>
                      </td>
                    </tr>
                  </table>
                    
                </td>
                   <!--  <td>
                     <h2 class="table-avatar">
                        <a href="<?=base_url()?>employees/profile_view/<?=$p['id']?>l" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="assets/img/profiles/avatar-03.jpg" alt="User Image"></a>
                        <a href="profile.html"> <?=$p['fullname']?> <span><?php echo $p['id']?></span></a>
                      </h2> -->
                     <!--  <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['fullname']?>
                      </a> -->
                    
                    <!-- </td> -->
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['role']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['email']?></td>                    
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['department']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['designation']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['doj']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['dob']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo isset($personal_info->marital_status)?$personal_info->marital_status:'';?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['gender']?></td>      
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['terminationdate']?></td>                    
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['resignationdate']?></td>                    
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['salary']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['address'];
                              echo isset($p['city'])?$p['city'].'<br>':'';
                              echo isset($p['state'])?$p['state'].'<br>':'';
                    ?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $p['phone']?></td>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo isset($emergency_info->contact_name1)?$emergency_info->contact_name1.'<br>':'';        echo isset($emergency_info->relationship1)?$emergency_info->relationship1.'<br>':'';
                              echo isset($emergency_info->contact1_phone1)?$emergency_info->contact1_phone1:'';
                    ?></td>
                   <!--  <td><?php echo '-'?></td> -->
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;"><?php echo $experience?></td>

                    
                    <?php 
                      switch ($p['status']) {
                        case '1': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td align="left" valign="middle" style="font-weight:400;padding:10px 8px 10px 8px;border:2px solid #9c9c9c;">
                      <span class="badge badge-<?=$label?>" style="padding:5px;border-radius:5px"><?php echo $btn_actions ?></span>
                    </td>
                   
                  </tr>
                  <?php $i++; }  ?>
      </tbody>
   </table>
 </div>
</div>
