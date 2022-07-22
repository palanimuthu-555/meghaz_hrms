<style type="text/css">
.modal:nth-of-type(even) {
    /*z-index: 1052 !important;*/
}
.modal-backdrop.show:nth-of-type(even) {
    /*z-index: 1051 !important;*/
}
</style>

<?php //echo 'det<pre>'; print_r($knwldge_data); exit; ?>
<div class="content container-fluid">
	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h3 class="page-title">Knowledgebase</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>ad_knowledge">Knowledgebase</a></li>
				</ul>
			</div>
			<?php if(App::is_permit('menu_knowledgebase','write') || App::is_permit('menu_knowledgebase','delete')){?>
			<div class="col-auto float-right ml-auto">
				<div class="dropdown dropdown-action position-absoulte d-inline-block">
					<a href="#" class="btn add-btn" data-toggle="dropdown" aria-expanded="false">Manage</a>
					<div class="dropdown-menu dropdown-menu-right">
						<?php if(App::is_permit('menu_knowledgebase','write')){?>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#add_know_<?php echo $id; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
					<?php } ?>
					<?php if(App::is_permit('menu_knowledgebase','delete')){?>
						<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_know_<?php echo $id; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
					<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
	</div>
	<!-- /Page Header -->
	<!-- Content Starts -->
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-body">
					<article class="post">
						<?php $topic = $this->db->get_where('ad_knowledge_topic',array('knowledge_id'=>$id))->row_array(); 
						$likes = $this->db->get_where('ad_knowledge_likes',array('knowledge_id'=>$id,'like_count'=>'1'))->result_array();
						?>
						<ul class="meta">
							<?php //echo '<pre>'; print_r($knowledge_details); exit; ?>
							<li><span>Created :</span> <?php echo date('M, d, Y', strtotime(str_replace('/', '-', $knowledge_details->created_datetime))); ?></li>
							<li><span>Category:</span> <a href="#"><?php echo $knowledge_details->category_name; ?></a></li>
							<li><span>Last Updated:</span> <?php echo ($knowledge_details->modified_datetime!='0000-00-00 00:00:00')?date('F, d, Y', strtotime(str_replace('/', '-', $knowledge_details->modified_datetime))):date('F, d, Y', strtotime(str_replace('/', '-', $knowledge_details->created_datetime))); ?></li>
							<li><span>Comments :</span> <a href="#"><?php echo $no_of_comments; ?></a></li>
							<li><span>Views :</span> <?php echo $knowledge_details->view_count; ?></li>
							<li><span>Likes :</span> <?php echo count($likes); ?></li>
						</ul>
						<h1 class="post-title"><?php echo ucfirst($topic['topic']); ?> </h1>
						<p>
							<?php if($knowledge_details->description != '') { 
								echo $knowledge_details->description;
							} else {
								echo 'Description Not Found';
							} ?>
						</p>
					</article>
					<?php if($this->session->userdata('role_id') == 3) { ?>
						<div class="feedback">
							<h3>Was this article helpful to you?</h3>
							<p>
								<a href="<?php echo base_url();?>/ad_knowledge/add_like_details/<?php echo $knowledge_details->id; ?>/1" class="btn btn-success"><i class="fa fa-thumbs-up"></i></a>
								<a href="<?php echo base_url();?>/ad_knowledge/add_like_details/<?php echo $knowledge_details->id; ?>/0" class="btn btn-danger"><i class="fa fa-thumbs-down"></i></a>
							</p>
							<p><?php echo $likes->likes_count; ?> found this helpful</p>
						</div>
					<?php } ?>
					<div class="comment-section">
						<div class="comments-area clearfix">
							<h3>(<?php echo $no_of_comments;?>) Comments</h3>
							<?php if($comments) { 
									foreach ($comments as $comment) { 
										$user_details = $this->db->get_where('account_details',array('user_id'=>$comment->user_id))->row_array();
										$dt = new DateTime($comment->created_datetime);
										$time = $dt->format('H:i A'); 

										if(!empty($user_details['avatar'])){
											$avatar = $user_details['avatar'];
										}else{
											$avatar = 'default_avatar.jpg';
										}

										?>
										<ul class="comment-list">
											<li>
												<div class="comment">
												    <input type="hidden" class="form-control comment_id" name="comment_id" id="comment_id" value="<?php echo $comment->id; ?>">
													<input type="hidden" class="form-control knowledge_id" name="knowledge_id" id="knowledge_id" value="<?php echo $id; ?>">
													<input type="hidden" class="form-control user_id" name="user_id" id="user_id" value="<?php echo $comment->user_id; ?>">
													<div class="comment-author">
														<img width="86" height="86" class="avatar avatar-86 photo" src="<?php echo base_url(); ?>assets/avatar/<?php echo $avatar; ?>" alt="">
													</div>
													<div class="comment-details">
														<div class="author-name">
															<a class="url" href="#"><?php echo $user_details['fullname']; ?></a> <span class="commentmetadata"><?php echo date('F d, Y', strtotime(str_replace('/', '-', $comment->created_datetime))); ?> at <?php echo $time; ?></span>
															<!-- October 25, 2016 at 01:10 pm -->
														</div>
														<div class="comment-body">
															<p><?php echo $comment->comments; ?></p>
														</div>
														<div class="reply">
														    <a href="#" class="comment-reply-link" rel="nofollow" id="reply_comments" onclick="reply_comments('<?php echo $comment->id; ?>','<?php echo $id; ?>','<?php echo $comment->user_id; ?>')"><i class="fa fa-reply"></i>Reply</a>
															<!--<a href="#" class="comment-reply-link" rel="nofollow" data-toggle="modal" data-target="#reply_comment"  class="btn add-btn"><i class="fa fa-reply"></i>Reply
															</a>-->
															
														</div>
													</div>
												</div>
												<?php $rly_comments = $this->db->select('c.reply_user_id,c.reply_user_comments,c.modified_datetime,u.username')
									            ->from('ad_knowledge_comments as c')
									            ->join('ad_knowledge as ad_k', 'ad_k.id=c.knowledge_id', 'LEFT')
									            ->join('users as u', 'u.id=c.reply_user_id', 'LEFT')
									            ->where(array('c.id'=>$comment->id,'c.knowledge_id'=>$id,'reply_user_id!='=>'','reply_user_comments!='=>''))
									            ->get()
									            ->result();

												if(!empty($rly_comments)) {
												foreach($rly_comments as $reply) { 
													$dt_reply = new DateTime($reply->modified_datetime);
													$reply_time = $dt_reply->format('H:i A');
													?>
												<ul class="children">
													<li>
														<div class="comment">
															<div class="comment-author">
																<img width="86" height="86" class="avatar avatar-86 photo" src="<?php echo base_url();?>assets/img/user.jpg" alt="">
															</div>
															<div class="comment-details">
																<div class="author-name">
																	<a href="#" class="comment-reply-link"></a><a class="url" href="#"><?php echo $reply->username; ?></a> <span class="commentmetadata"><?php echo date('F d, Y', strtotime(str_replace('/', '-', $reply->modified_datetime))); ?> at <?php echo $reply_time; ?></span>
																</div>
																<div class="comment-body">
																	<p><?php echo $reply->reply_user_comments; ?></p>
																</div>
																<!-- <div class="reply"><a href="#" class="comment-reply-link"><i class="fa fa-reply"></i> Reply</a></div> -->
																<a href="#" class="comment-reply-link" rel="nofollow" id="reply_comments" onclick="reply_comments('<?php echo $comment->id; ?>','<?php echo $id; ?>','<?php echo $comment->user_id; ?>')"><i class="fa fa-reply"></i>Reply</a>
															</div>
														</div>
													</li>
												</ul>
											<?php } } ?>
											</li>
										</ul>
								<?php } ?>
								<a href="#" class="comment-reply-link" rel="nofollow" data-toggle="modal" data-target="#reply_comment"  class="btn add-btn"><i class="fa fa-comments-o"></i>Comments</a>
							<?php } else { ?>  
								<a href="#" class="comment-reply-link" rel="nofollow" data-toggle="modal" data-target="#reply_comment"  class="btn add-btn"><i class="fa fa-comments-o"></i>Comments
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="w-sidebar">
				<div class="widget widget-category">
					<h4 class="widget-title"><i class="fa fa-folder-o"></i> Categories</h4>
					<ul>
						<?php foreach($categories as $category) { 
							if($category->status == 1){
							?>
						<li>
							<a href="#" style="pointer-events: none;"><?php echo $category->category_name; ?></a>
						</li>
						<?php } } ?>
					</ul>
				</div>
				<div class="widget widget-category">
					<h4 class="widget-title"><i class="fa fa-folder-o"></i> Popular Articles</h4>
					<ul>
						<?php foreach($popular_articles as $pop_articles) { 
							$topicss = $this->db->get_where('ad_knowledge_topic',array('knowledge_id'=>$pop_articles->id))->row_array();
							?>
						<li>
							<a href="<?php echo base_url();?>ad_knowledge/ad_knowledge_view/<?php echo $pop_articles->id;?>"><?php echo ucfirst($topicss['topic']); ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="widget widget-category">
					<h4 class="widget-title"><i class="fa fa-folder-o"></i> Latest Articles</h4>
					<ul>
						<?php foreach($latest_articles as $articles) { 
							$topicss1 = $this->db->get_where('ad_knowledge_topic',array('knowledge_id'=>$articles->id))->row_array();
							?>
						<li>
							<a href="<?php echo base_url();?>ad_knowledge/ad_knowledge_view/<?php echo $articles->id;?>"><?php echo ucfirst($topicss1['topic']); ?></a>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- /Content End -->
</div>

<div id="add_know_<?php echo $id; ?>" class="modal" role="dialog">  <!-- custom-modal fade -->
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Knowledge</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php $attributes = array('id' => 'knowledge_submit'); echo form_open(base_url().'ad_knowledge/addKnowledge',$attributes); ?>
						<div class="form-group">
							<input class="form-control" type="hidden" name="knowledge_id" id="knowledge_id" value="<?php echo $id; ?>">
							<label><?php echo lang('category'); ?> <span class="text-danger">*</span></label>
							<select class="select form-control" name="category" id="category">
								<option value="">Select Category</option>
								<?php foreach($categories as $category) { ?>
									<option value="<?php echo $category->id; ?>" <?=!empty($knwldge_data->category==$category->id)?'selected':'';?>><?php echo $category->category_name; ?></option>
								<?php } ?>
							</select>
							<div class="add-more">
								<a href="#" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-circle"></i> Add</a>
							</div>
						</div>
						<!-- <div class="form-group">
							<label><?php echo lang('title'); ?> <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="title" id="title" value="<?php echo $knwldge_data->title; ?>">
						</div> -->
						<?php /* <div class="form-group topics1">
							<label><?php echo lang('topic'); ?> <span class="text-danger">*</span></label>
							<textarea class="form-control" name="topic[]" id="topic"><?php echo $knwldge_data->topic; ?></textarea>
							<div class="add-more">
								<a href="#" id="add_more_topic"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>
						</div> */ ?>
						<div class="form-group topics1">
							<label><?php echo lang('topic'); ?> <span class="text-danger">*</span></label>
							<?php $i = 1; foreach($topics as $topic) { ?>
								<textarea class="form-control" name="topic[]" id="topic_<?php echo $topic->id;?>"><?php echo $topic->topic; ?></textarea>
							<?php if($i != 1) { ?> <a href="#" id="remove_topic_<?php echo $topic->id;?>" class="remove_topic" onclick="removeTopic('<?php echo $topic->id;?>')"><i class="fa fa-trash-o"></i></a> <?php } $i++;   }?>
							<!-- <div class="add-more">
								<a href="#" id="add_more_topic"><i class="fa fa-plus-circle"></i> Add More</a>
							</div> -->
						</div>
						<div class="form-group">
							<label><?php echo lang('description'); ?> <span class="text-danger">*</span></label>
							<textarea class="form-control foeditor-550" name="description" id="description"><?php echo $knwldge_data->description; ?></textarea>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn" id="submit_knowledge"><?php echo lang('submit'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>	

<div id="delete_know_<?php echo $id; ?>" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<button type="button" class="close" data-dismiss="modal">&times;</button> 
				<h4 class="modal-title">Delete Knowledge</h4>
			</div>
			<?php $attributes = array('id' => 'knowledge_submit'); echo form_open(base_url().'ad_knowledge/delete_knowledge',$attributes); ?>
				<div class="modal-body">
					<p>Are you sure want to delete?</p>
					<input type="hidden" name="knowledge_id" id="knowledge_id" value="<?=$id?>"> 
				</div>
				<div class="modal-footer"> 
					<button type="submit" class="btn btn-danger"><?php echo lang('delete'); ?></button>
					<a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('close')?></a>
				</div>
			</form>
		</div>
	</div>
</div>	

<div id="reply_comment" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Comments</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php $attributes = array('id' => 'comments_submit'); echo form_open(base_url().'ad_knowledge/add_comments',$attributes); ?>
					<input type="hidden" class="form-control" name="knowledge_id" id="knowledge_id" value="<?php echo $id; ?>">
					<input type="hidden" class="form-control" name="knowledge_id_1" id="knowledge_id_1" value="">
					<input type="hidden" class="form-control" name="comment_id_1" id="comment_id_1" value="">
					<input type="hidden" class="form-control" name="user_id_1" id="user_id_1" value="">
					<input type="hidden" class="form-control" name="user_reply_comments" id="user_reply_comments" value="1">
					<div class="form-group">
						<label for="comment">Comments<span class="text-danger">*</span></label>
						<textarea id="comment" name="comment" class="form-control" rows="3" cols="5" required></textarea>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="post_comment">Post Comment</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	

<div class="modal" id="myModal2" data-backdrop="static">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo lang('add_category'); ?></h4>
          <button type="button" data-dismiss="modal" aria-hidden="true">×</button>
        </div><div class="container"></div>
        <div class="modal-body">
          <?php $attributes = array('id' => 'category_submit'); echo form_open(base_url().'ad_knowledge/addCategory',$attributes); ?>
					<div class="form-group">
						<label><?php echo lang('category_name'); ?> <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="category_name" id="category_name">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_category"><?php echo lang('submit'); ?></button>
					</div>
				</form>
        </div>
        <!-- <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn">Close</a>
          <a href="#" class="btn btn-primary">Save changes</a>
        </div> -->
      </div>
    </div>
</div>