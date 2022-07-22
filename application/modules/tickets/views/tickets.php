<!-- Start -->
<div class="content">

					
	<!-- Page Header -->
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col-sm-5">
				<h4 class="page-title"><?=lang('tickets')?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item active"><?=lang('tickets')?></li>
				</ul>
			</div>
			<div class="col-sm-7 text-right m-b-20">
				<?php if(App::is_permit('menu_tickets','create'))
				{?>
				<a href="<?=base_url()?>tickets/add" class="btn add-btn mb-1"><i class="fa fa-plus"></i> <?=lang('create_ticket')?></a>
				<?php
				}
				?>
				<?php if(!User::is_client()) { ?>
				<?php if ($archive) : ?>
				<a href="<?=base_url()?>tickets" class="btn float-right add-btn m-r-10 mb-1"><?=lang('view_active')?></a>
				<?php else: ?>
				<a href="<?=base_url()?>tickets?view=archive" class="btn btn-info float-right add-btn m-r-10 mb-1"><?=lang('view_archive')?></a>
				<?php endif; ?>
				<?php } ?>
				
				<div class="btn-group float-right m-r-10 mb-1">
					<button class="btn btn-white dropdown-toggle" data-toggle="dropdown">
			  
					  <?php
					  $view = isset($_GET['view']) ? $_GET['view'] : NULL;
					  switch ($view) {
						case 'pending':
						  echo lang('pending');
						  break;
						case 'closed':
						  echo lang('closed');
						  break;
						case 'open':
						  echo lang('open');
						  break;
						case 'resolved':
						  echo lang('resolved');
						  break;

						default:
						  echo lang('filter');
						  break;
					  }
					  ?>
					</button>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=pending"><?=lang('pending')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=closed"><?=lang('closed')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=open"><?=lang('open')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets?view=resolved"><?=lang('resolved')?></a>
						<a class="dropdown-item" href="<?=base_url()?>tickets"><?=lang('all_tickets')?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
		
		
	<div class="row filter-row">
		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
			<div class="form-group form-focus ">
				<label class="control-label">Reporter</label>
				<input type="text" class="form-control floating ticket_search_submit" id="employee_name" name="employee_name" />
				<label id="employee_name_error" class="error display-none" for="employee_name">Reporter Shouldn't be empty</label>
			</div>
		</div>

		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
			<div class="form-group form-focus select-focus ">
				<label class="control-label">Status</label>
				<select class="select floating form-control" id="ticket_status" name="ticket_status"> 
					<option value=""> All Tickets</option>
					<option value="Pending"> Pending </option>
					<option value="Closed"> Closed </option>
					<option value="Open"> Open </option>
					<option value="Resolved"> Resolved </option>
				</select>
				<label id="ticket_status_error" class="error display-none" for="ticket_status">Please Select a status</label>
			</div>
		</div>
		
		<?php $priorities = $this->db->order_by('hour','DESC')->get('priorities')->result_array();?>
		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12"> 
			<div class="form-group form-focus select-focus">
				<label class="control-label">Priority</label>
				<select class="select floating form-control" id="ticked_priority" name="ticked_priority"> 
					<option value="" selected="selected"> All Priorities </option>
					<?php foreach ($priorities as $key => $value) {?>
					<option value="<?php echo $value['priority'];?>" > <?php echo ucfirst($value['priority']) ?> </option> 
					<?php } ?>
				</select>
				<label id="ticked_priority_error" class="error display-none" for="ticked_priority">Please Select a priority</label>
			</div>
		</div>
		
		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
			<div class="form-group form-focus">
				<label class="control-label">From</label>
				<div class="cal-icon"><input class="form-control floating" id="ticket_from" name="ticket_from" type="text"></div>
				<label id="ticket_from_error" class="error display-none" for="ticket_from">From Date Shouldn't be empty</label>
			</div>
		</div>
			
		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
				<div class="form-group form-focus">
				<label class="control-label">To</label>
				<div class="cal-icon">
					<input class="form-control floating" id="ticket_to" name="ticket_to" type="text">
				</div>
				<label id="ticket_to_error" class="error display-none" for="ticket_to">To Date Shouldn't be empty</label>
			</div>
		</div>
		<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">  
			<a href="javascript:void(0)" id="ticket_search_btn" class="btn btn-primary text-white btn-block"> Search </a>  
		</div> 
	</div>
	

	
	<div class="row">
		<?php
				$priorities1 = $this->db->order_by('hour','DESC')->get('priorities')->result();
					foreach ($priorities1 as $p){
						$p_color[$p->id] = $p->color;
						$p_name[$p->id] = $p->priority;
					} 

					?>
		<div class="col-md-12"> 
			<div class="table-responsive">
                <table id="table-tickets<?=($archive) ? '-archive':''?>" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th>#</th>
							<th>Ticket Number</th>
							<th><?=lang('subject')?></th>
							<?php if (User::is_admin() || User::is_staff()) { ?>
							<th><?=lang('reporter')?></th>
							<?php } ?>
							<?php if (User::is_admin() || User::is_staff()) { ?>
							<th><?=lang('assignee')?></th>
							<?php } ?>
							<th class="col-date"><?=lang('date')?></th>
							<th class="col-options no-sort"><?=lang('priority')?></th>
							<th class=""><?=lang('department')?></th>
							<th class="text-center"><?=lang('status')?></th>
							<th style="width:5px; display:none;"></th>
							<th class=" text-center"><?=lang('action')?></th>
						</tr>
					</thead>
					<tbody>
                    <?php
                        $this->load->helper('text');
                        $i=1;
                        foreach ($tickets as $key => $t) {
                        $s_label = 'default';
                        if($t->status == 'open') $s_label = 'danger';
                        if($t->status == 'closed') $s_label = 'success';
                        if($t->status == 'resolved') $s_label = 'primary';
                        if($t->status == 'pending') $s_label = 'warning';
                    ?>
                    <tr>
						<td><?=$i;?></td>
						<td><?php echo $t->ticket_code;?></td>
						<td>
							<?php $rep = $this->db->where('ticketid',$t->id)->get('ticketreplies')->num_rows();
							if($rep == 0){ ?>
							<h2>
							<a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?php if(App::is_permit('menu_tickets','read')){?><?=base_url()?>tickets/view/<?=$t->id?><?php } else { echo'#';}?>" data-toggle="tooltip" data-title="<?=lang('ticket_not_replied')?>"></h2>
							<?php }else{ ?>
							<h2><a class="text-info <?=($t->status == 'closed') ? 'text-lt' : ''; ?>" href="<?php if(App::is_permit('menu_tickets','read')){?><?=base_url()?>tickets/view/<?=$t->id?><?php } else { echo'#';}?>">
							<?php } ?>

							<?=word_limiter(ucfirst($t->subject), 8);?>
							</a></h2><br>
							<?php if($rep == 0 && $t->status != 'closed'){ ?>
							<span class="text-danger f-12">Pending for <?=Applib::time_elapsed_string(strtotime($t->created));?></span>
							<?php } ?>
						</td>
						
						<?php if (User::is_admin() || User::is_staff()) { ?>
						<td>
							<?php
							if($t->reporter != NULL){ ?>
							<h2 class="table-avatar">
								<a class="avatar avatar-xs" href="javascript:void(0);" data-toggle="tooltip" title="<?php echo User::login_info($t->reporter)->email; ?>" data-placement="right">
									<img alt="" src="<?php echo User::avatar_url($t->reporter); ?>">
								</a>
								<a href="javascript:void(0);"><?php echo ucfirst(User::displayName($t->reporter)); ?></a>
							</h2>
							<?php } else { echo "NULL"; } ?>
						</td>
						<?php } ?>
						
						<?php if (User::is_admin() || User::is_staff()) { ?>
						<td>
						<?php
							if($t->assignee != 0){ ?>
							<h2 class="table-avatar">
								<a class="avatar avatar-xs" href="javascript:void(0);" data-toggle="tooltip" title="<?php echo User::login_info($t->assignee)->email; ?>" data-placement="right">
									<img alt="" src="<?php echo User::avatar_url($t->assignee); ?>">
								</a>
								<a href="javascript:void(0);"><?php echo(!empty($t->assignee))?ucfirst(User::displayName($t->assignee)):""; ?></a>
							</h2>
							<?php } else { echo "-"; } ?>
						</td>
						<?php } ?>

						<td><?=date("D, d M g:i:A",strtotime($t->created));?><br/>
							<span class="text-primary f-12">(<?=Applib::time_elapsed_string(strtotime($t->created));?>)</span>
						</td>

						<td>
							<span class="badge badge-primary f-14 text-white" style="background: <?php echo $p_color[$t->priority]; ?>"> <?php echo ucfirst($p_name[$t->priority]);?></span>
						</td>

						<td>
							<?php 
							$department = App::get_dept_by_id($t->department);
							if(!empty($department)){echo ucfirst($department);}else{echo '-';} ?>
						</td>

						<td class="text-center">
						<?php
							switch ($t->status) {
								case 'open':
									$status_lang = 'open';
									break;
								case 'closed':
									$status_lang = 'closed';
									break;
								case 'pending':
									$status_lang = 'pending';
									break;
								case 'resolved':
									$status_lang = 'resolved';
									break;

								default:
									# code...
									break;
							}
							?>
							<span class="badge f-14 badge-<?=$s_label?>"><?=ucfirst(lang($status_lang))?></span>
						</td>
						<td style="display:none;"><?=date("m/d/Y",strtotime($t->created));?></td>                  
						<td class="text-center">
							<div class="dropdown dropdown-action">
								<a data-toggle="dropdown" class="action-icon" href="#">
									<i class="material-icons">more_vert</i>
								</a>
								<div class="dropdown-menu float-right dropdown-menu-right">

									<?php if(App::is_permit('menu_tickets','read')){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/view/<?=$t->id?>"><i class="fa fa-crosshairs m-r-5"></i><?=lang('preview_ticket')?></a><?php }?>
									
									<?php if(App::is_permit('menu_tickets','write')){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/edit/<?=$t->id?>"><i class="fa fa-pencil m-r-5"></i><?=lang('edit_ticket')?></a><?php }?>
									<?php if(App::is_permit('menu_tickets','delete')){?><a class="dropdown-item" class="" href="<?=base_url()?>tickets/delete/<?=$t->id?>" data-toggle="ajaxModal" title="<?=lang('delete_ticket')?>"><i class="fa fa-trash-o m-r-5"></i><?=lang('delete_ticket')?></a><?php }?>

									<?php if ($archive) : ?>
									<a class="dropdown-item" class="" href="<?=base_url()?>tickets/archive/<?=$t->id?>/0"><i class="fa fa-archive m-r-5" aria-hidden="true"></i><?=lang('move_to_active')?></a>
									<?php else: ?>
									<a class="dropdown-item" class="" href="<?=base_url()?>tickets/archive/<?=$t->id?>/1"><i class="fa fa-archive m-r-5" aria-hidden="true"></i> <?=lang('archive_ticket')?></a>
									<?php endif; ?>
									
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

