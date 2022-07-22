<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>

<div class="content">
	<div class="page-header">
	<div class="row">
		<div class="col-sm-6 col-3">
			<h4 class="page-title"><?=lang('calendar')?></h4>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
				<li class="breadcrumb-item active"><?=lang('calendar')?></li>
			</ul>
		</div>
		<div class="col-sm-6 col-9 text-right m-b-0">
			<?php $all_routes = $this->session->userdata('all_routes');
            
            foreach($all_routes as $key => $route){
                if($route == 'calendar'){
                    $routname = calendar;
                } 
                
            }
        
        if(User::is_admin()) : ?>
			<a href="<?=base_url()?>calendar/settings" data-toggle="ajaxModal" class="btn btn-primary add-btn float-right d-inline-block view-btn1"><i class="fa fa-cog"></i> <?=lang('calendar_settings')?></a>
			<?php endif; ?>
			<?php 

			if(App::is_permit('menu_calendar','create'))
			{?>
			<a id="eventAddCal" href="<?=base_url()?>calendar/add_event" data-toggle="ajaxModal" class="btn btn-primary add-btn d-inline-block float-right view-btn1"><i class="fa fa-calendar-plus-o"></i> <?=lang('add_event')?></a>

			<?php } ?>
		</div>
	</div>
</div>
	<?php
	 if(!empty($routname)){ ?>
	<div class="row">
	<div class="col-md-12">
	<div class="card-box m-b-0">
			<div id="calendar"></div>
		</div>
		</div>
	</div>
	<?php } ?>
</div>



