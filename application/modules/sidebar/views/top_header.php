<div class="header-<?=config_item('top_bar_color')?> header">
	<div class="header-left">
		<a class="logo" href="<?=base_url()?>">
			<?php $display = config_item('logo_or_icon'); ?>
		<?php if ($display == 'logo' || $display == 'logo_title') { ?>
			<img src="<?=base_url()?>assets/images/<?=config_item('company_logo')?>" class="img-fluid">
		<?php } ?>
		</a>
	</div>
	
	<a id="toggle_btn" href="javascript:void(0);">
		<span class="bar-icon">
			<span></span>
			<span></span>
			<span></span>
		</span>
	</a>
	
	<div class="page-title-box float-left">
		<h3><?=config_item('company_name')?>
			<!-- <?php 
			if ($display == 'logo_title') {
				if (config_item('website_name') == '') { echo config_item('company_name'); } else { echo config_item('website_name'); }
			} ?> -->
		</h3>
	</div>
	<a href="#nav" class="mobile_btn float-left" id="mobile_btn"><i aria-hidden="true" class="fa fa-bars"></i></a>
	<ul class="navbar-nav navbar-right nav-user float-right flex-row user-menu">
		<?php $role = User::login_role_name(); 
		$user_id = $this->session->userdata('user_id');
		$dept_id = $this->db->get_where('dgt_users',array('id'=> $user_id))->row_array();
		$department_id = $dept_id['department_id'];
		$last_login = $dept_id['last_login'];
		$lastseen = config_item('last_seen_activities');

		$get_activities = $this->db->select('*')
						  ->from('dgt_activities')
						  ->where('activity_date >',date("Y-m-d H:i:s",$lastseen))
						  // ->where('user',$user_id)
						  ->group_start()
						  ->or_where("FIND_IN_SET('".$department_id."', value2)")
						  ->or_where('value2','00')
						  ->or_where('user',$user_id)
						  ->group_end()
						  ->get()->result_array();
		// echo ($this->db->last_query()); exit()	;
		?>
		<!-- search -->
		<li class="nav-item search-li">
			<div class="top-nav-search">
				<a href="javascript:void(0);" class="responsive-search">
					<i class="fa fa-search"></i>
			   </a>
				<form action="<?php echo base_url() . 'employees/search_emp_profile' ?>" class="bs-example"  enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
					<input class="form-control" type="text" name="search" placeholder="Search here">
					<button class="btn" type="submit"><i class="fa fa-search"></i></button>
				</form>
			</div>
		</li>
		<!-- search -->

		<!-- Flag -->
		<?php if(config_item('enable_languages') == 'TRUE'){  ?>
		<?php $set_lang = ($this->session->userdata('lang'))?$this->session->userdata('lang'):'english';
		
		?>
		<li class="nav-item dropdown has-arrow flag-nav">
			<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
				<?php foreach (App::languages() as $lang) { if ($lang->name == $set_lang) { ?>
				<img src="<?=base_url()?>assets/img/flags/<?=$lang->icon?>.png" alt="" height="20"> <span><?php echo  ucfirst($set_lang);?></span>
				<?php } } ?>
			</a>
			<div class="dropdown-menu dropdown-menu-right">
				<?php foreach (App::languages() as $lang) { if ($lang->active == 1) { ?>

                    

                        <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>" class="dropdown-item">

                            <img src="<?=base_url()?>assets/img/flags/<?=$lang->icon?>.png" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  height="16"/> <?=ucwords(str_replace("_"," ", $lang->name))?>

                        </a>

                    

                    <?php } }  ?>
				<!-- <a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/us.png" alt="" height="16"> English
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/fr.png" alt="" height="16"> French
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/es.png" alt="" height="16"> Spanish
				</a>
				<a href="javascript:void(0);" class="dropdown-item">
					<img src="<?=base_url()?>assets/img/flags/de.png" alt="" height="16"> German
				</a> -->
			</div>
		</li>
	<?php } ?>
		<!-- flag -->
		<?php  if(App::is_access('menu_activities'))
		{ ?>
			<li class="nav-item">
				<a class="nav-link" id="user-activities v" href="<?=base_url()?>profile/activities">
				<?php if ($role == 'admin') {
					$lastseen = config_item('last_seen_activities');
					$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
					$act = count($activities);
					$badge = 'bg-purple';
					if ($act == 0) $badge = 'bg-purple';
				  ?>
				 <span class="rounded-circle badge <?=$badge;?> float-right"><?=$act;?></span>
				<i class="fa fa-bell-o"></i><?php } elseif($role == 'staff') 
				{ 

				$act_staff = count($get_activities);
				$badge_staff = 'bg-purple';
				if ($act_staff == 0) $badge_staff = 'bg-purple';
				?><span class="badge <?=$badge_staff;?> float-right"><?=$act_staff;?></span><i class="fa fa-bell-o"></i><?php } ?>
				</a>
			</li>
		<?php } ?>
		<?php  if(App::is_access('menu_chats'))
		{ ?>
			<li class="hidden-xs nav-item">
				<a class="nav-link" href="javascript:;" data-toggle="sidebar-chat" onclick="show_user_sidebar()">
					<i class="fa fa-comment-o"></i>
				</a>
		   </li>
        <?php } ?>
		
		<?php // foreach ($timers as $timer) : if ($role == 'admin' || ($role == 'staff' && User::get_id() == $timer['user'])) : ?>
			<?php //	$type = (isset($timer['task'])) ? 'task' : 'project'; 
					//$title = (isset($timer['task'])) ? Project::view_task($timer['task'])->task_name : Project::by_id($timer['project'])->project_title;
					//$id = (isset($timer['task'])) ? $timer['pro_id'] : $timer['project']; 
			?> 
			<!-- <li class="timer hidden-xs" start="<?php //echo $timer['start_time']; ?>">
				<a title="<?php //echo lang($type).": ".$title.' by '.User::displayName($timer['user']); ?>" data-placement="bottom" data-toggle="tooltip" class="dker" href="<?php //echo site_url('projects/view/'.$id).($type == 'task' ? '?group=tasks':'');  ?>">
					<img src="<?php //echo User::avatar_url($timer['user']); ?>" class="rounded">
					<span></span>
				</a>
			</li> -->
		<?php // endif; endforeach; ?>
		<?php $up = count($updates); ?>
		<li class="dropdown main-drop nav-item">
			<a href="#" class="nav-link user-link" data-toggle="dropdown">
				<span class="user-img">
					<?php
					$user = User::get_id();
					$user_email = User::login_info($user)->email;
					?>
					<img src="<?php echo User::avatar_url($user);?>" class="rounded-circle" width="40">
				</span>
				<span><?php echo User::displayName($user);?></span>
				<b class="caret"></b>
			</a>
			<div class="dropdown-menu">
				<?php if(($role != 'admin') && ($role != 'superadmin') && (App::is_access('menu_employees'))){ ?>
					<a class="dropdown-item" href="<?=base_url()?>employees/profile_view/<?php echo $this->session->userdata('user_id'); ?>">My Profile</a>
				<?php } ?>
				<a class="dropdown-item" href="<?=base_url()?>profile/settings"><?=lang('account_settings')?></a>
				 <a class="dropdown-item" href="<?=base_url()?>logout" ><?=lang('logout')?></a> 
			</div>
		</li>
	</ul>
	
            <div class="dropdown mobile-user-menu float-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v text-white"></i></a>
                <div class="dropdown-menu float-right dropdown-menu-right">
                  <a class="dropdown-item" href="<?=base_url()?>profile/settings"><?=lang('settings')?></a>
				
					<a class="dropdown-item position-relative" id="user-activities" href="<?=base_url()?>profile/activities">
					<?php if ($role == 'admin') {
						$lastseen = config_item('last_seen_activities');
						$activities = $this->db->where('activity_date >',date("Y-m-d H:i:s",$lastseen))->get('activities')->result();
						$act = count($activities);
						$badge = 'bg-danger';
						if ($act == 0) $badge = 'bg-success';
					?>
					 <span class="badge <?=$badge;?> float-right"><?=$act;?></span>
					<?php } ?><?=lang('activities')?>
					</a>
				
				 <a class="dropdown-item" href="<?=base_url()?>logout" ><?=lang('logout')?></a> 
                </div>
            </div>
	
</div>
<?php
$chat_qry      =  "SELECT ad.*,u.* 
                   FROM dgt_users u  
				   left join dgt_account_details as ad on ad.user_id = u.id
				   WHERE u.activated = 1 and u.id != ".$this->tank_auth->get_user_id().""; 
$chat_users 	= $this->db->query($chat_qry)->result();
//$chat_users         = $this->user_model->users();
//$chat_user_roles    = $this->user_model->roles();
?>
<div class="notification-box chat_user_sidebar">
	<div class="chat-search" style="height: auto">
		<input type="text" placeholder="Search" class="form-control" onkeyup="filter_chat_user(this.value);">
	</div>
	<ul class="chat-contacts list-box" id="chat-contacts_list">
	<?php 
		if (!empty($chat_users)) {
		foreach ($chat_users as $key => $chat_user) {?>
		<li class="online" onclick="get_users_chats(<?=$chat_user->user_id?>,0);">
			<a href="javascript:;">
				<div class="list-item">
					<div class="list-left">
						<?php 
						if(config_item('use_gravatar') == 'TRUE' AND 
							Applib::get_table_field(Applib::$profile_table,
								array('user_id'=>$chat_user->user_id),'use_gravatar') == 'Y'){
								   $user_email = Applib::login_info($chat_user->user_id)->email; 
						?>
						<img width="38" src="<?=$this -> applib -> get_gravatar($user_email)?>" class="avatar">
						<?php }else{  $pro_pic = Applib::profile_info($chat_user->user_id)->avatar; if($pro_pic != ''){ $pro_pic = $pro_pic; }else{ $pro_pic = 'default_avatar.jpg'; } ?>
						<img width="38" src="<?=base_url()?>assets/avatar/<?php echo $pro_pic; ?>" class="avatar">
						<?php } ?>
					 </div>
					<div class="list-body">
						<div class="message-author"><a href="<?=base_url()?>chats"> <?=$chat_user->fullname?></a> </div>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</li>
	 <?php 
		}
	}?>    
	</ul>
</div> 
<div class="chat-window-container" id="chat-window-container">

</div>
