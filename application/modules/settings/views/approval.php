
   <?php if(isset($_GET['key']) && !empty($_GET['key']))
   {

    $active_approval = $_GET['key'];

} else {
  $active_approval = 'expense_approval';
}
 // echo $active_performance; exit();
     $designations1 = $this->db->order_by('designation','ASC')->where('id !=',1)->where('id !=',9)->get('designation')->result();
     $designations2 = $this->db->order_by('designation','ASC')->where('id',1)->or_where('id',9)->get('designation')->result();
     // echo $this->db->last_query();exit;
     $designations = array_merge($designations2,$designations1);
    // echo '<pre>';print_r($designation); exit();
    $offer_approvers = $this->db->get('offer_approver_settings')->result_array();
    if(!empty($offer_approvers)){
        $default_offer_approvals = $this->db->get_where('offer_approver_settings',array('default_offer_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_offer_approvals)){
            $default_offer_approval = 'seq-approver';
            $offer_seq_approve = 'seq-approver';
            
        }else {
            $default_offer_approval = 'sim-approver';
            $offer_sim_approve = 'sim-approver';
        }
    }else {
        $default_offer_approval = '';
    }

    $leave_approvers = $this->db->get('leave_approver_settings')->result_array();
    if(!empty($leave_approvers)){
        $default_leave_approvals = $this->db->get_where('leave_approver_settings',array('default_leave_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_leave_approvals)){
            $default_leave_approval = 'seq-approver';
            $leave_seq_approve = 'seq-approver';
            
        }else {
            $default_leave_approval = 'sim-approver';
            $leave_sim_approve = 'sim-approver';
        }
    }else {
        $default_leave_approval = '';
    }

    $expense_approvers = $this->db->get('expense_approver_settings')->result_array();
    if(!empty($expense_approvers)){
        $default_expense_approvals = $this->db->get_where('expense_approver_settings',array('default_expense_approval'=>'seq-approver'))->result_array();
        // echo "<pre>";print_r($default_offer_approvals);
        if(!empty($default_expense_approvals)){
            $default_expense_approval = 'seq-approver';
            $expense_seq_approve = 'seq-approver';
            
        }else {
            $default_expense_approval = 'sim-approver';
            $expense_sim_approve = 'sim-approver';
        }
    }else {
        $default_expense_approval = '';
    }
    // echo "<pre>";print_r($default_offer_approval);
 ?>
<?php  if(User::is_staff()) { ?>
<div class="content">
   <div class="row">
      <div class="col-3">
         <h4 class="page-title m-b-20"><?=lang('settings')?></h4>
      </div>
      <div class="col-9 m-b-0 text-right">
         <?php if($load_setting == 'templates'){  ?>
         <div class="btn-group">
            <button type="button" class="btn btn-sm btn-primary" title="Filter" data-toggle="dropdown"><i class="fa fa-cogs"></i> <?=lang('choose_template')?> <span class="caret"></span></button>
            <div class="dropdown-menu">
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=user"><?=lang('account_emails')?></a>
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=bugs"><?=lang('bug_emails')?></a>
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=project"><?=lang('project_emails')?></a>
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=task"><?=lang('task_emails')?></a>
              <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=invoice"><?=lang('invoicing_emails')?></a>
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=ticket"><?=lang('ticketing_emails')?></a>
               <span class="divider"></span>
              <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=extra"><?=lang('extra_emails')?></a>
               <a class="dropdown-item" href="<?=base_url()?>settings/?settings=templates&group=signature"><?=lang('email_signature')?></a>
            </div>
         </div>
         <?php }
            $set = array('theme','customize');
            if( in_array($load_setting, $set)){  ?>
         <?php  ?>
         <?php } ?>
         <?php $set = array('payments');
            if(in_array($load_setting, $set)){ $views = $this->input->get('view'); if($views != 'currency'){ ?>
         <a href="<?=base_url()?>settings/?settings=payments&view=currency" class="btn btn-primary btn-sm">
         <?=lang('currencies')?></a>
         <?php } }
            $set = array('system', 'validate');
            if( in_array($load_setting, $set)){  ?>
         <a href="<?=base_url()?>settings/?settings=system&view=categories" class="btn btn-primary btn-sm"><?=lang('category')?>
         </a>
         <a href="<?=base_url()?>settings/?settings=system&view=slack" class="btn btn-warning btn-sm">Slack</a>
         <a href="<?=base_url()?>settings/?settings=system&view=project" class="btn btn-info btn-sm"><?=lang('project_settings')?>
         </a>
         <a href="<?=base_url()?>settings/database" class="btn btn-success btn-sm"><i class="fa fa-cloud-download text"></i>
         <span class="text"><?=lang('database_backup')?></span>
         </a>
         <?php } ?>
         <?php if($load_setting == 'email'){  ?>
         <a href="<?=base_url()?>settings/?settings=email&view=alerts" class="btn btn-success btn-sm"><i class="fa fa-inbox text"></i>
         <span class="text"><?=lang('alert_settings')?></span>
         </a>
         <?php } ?>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-4 col-md-4 col-lg-3 col-12">
         <a class="btn btn-light d-md-none d-sm-inline-block m-r-xs m-b-20" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></a>
         <div id="setting-nav" class="card-box settings-menu hidden-xs">
            <ul>
               <?php                
                  $menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();
                  
                      $approval_menu = $this->db->get_where('hooks',array('name'=>approval_settings))->result();
                                
                                 if(User::is_admin()) {
                  foreach ($menus as $menu) { 
                                     ?>
               <li class="<?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
                  <a href="<?=base_url()?>settings/?settings=<?=$menu->route?>">
                  <i class="fa fa-fw <?=$menu->icon?>"></i>
                  <?=lang($menu->name)?>
                  </a>
               </li>
               <?php } }
                  elseif(User::is_staff())
                  {
                     foreach ($approval_menu as $app_menu) { ?>
               <li class="<?php echo ($load_setting == $app_menu->route) ? 'active' : '';?>">
                  <a href="<?=base_url()?>settings/?settings=<?=$app_menu->route?>">
                  <i class="fa fa-fw <?=$app_menu->icon?>"></i>
                  <?=lang($app_menu->name)?>
                  </a>
               </li>
               <?php } }
                  ?>
            </ul>
         </div>
      </div>
      <?php } ?>
      <?php if(User::is_staff()) { ?>
      <div class="col-sm-8 col-md-8 col-lg-9 col-12">
         <?php } ?>
         <?php
            // $expense_approvers = $this->db->get('expense_approvers')->result_array();
            
            ?>
         <div class="p-0">
            <div class="col-lg-12 p-0">
               <div class="card panel-white">
                  <div class="card-header">
                     <h3 class="card-title mb-0 p-5">Approval Setting</h3>
                  </div>
                  <div class="card-body">
                     <ul class="nav nav-tabs nav-tabs-solid m-b-20">
                        <li class="nav-item <?php echo ('expense_approval' == $active_approval )?'active':'';?>"><a class="nav-link" href="#" id='expense_approval'>Expenses Approval</a></li>                       
						      <li class="nav-item <?php echo ('leave_approval' == $active_approval )?'active':'';?>" ><a class="nav-link" href="#" id='leave_approval'>Leave Approval</a></li>
                        <li class="nav-item <?php echo ('offer_approval' == $active_approval )?'active':'';?>"><a class="nav-link" href="#" id='offer_app_view' >Offer Approval</a>
                        </li>
                        <li class="nav-item <?php echo ('resignation_notice' == $active_approval )?'active':'';?>"><a class="nav-link" href="#" id='resignation_notice' >Resignation Notice</a>
                        </li>
                     </ul>
                     <div class="content">
	                   
   <div class="div_table-expenses " style="<?php echo ('expense_approval' == $active_approval )?'':'display: none';?>">
      <?php if(User::is_admin()) { ?>
        <form action="<?php echo base_url(); ?>settings/expense_approval_settings" id="tokbox_form" class="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
               <h3>Expense Approval Settings</h3>
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group form-row">
                        <label class="control-label col-md-12">Default Expense Approval</label>
                        <div class="col-md-12 approval-option">
                            <label class="radio-inline">
                                <input id="radio-single" class="mr-2 default_expense_approval" value="seq-approver" <?php echo ($default_expense_approval =='seq-approver')?"checked":"";?> name="default_expense_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                            <label class="radio-inline ml-2">
                                <input id="radio-multiple" class="mr-2 default_expense_approval" value="sim-approver" <?php echo ($default_expense_approval =='sim-approver')?"checked":"";?> name="default_expense_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                        </div>
                    </div>
                     <div class="form-group  form-row row approver seq-approver" style="<?php echo ($expense_seq_approve =='seq-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">Expense Approvers</label>
                        
                        <div class="col-sm-9 ">
                            
                                    <!-- <select class="select form-control" name="offer_approvers" >
                                        <option>Recruiter</option>
                                        <option>Hiring Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                    </select> -->

                                <?php 
                                $expense_approvers1 = $this->db->where('default_expense_approval','seq-approver')->get('expense_approver_settings')->result_array();

                                if(!empty($expense_approvers1)){

                                    $expense_approvers_count = count($expense_approvers1);
                                    //echo count($expense_approvers);
                                    if($expense_seq_approve == 'seq-approver') { 
                                        $i=1;
                                        foreach ($expense_approvers as $expense_approver) { ?>
                                        <label class="ex_exp_approvers_<?php echo $i;?> control-label m-b-10 exp_appr" style="padding-left:0">Approver <?php echo $i;?></label>
                            <div class="row ex_exp_approvers_<?php echo $i;?>">
                                <div class="col-md-6">
                                         <select class="select2-option form-control expense_approvers "   style="width:260px" name="expense_approvers[]" > 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                    <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  <?php echo ($expense_approver['approvers'] ==$designation->id)?"selected":"";?>>
                                           <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            <?php if($i != 1){?>
                            <div class="col-md-2"><a class="remove_ex_exp_approver btn rounded border text-danger" data-id ="<?php echo $i;?>"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                        <?php } ?>
                            </div>
                                   <?php  $i++;
                               }
                                    } else { ?>
                                        <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-6">
                                     <select class="select2-option form-control expense_approvers"   style="width:260px" name="expense_approvers[]" id="expense_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                    <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  >
                                            <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                                <?php     }
                                } else{ 
                                    $expense_approvers_count = 1;
                                 ?>
                                     <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-6">
                                     <select class="select2-option form-control expense_approvers"   style="width:260px" name="expense_approvers[]" id="offer_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                     <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  >
                                            <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                              <?php   }
                                ?>
                                
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            
                            <div id="expense_approvers_items">
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id="expense_approvers_count" value="<?php echo $expense_approvers_count ;?>">
                            <div class="col-sm-9 col-md-offset-3 m-t-10">
                                <a id="add_expense_approvers" href="javascript:void(0)" class="add-more">+ Add Approver</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group form-row row approver sim-approver" style="<?php echo ($expense_sim_approve =='sim-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">Expense Approvers</label>
                        <div class="col-sm-9 ">
                            <?php if(!empty($expense_approvers)){
                                    $expense_approvers_count = count($expense_approvers);
                                    if($expense_sim_approve == 'sim-approver') { 
                                        
                                         ?>
                            <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control expense_approvers_sim" multiple="multiple" style="width:260px" name="expense_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php 
                                            foreach ($expense_approvers as $expense_approver) {
                                            foreach ($designations as $designation){ ?>
                                                <option value="<?=$designation->id?>" <?php echo ($expense_approver['approvers'] ==$designation->id)?"selected":"";?>>
                                                  <?=ucfirst($designation->designation)?>
                                                </option>
                                           
                                        <?php } } ?>
                                        </optgroup> 
                                    </select>

                                </div>

                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                                <?php  
                               
                                    } else{ ?>
                                         <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control expense_approvers_sim" multiple="multiple" style="width:260px" name="expense_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                             <?php foreach ($designations as $designation): ?>
                                                <option value="<?=$designation->id?>"  >
                                                    <?=ucfirst($designation->designation)?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>

                                   <?php  }
                                } else{ 
                                   
                                 ?>
                                  <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control expense_approvers_sim" multiple="multiple" style="width:260px" name="expense_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?=$designation->id?>"  >
                                                  <?=ucfirst($designation->designation)?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                             <?php   }
                                ?>
                        </div>
                    </div>
                     <div class="m-t-30">
                       
                        <div class="col-md-12 submit-section">
                          <button id="expense_approval_set_btn" type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                        </div>
                    </div>
        </form>
     <?php }?>
   </div>



	<div class="div_offer_app "  style="<?php echo ('offer_approval' == $active_approval )?'':'display: none';?>">
      <?php if(User::is_admin()) { ?>
        <form action="<?php echo base_url(); ?>settings/offer_approval_settings" id="tokbox_form" class="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
               <h3>Offer Approval Settings</h3>
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group">
                        <label class="control-label col-md-12">Default Offer Approval</label>
                        <div class="col-md-12 approval-option">
                            <label class="radio-inline">
                                <input id="radio-single" class="mr-2 default_offer_approval" value="seq-approver" <?php echo ($default_offer_approval =='seq-approver')?"checked":"";?> name="default_offer_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                            <label class="radio-inline ml-2">
                                <input id="radio-multiple" class="mr-2 default_offer_approval" value="sim-approver" <?php echo ($default_offer_approval =='sim-approver')?"checked":"";?> name="default_offer_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                        </div>
                    </div>

                     <div class="m-t-30">
                                              <div class="col-md-12 submit-section">
                          <button id="offer_approval_set_btn" type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                        </div>
                    </div>
        </form>
     <?php }?>
	</div>
	


</div>
<div class="leave_approval_div"  style="<?php echo ('leave_approval' == $active_approval )?'':'display: none';?>">
      <?php if(User::is_admin()) { ?>
        <form action="<?php echo base_url(); ?>settings/leave_approval_settings" id="tokbox_form" cl
          ss="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
               <h3>Leave Approval Settings</h3>
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group row">
                        <label class="control-label col-md-12">Default Leave Approval</label>
                        <div class="col-md-12 approval-option">
                            <label class="radio-inline">
                                <input id="radio-single" class="mr-2 default_offer_approval" value="seq-approver" <?php echo ($default_leave_approval =='seq-approver')?"checked":"";?> name="default_leave_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                            <label class="radio-inline ml-2">
                                <input id="radio-multiple" class="mr-2 default_offer_approval" value="sim-approver" <?php echo ($default_leave_approval =='sim-approver')?"checked":"";?> name="default_leave_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
                            </label>
                        </div>
                    </div>
                     <div class="form-group row approver seq-approver" style="<?php echo ($leave_seq_approve =='seq-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">Leave Approvers</label>
                        
                        <div class="col-sm-9 ">
                            
                                    <!-- <select class="select form-control" name="offer_approvers" >
                                        <option>Recruiter</option>
                                        <option>Hiring Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                        <option>Manager</option>
                                    </select> -->

                                <?php 
                                    $leave_approvers1 = $this->db->where('default_leave_approval','seq-approver')->get('leave_approver_settings')->result_array();

                                if(!empty($leave_approvers1)){
                                    $leave_approvers_count = count($leave_approvers1);
                                    if($leave_seq_approve == 'seq-approver') { 
                                        $i=1;
                                        foreach ($leave_approvers as $leave_approver) { ?>
                                        <label class="ex_leave_approvers_<?php echo $i;?> control-label m-b-10 leave_appr" style="padding-left:0">Approver <?php echo $i;?></label>
                            <div class="row ex_leave_approvers_<?php echo $i;?>">
                                <div class="col-md-6">
                                         <select class="select2-option form-control leave_approvers "   style="width:260px" name="leave_approvers[]" > 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                    <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  <?php echo ($leave_approver['approvers'] ==$designation->id)?"selected":"";?>>
                                            <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            <?php if($i != 1){?>
                            <div class="col-md-2"><a class="remove_ex_leave_approver btn rounded border text-danger" data-id ="<?php echo $i;?>"><i class="fa fa-times" aria-hidden="true"></i></a></div>
                        <?php } ?>
                            </div>
                                   <?php  $i++;
                               }
                                    } else { ?>
                                        <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-6">
                                     <select class="select2-option form-control leave_approvers"   style="width:260px" name="leave_approvers[]" id="leave_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                    <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  >
                                            <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                                <?php     }
                                } else{ 
                                    $leave_approvers_count = 1;
                                 ?>
                                     <label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
                            <div class="row">
                                <div class="col-md-6">
                                     <select class="select2-option form-control leave_approvers"   style="width:260px" name="leave_approvers[]" id="leave_approvers"> 
                                <optgroup>Select Approvers</optgroup> 
                                    <option value="">Select Approvers</option>
                                   <?php foreach ($designations as $designation): ?>
                                        <option value="<?=$designation->id?>"  >
                                            <?=ucfirst($designation->designation)?>
                                        </option>
                                    <?php endforeach ?>
                                </optgroup> 
                            </select>
                            </div>
                            </div>
                              <?php   }
                                ?>
                                
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            
                            <div id="leave_approvers_items">
                            </div>
                        </div>
                        <div class="row">
                            <input type="hidden" id="leave_approvers_count" value="<?php echo $leave_approvers_count ;?>">
                            <div class="col-sm-9 col-md-offset-3 m-t-10">
                                <a id="add_leave_approvers" href="javascript:void(0)" class="add-more">+ Add Approver</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row approver sim-approver" style="<?php echo ($leave_sim_approve =='sim-approver')?"display: block;":"";?>">
                        <label class="control-label col-sm-3">leave Approvers</label>
                        <div class="col-sm-9 ">
                            <?php if(!empty($leave_approvers)){
                                    $leave_approvers_count = count($leave_approvers);
                                    if($leave_sim_approve == 'sim-approver') { 
                                        
                                         ?>
                            <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control leave_approvers_sim" multiple="multiple" style="width:260px" name="leave_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php 
                                            foreach ($leave_approvers as $leave_approver) {
                                            foreach ($designations as $designation){ ?>
                                                <option value="<?=$designation->id?>" <?php echo ($leave_approver['approvers'] ==$designation->id)?"selected":"";?>>
                                                    <?=ucfirst($designation->designation)?>
                                                </option>
                                           
                                        <?php } } ?>
                                        </optgroup> 
                                    </select>

                                </div>

                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                                <?php  
                               
                                    } else{ ?>
                                         <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control leave_approvers_sim" multiple="multiple" style="width:260px" name="leave_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                             <?php foreach ($designations as $designation): ?>
                                                <option value="<?=$designation->id?>"  >
                                                    <?=ucfirst($designation->designation)?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>

                                   <?php  }
                                } else{ 
                                   
                                 ?>
                                  <label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="select2-option form-control leave_approvers_sim" multiple="multiple" style="width:260px" name="leave_approvers_sim[]" > 
                                        <optgroup label="Staff">
                                            <?php foreach ($designations as $designation): ?>
                                                <option value="<?=$designation->id?>"  >
                                                  <?=ucfirst($designation->designation)?>
                                                </option>
                                            <?php endforeach ?>
                                        </optgroup> 
                                    </select>

                                </div>
                                <!-- <div class="col-md-2">
                                    <span class="m-t-10 badge btn-success">Approved</span>
                                </div> -->
                            </div>
                             <?php   }
                                ?>
                        </div>
                    </div>
                     <div class="m-t-30 ">
                        
                        <div class="col-md-12 submit-section">
                          <button id="leave_approval_set_btn" type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                        </div>
                    </div>
        </form>
     <?php }?>
   </div>

   <div class="div_resignation_notice"  style="<?php echo ('resignation_notice' == $active_approval )?'':'display: none';?>">
      <?php if(User::is_admin()) {
        $resignation_notice = $this->db->get('resignation_notice')->row_array();
        if(!empty($resignation_notice)){          
          $email_notification = explode(',', $resignation_notice['email_notification']);
          $notice_days = $resignation_notice['notice_days'];
        }else{
          $email_notification = array();
          $notice_days = '';
        }
       ?>
        <form action="<?php echo base_url(); ?>settings/resignation_notice" id="tokbox_form" class="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
               <h3>Resignation Notice</h3>
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group">
                        <label class="control-label col-md-6">Email Notification <span class="text-danger">*</span></label></label>
                        <div class="col-md-6 approval-option">
                           <select class="select2-option form-control email_notification" multiple="multiple" style="width:100%" name="email_notification[]" > 
                                <optgroup label="Staff">                                  
                                <?php foreach (User::team() as $user){ ?>
                                    <option value="<?=$user->id?>" <?php if(in_array($user->id, $email_notification)){ echo "selected";}?> >
                                        <?=ucfirst(User::displayName($user->id))?>
                                    </option>
                                <?php } ?>
                                </optgroup> 
                          </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-6">Notice Days <span class="text-danger">*</span></label></label>
                        <div class="col-md-6 approval-option">
                         <input type="number" name="notice_days" class="form-control notice_days" value="<?php echo $notice_days;?>">
                        </div>
                    </div>

                     <div class="m-t-30">
                                              <div class="col-md-12 submit-section">
                          <button id="resignation_notice_set_btn" type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                        </div>
                    </div>
        </form>
     <?php }?>
  </div>

                  </div>
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>