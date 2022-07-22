<?php
$jtype=array();
 foreach ($offer_jobtype as $key => $value) {
   $jtype[$value->id] = $value->job_type;
 }
?>
      <div class="content ">
         <div class="page-header">
          <div class="row">
            <div class="col-12">
              <h4 class="page-title"> Offer Approvals Request
                  <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                    <li class="breadcrumb-item active">Offer Approvals Request</li>
                </ul>
              </h4>
            </div>
           <!--  <div class="col-4 text-right m-b-30">
              <a href="#" class="btn btn-primary rounded float-right" data-toggle="modal" data-target="#add_job"><i class="fa fa-plus"></i> Add New Job</a>
            </div> -->
          </div>
        </div>
       
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table id="table-offer_approval_request" class="table table-striped custom-table m-b-0">
                  <thead>
                    <tr>
                      <th >#</th>
                      <th>Name</th>
                      <th class="text-left">Title</th>
                      <th class="text-left">Job Type </th> 
                      <th class="text-right">Pay </th> 
                      <th class="text-right" title='Annual Incentive plan'>Annual IP </th>
                      <th  class="text-right" title='Long Term Incentive plan'>Long Term IP</th>
                      <!-- <th>Vacation</th> -->
                      <th class="text-left">Status</th>
                      <!-- <th>Resume</th> -->
					   <?php
						if(App::is_permit('menu_offer_approval','read')==true||App::is_permit('menu_offer_approval','write')==true|| App::is_permit('menu_offer_approval','delete')==true)
						{
						?>
						<th class="text-center">Action</th>
						<?php
						}
						?>
					<!--   <th>Applicants</th>
                      <th class="text-right">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    $curren_type = array();
                     foreach ($currencies as $curren){ 
                      $curren_type[$curren->code] = $curren->symbol;
                     }
                   //  print_r($curren_type);
                      $plan_percent = array();
                      foreach (User::get_annual_incentive_plans() as $plans =>$plan){
                       $plan_percent[$plan['id']] = ucfirst(trim($plan['plan']));
                      } 
                      $vocation_name = array();
                       foreach (User::get_vocations() as $vocations =>$vocation){
                        $vocation_name[$vocation['id']] = ucfirst(trim($vocation['vocation']));
                       }
                    $i=1;
                     foreach ($candi_list as $ck => $cv) {

                         $s_label = 'Requested';$s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='success'; $color='#b31109';
                          $clr_class='warning'; $title="Click to Approve";
                           if($cv->approver_status == 1) { 
							$s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
                          $clr_class='danger'; $title="Click to Approve";
                           }
                        if($cv->approver_status == 2) { 
                          $s_label = 'Approved'; $s_label2 = '<i class="fa fa-thumbs-o-down" aria-hidden="true"></i>'; $clr_class='success';
                        $title="Click to Not Approve"; $class='warning';$color='#056928';}

                      if($this->session->userdata('role_id')==1)
						{
							$rejected = $this->db->where(array('status'=>1,'offer'=>$cv->id))->get('dgt_offer_approvers')->num_rows();
							if($rejected >0)
							{
							$s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
							$clr_class='danger'; $title="Click to Approve";

							}
						}

                        /*if($cv->status == 3) $s_label = 'Send offer';
                        if($cv->status == 4) $s_label = 'Offer accepted';
                        if($cv->status == 5) $s_label = 'Declined';
                        if($cv->status == 6) $s_label = 'Onboard';*/
                    ?>
                    <tr>

                       <td ><?=$i;?></td>
                      <td><a href="<?php if(App::is_permit('menu_offer_approval','read')){?><?php echo base_url('offers/offer_view/').$cv->id; ?><?php }else{ echo '#';}?>" class =""><?=ucfirst($cv->candidate) ?></a></td>

                     
                      
                      <td class="text-left"><?=$cv->title?></td>
                      <td class="text-left"><?=ucfirst($jtype[$cv->job_type]) ?></td>                      
                      <td class="text-right"><?=$cv->salary; ?>  <?php echo $curren_type[$cv->currency_type];?></td>
                       <td class="text-right"><?php if(isset($plan_percent[$cv->annual_incentive_plan])){
                        echo $plan_percent[$cv->annual_incentive_plan];
                      }else{ echo "No";};?></td>
                      <td class="text-right"><?=ucfirst(($cv->long_term_incentive_plan=='on')?'Yes':'No') ?></td>
                      <!-- <td><?php if(isset($vocation_name[$cv->vacation])) { echo $vocation_name[$cv->vacation];}else{ echo "-";}?></td> -->

                        <td ><label class="badge bg-inverse-<?php echo $clr_class;?>" style='display: inline-block;min-width: 90px;'><?=ucfirst($s_label)?></label></td>
                      <!-- <td> <a href="<?= base_url().''.$cv->file_path.'/'.$cv->filename ?>" target='_blank' download>Download</td> -->
                    <!--   <td title="<?php echo $title; ?>">
                        <button data-status='<?=$cv->approver_status?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>' type="button" class="btn btn-<?=$class?> status_changebuttons"><?=$s_label2?></button></td> -->

                      
						<td class="text-center">
						<div class="dropdown">
						<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu float-right">

						<a href="javascript:void(0)" data-status='<?php echo 1;?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>'class="status_changebuttons dropdown-item"  title="<?php echo "Approve offer"?>"><i class="fa fa-thumbs-o-up m-r-5" aria-hidden="true"></i>
						<?=lang('approve')?></a>


						<a href="javascript:void(0)" data-status='<?php echo 2;?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>'class ="status_changebuttons dropdown-item"><i class="fa fa-ban m-r-5" aria-hidden="true"></i> <?=lang('reject')?></a>

						<a href="<?php echo base_url('offers/offer_view/').$cv->id; ?>" class =" dropdown-item"><i class="fa fa-eye m-r-5" aria-hidden="true"></i><?=lang('view_offer')?></a>


						</div>
						</div>


						</td>
						
                    </tr>
                      <?php $i++; } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
              
           </div>