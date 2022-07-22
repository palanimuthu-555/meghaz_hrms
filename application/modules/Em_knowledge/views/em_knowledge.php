<div class="content container-fluid">
					<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h3 class="page-title">Knowledgebase</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active">Knowledgebase</li>
				</ul>
			</div>
			
		</div>
	</div>
	<!-- /Page Header -->
	<div class="row">
		<div class="col-12">
			<form>
				<div class="form-group">
					<input type="search" name="search" id="search" class="form-control" placeholder="Search Topic...">
				</div>
			</form>
		</div>
	</div>
	<input type="hidden" name="isSearch" id="isSearch" class="form-control" value="0">
	<div class="row" id="knowledge_details">
		<?php if($knowledge_details) { 
				foreach($knowledge_details as $knowledge) { 
					$category_details = $this->db->get_where('category',array('id'=>$knowledge->category))->row_array();
					?>
					<div class="col-xl-4 col-md-6 col-sm-6">
						<div class="topics">
							<h3 class="topic-title"><a href="#"><i class="fa fa-folder-o"></i> <?php echo ucfirst($category_details['category_name']);?> <span><?php echo ($knowledge->views)?$knowledge->views:''; ?></span></a></h3>
							<ul class="topics-list">
								<?php $topics = $this->db->select('*')->get_where('ad_knowledge_topic', array('knowledge_id'=>$knowledge->id))->result();
									foreach ($topics as $key => $value) { ?>
										<li><a href="<?php echo base_url();?>em_knowledge/em_knowledge_view/<?php echo $knowledge->id;?>"> <?php echo $value->topic;?></a>
										</li>
									<?php } ?>
							</ul>
						</div>
					</div>
			<?php } 
			} else { ?>
				<div class="col-md-12 col-sm-12">
					<div class="topics" style="text-align:center;">Details Not found</div>
				</div>
			<?php }	?>
	
	</div>
</div>