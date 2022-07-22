<?php 
$cur = App::currencies(config_item('default_currency')); 
// $task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
// $project_id = (isset($task_id)) ? $task_id : '';
// $task_progress = (isset($task_progress)) ? $task_progress : '';
$user_id = (isset($user_id)) ? $user_id : '';

?>

<div class="content">
  <!-- Page Header -->
          <div class="page-header">
            <div class="row">
              <div class="col-sm-8">
                 <h3 class="reports-headerspacing page-title"><?=lang('employee_report')?></h3>
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                  <li class="breadcrumb-item active">Employee Reports</li>
                </ul>
              </div>
               <div class="col-sm-4 text-right">
                  <div class="btn-group">
            <button class="btn btn-light dropdown-toggle m-b-10" data-toggle="dropdown"><?=lang('export')?></button>
           
            <div class="dropdown-menu export" style="left:auto; right:0px !important; min-width: 93px !important">  
              <li>
                <form method="post" action="">
                    <input type="hidden" class="form-control" name = "pdf" value="1">
                    
                    <input type="hidden" class="form-control department_id_excel" name = "department_id" value="<?php echo (isset($_POST['department_id']) && !empty($_POST['department_id']))?$_POST['department_id']:'';?>">
                    <input type="hidden" class="form-control designation_id_excel" name = "designation_id" value="<?php echo (isset($_POST['designation_id']) && !empty($_POST['designation_id']))?$_POST['designation_id']:'';?>">
                    
                    <input type="hidden" class="form-control user_id_excel" name = "user_id" value="<?php echo (isset($_POST['user_id']) && !empty($_POST['user_id']))?$_POST['user_id']:'';?>">
                   
                    <button class=" btn  btn-block" type="submit" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-pdf-o m-r-5"></i></span> <span><?=lang('pdf')?></span></button>
                     <!-- <a href="#" class="pull-right" id="attendance_report_pdf1" type="submit"> -->
                     
                      <!-- </a> -->
                </form>
               
              </li>

            
              <li>
                <?php  $report_name = lang('employee_report');?>
                 <button class="btn  btn-block " onclick="employee_report_excel('<?php echo $report_name;?>','employee_report_excel');" style="text-align: left;"> <span style="font-size: 18px;text-align: left;"><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
              </li>
            </div>
          </div>
          <?=$this->load->view('report_header');?>
               </div>
            </div>
          </div>
          

      <div class="fill body reports-top rep-new-band">
        <div class="criteria-container fill-container hidden-print border-0">
          <div class="criteria-band p-0">
             <?php echo form_open(base_url().'reports/view/employee_report'); ?>
            <div class="row">
           

          
              <div class="col-md-3">
                <div class="form-group select-focus form-focus">
                <label class="control-label"><?=lang('name')?> </label>
                <select class="select2-option form-control floating" name="user_id" >
                    <optgroup label="<?=lang('name')?>">
                      <option value="0">All</option>
                        <?php 

                        $this->db->select('*');
                        $this->db->join('account_details','account_details.user_id = users.id');   
                        $this->db->where('users.role_id', 3);
                        $users = $this->db->get('users')->result(); 

                        foreach ($users as $c): ?>
                            <option value="<?=$c->user_id?>" <?=($user_id == $c->user_id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->fullname)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div></div>

              <div class="col-md-3">
                <div class="form-group select-focus form-focus">
                <label class="control-label"><?=lang('department')?> </label>
                <select class="select2-option form-control floating" name="department_id" >
                    <optgroup label="<?=lang('department')?>">
                      <option value="0">All</option>
                        <?php 
                        $department = $this->db->get_where('departments')->result();

                        foreach ($department as $c): ?>
                            <option value="<?=$c->deptid?>" <?=($department_id == $c->deptid) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->deptname)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
</div>
              <div class="col-md-3">
                <div class="form-group select-focus form-focus">
                <label class="control-label"><?=lang('designation')?> </label>
                <select class="select2-option form-control floating" name="designation_id" >
                    <optgroup label="<?=lang('designation')?>">
                      <option value="0">All</option>
                        <?php 
                        $designation = $this->db->get_where('designation')->result();

                        foreach ($designation as $c): ?>
                            <option value="<?=$c->id?>" <?=($designation_id == $c->id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->designation)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
                
</div>

              <div class="col-md-2">  
                <label></label>
                <button class="btn btn-success mt-0" type="submit">
                  <?=lang('run_report')?>
                </button>
              </div>



            </div>
          </div>
        </div>


        <?php  form_close(); ?>

        <div class="rep-container">
         <!--  <div class="page-header text-center">
            <h3 class="reports-headerspacing"><?=lang('employee_report')?></h3>
            <!-- <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?> -->
                   <!-- </div> -->

        <div class="fill-container row">


          <div class="col-md-12">
                <div class="table-responsive">  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <!-- <th style="width:5px; display:none;"></th> -->
                    <!-- <th><b><?=lang('employee_id')?></b></th> -->  
                    <th><b><?=lang('name')?></b></th>  
                    <th><b><?=lang('employee_type')?></b></th>  
                    <th><b><?=lang('email')?></b></th>
                    <th><b><?=lang('department')?></b></th>
                    <th><b><?=lang('designation')?></b></th>
                    <th><b><?=lang('joining_date')?></b></th>  
                    <th><b><?=lang('dob')?></b></th>  
                    <th><b><?=lang('marital_status')?></b></th>  
                    <th><b><?=lang('gender')?></b></th>  
                    <th><b><?=lang('terminated_date')?></b></th>  
                    <th><b><?=lang('relieving_date')?></b></th>  
                    <th><b><?=lang('salary')?></b></th>  
                    <th><b><?=lang('address')?></b></th>  
                    <th><b><?=lang('contact_number')?></b></th>  
                    <th><b><?=lang('emergency_contact_details')?></b></th>  
                   <!--  <th><b><?=lang('salary_hike')?></b></th>   -->
                    <th><b><?=lang('total_years_months_experience')?></b></th>                      
                    <th class="col-title "><b><?=lang('status')?></b></th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($employees as $key => $p) { 
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
                  <tr >
                   <!--  <td><?php echo $p['id']?></td> -->
                    <td>
                     <h2 class="table-avatar">
                        <a href="<?=base_url()?>employees/profile_view/<?=$p['id']?>" class="avatar avatar-sm mr-2"><img class="avatar-img rounded-circle" src="assets/img/profiles/avatar-03.jpg" alt="User Image"></a>
                        <a href="<?=base_url()?>employees/profile_view/<?=$p['id']?>" class="text-primary"> <?=$p['fullname']?> <span><?php echo $p['id']?></span></a>
                      </h2>
                     <!--  <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['fullname']?>
                      </a> -->
                    
                    </td>
                    <td class="text-capitalize"><?php echo $p['role']?></td>
                    <td class="text-info"><?php echo $p['email']?></td>                    
                    <td class="text-capitalize"><?php echo $p['department']?></td>
                    <td class="text-capitalize text-warning"><?php echo $p['designation']?></td>
                    <td><?php echo $p['doj']?></td>
                    <td><?php echo $p['dob']?></td>
                    <td class="text-capitalize"><?php echo isset($personal_info->marital_status)?$personal_info->marital_status:'';?></td>
                    <td class="text-capitalize"><?php echo $p['gender']?></td>
                    <td><?php echo $p['terminationdate']?></td>                    
                    <td><?php echo $p['resignationdate']?></td>                    
                    <td><?php echo $p['salary']?></td>
                    <td class="text-capitalize"><?php echo $p['address'];
                              echo isset($p['city'])?$p['city'].'<br>':'';
                              echo isset($p['state'])?$p['state'].'<br>':'';
                    ?></td>
                    <td><?php echo $p['phone']?></td>
                    <td><?php echo isset($emergency_info->contact_name1)?$emergency_info->contact_name1.'<br>':'';        echo isset($emergency_info->relationship1)?$emergency_info->relationship1.'<br>':'';
                              echo isset($emergency_info->contact1_phone1)?$emergency_info->contact1_phone1:'';
                    ?></td>
                   <!--  <td><?php echo '-'?></td> -->
                    <td><?php echo $experience?></td>

                    
                    <?php 
                      switch ($p['status']) {
                        case '1': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td>
                      <span class="badge badge-<?=$label?>"><?php echo $btn_actions ?></span>
                    </td>
                   
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


     