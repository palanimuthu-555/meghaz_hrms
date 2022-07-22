<?php //echo 'sess<pre>'; print_r($this->session->userdata()); exit; ?>
 
<div class="content container-fluid">
	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h3 class="page-title">FAQ</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active">FAQ</li>
				</ul>
			</div>
			<div class="col-auto float-right ml-auto">
				<?php if($this->session->userdata('role_id') != '1' && App::is_permit('menu_faq','create')) { ?>
						<a href="#"  data-toggle="modal" data-target="#add_faq"  class="btn add-btn"><?php if($this->session->userdata('role_id') == '1' && App::is_permit('menu_faq','create')) { ?>Add FAQ <?php } else { ?> Submit Qusetion <?php } ?></a>
					<?php } else { 
					if(App::is_permit('menu_faq','create')) {
						?>

						<a href="#"  data-toggle="modal" data-target="#add_faq"  class="btn add-btn"><?php if($this->session->userdata('role_id') == '1' && App::is_permit('menu_faq','create')) { ?>Add FAQ <?php } else { ?> Submit Qusetion <?php } ?></a>
					<?php }
				} ?>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
	<?php if($this->session->userdata('role_id') == '1') { ?>
	<div class="row justify-content-center">
		<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
			<a href="<?php echo base_url()?>Faq/index/3">
			<div class="card dash-widget text-center">
				<div class="card-body">
					<div class="dash-widget-info text-center">
						<span>All Questions</span>
						<h3 class="text-primary"><?php echo $all_question->all_qustn; ?></h3>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
			<a href="<?php echo base_url()?>Faq/index/1">
			<div class="card dash-widget">
				
				<div class="card-body">
					<div class="dash-widget-info text-center">
						<span>Answered</span>
						<h3 class="text-success"><?php echo $answered_qustn->answered; ?></h3>
					</div>
				</div>
			</div>
			</a>
		</div>
		<div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
			<a href="<?php echo base_url()?>Faq/index/2">
			<div class="card dash-widget">
				<div class="card-body">
					<div class="dash-widget-info text-center">
						<span>Not Answered</span>
						<h3 class="text-info"><?php echo $not_answered_qustn->not_answered; ?></h3>
					</div>
				</div>
			</div>
		</a>
		</div>
	</div>
	<?php } ?>

	<div class="row">
		<div class="col-12">
			<form>
				<div class="form-group">
					<input type="search" name="faq_search" id="faq_search" class="form-control" value="" placeholder="Search...">
				</div>
			</form>
		</div>
	</div>
	<div class="faq-card">
		<?php $i=1; foreach($faq_details as $faq) { ?>
		<div class="card">
			<div class="card-header">
				<h4 class="card-title">
					<a class="collapsed" data-toggle="collapse" href="#collapseOne_<?php echo $i; ?>">
						<?php echo $i.'. '.$faq->question; ?>
						<span style="font-size: 12px; color: <?php echo !empty($faq->answer)?'#00e600':'#f30404'; ?>"><?php echo !empty($faq->answer)?'Answered':'Not Answered'; ?></span>
						<?php if(App::is_permit('menu_faq','write') || App::is_permit('menu_faq','delete')){?>
							<div class="dropdown dropdown-action position-absoulte d-inline-block">
								<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu dropdown-menu-right">
								<?php if(App::is_permit('menu_faq','write')){?>	
								<a class="dropdown-item" id="edit" onclick="edit('<?php echo $faq->id?>')" href="#"><i class="fa fa-pencil m-r-5"></i>Edit</a>
								<?php } ?>
								<?php if(App::is_permit('menu_faq','delete')){?>	
								<a class="dropdown-item" href="<?php echo base_url()?>deleteFaq/<?php echo $faq->id?>" data-toggle="ajaxModal"><i class="fa fa-trash m-r-5"></i>Delete</a>
								<?php } ?>
								</div>
							</div>
						<?php } ?>
					</a>
				</h4>
			</div>
			<div id="collapseOne_<?php echo $i; ?>" class="card-collapse collapse">
				<div class="card-body">
					<p><?php echo $faq->answer; ?></p>
				</div>
			</div>
		</div>
		<?php $i++; } ?>
	</div>
</div>

<div id="add_faq" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add FAQ</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'faq_submit'); echo form_open(base_url().'addFaq',$attributes); ?>
					<input type="hidden" class="form-control" name="faq_id" id="faq_id" value="">
						<div class="form-group">
							<label><?php if($this->session->userdata('role_id') == '1') { ?> Add <?php } else { ?>Type <?php } ?> Question <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="question" id="question" value="" required>
						</div>
						<?php if($this->session->userdata('role_id') == '1') { ?>
							<div class="form-group">
								<label>Answer <span class="text-danger">*</span></label>
								<textarea name="answer" id="answer" class="form-control foeditor-faq-add" placeholder=""></textarea>
							</div>
						<?php } ?>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_faq">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>