<!-- Page Content  -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
				<!-- 	<div class="row">
						<div class="col-sm-4 col-3">
							<h4 class="page-title"><?=lang('companies')?></h4>
							<ul class="breadcrumb">
								<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
								<li class="breadcrumb-item active"><?=lang('companies')?></li>
							</ul>
						</div>
						<div class="col-sm-8 col-9 text-right m-b-0">
							<a class="btn btn-primary rounded float-right" href="<?=base_url()?>companies/create" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom">
								<i class="fa fa-plus"></i> <?=lang('new_company')?>
							</a>
							<div class="view-icons">
								<a href="<?php echo base_url(); ?>companies" class="list-view btn btn-link"><i class="fa fa-bars"></i></a>
								<a href="<?php echo base_url(); ?>companies/grid_companies" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
							</div>
						</div>
					</div> -->
					<div class="page-header">
	<div class="row">
		<div class="col-sm-4 col-12">
			<h4 class="page-title"><?=lang('companies')?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><?=lang('companies')?></li>
			</ul>
		</div>
		<div class="col-sm-8 col-12 text-right m-b-20">
			
			<?php
			if(App::is_permit('menu_clients','create'))
			{
			?>
			<a class="btn add-btn" href="<?=base_url()?>companies/create" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom">
				<i class="fa fa-plus"></i> <?=lang('new_company')?>
			</a>
			<?php
			}
			?>
			<div class="view-icons">
				<a href="<?php echo base_url(); ?>companies" class="list-view btn btn-link "><i class="fa fa-bars"></i></a>
				<a href="<?php echo base_url(); ?>companies/grid_companies" class="grid-view btn btn-link active"><i class="fa fa-th"></i></a>
			</div>
		</div>
	</div>
</div>
					<!-- /Page Title -->
					
					<!-- Search Filter -->
					<div class="row filter-row">
						<div class="col-lg-4 col-sm-6">  
							<div class="form-group form-focus">
								<label class="control-label">Company</label>
								<input type="text" id="client_name_edit" name="client_name" class="form-control floating client_submit_search_grid">
								<label id="client_name_error" class="error display-none" for="client_name">Companies field Shouldn't be empty</label>
							</div>
						</div>
						<!-- <div class="col-lg-4 col-sm-6">  
							<div class="form-group form-focus">
								<label class="control-label">Email</label>
								<input type="text" id="client_email_edit" name="client_email" class="form-control floating">
								<label id="client_email_error" class="error display-none" for="client_email">Email field Shouldn't be empty</label>
							</div>
						</div> -->
						<div class="col-lg-4 col-sm-6">  
							<a href="javascript:void(0)" id="client_search_grid" class="btn btn-primary btn-block form-control" > Search </a>  
						</div>     
                    </div>
					<!-- Search Filter -->
					
					<div class="row staff-grid-row">
						<?php
						if (!empty($companies)) {
						foreach ($companies as $client) { 
						$client_due = Client::due_amount($client->co_id);
						?>
							<div class="col-md-4 col-sm-4 col-6 col-lg-3 AllGridCompanies">
								<div class="profile-widget">
									<div class="profile-img">
										<a  href="<?php echo base_url(); ?>companies/view/<?php echo $client->co_id; ?>" class="avatar"><?php echo strtoupper($client->company_name[0]); ?></a>
										<span name="email_company" style="display: none;"><?php echo $client->company_email; ?></span>
									</div>
									<?php
								if(App::is_permit('menu_clients','delete')==true||App::is_permit('menu_clients','write')==true)
								{
								?>
									<div class="dropdown profile-action">
										<a aria-expanded="false" data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>

										<div class="dropdown-menu float-right">
											<?php if(App::is_permit('menu_clients','write')){?><a class="dropdown-item" href="#" data-toggle="modal" data-target="#edit_client"><i class="fa fa-pencil m-r-5"></i> Edit</a><?php } ?>
											<?php if(App::is_permit('menu_clients','delete')){?><a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_client"><i class="fa fa-trash-o m-r-5"></i> Delete</a><?php } ?>
										</div>

									</div>
									<?php
								}
									?>
									<h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo base_url(); ?>companies/view/<?php echo $client->co_id; ?>"><?=($client->company_name != NULL) ? $client->company_name : '...'; ?></a></h4>
									<!-- <h5 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?php echo base_url(); ?>companies/view/<?php echo $client->co_id; ?>">-</a></h5> -->
									<!-- <div class="small text-muted">-</div> -->
									<a href="<?php if(App::is_permit('menu_clients','read')){?><?=base_url()?>companies/view/<?=$client->co_id?><?php }else{ echo '#';}?>" class="btn btn-white btn-sm m-t-10">View Profile</a>
								</div>
							</div>
						<?php } } ?>
					</div>
                </div>
				<!-- /Page Content