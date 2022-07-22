<?php //echo 'det<pre>'; print_r($knwldge_data); exit; ?>
<div class="content container-fluid">
	<!-- Page Header -->
	<div class="page-header">
		<div class="row">
			<div class="col">
				<h3 class="page-title">Knowledgebase</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>em_knowledge">Knowledgebase</a></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
	<!-- Content Starts -->
	<div class="row">
		<div class="col-lg-8">
			<div class="card">
				<div class="card-body">
					<article class="post">
						<?php $topic = $this->db->get_where('ad_knowledge_topic',array('knowledge_id'=>$id))->row_array(); ?>
						<ul class="meta">
							<?php //echo'<pre>'; print_r($knowledge_details); exit; ?>
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
					<?php //if($this->session->userdata('role_id') == 3) { 
						$check_like = $this->db->get_where('ad_knowledge_likes',array('knowledge_id'=>$id,'user_id'=>$this->session->userdata('user_id')))->row_array();
						// print_r($check_like); exit;
						?>
						<div class="feedback">
							<h3>Was this article helpful to you?</h3>
							<p>
								<a <?php if($check_like['like_count'] == 1){ ?> style="pointer-events: none;" <?php  } ?> href="<?php echo base_url();?>em_knowledge/add_like_details/<?php echo $id; ?>/1" class="btn btn-success <?php if($check_like['like_count'] == 1){ echo 'active'; } ?>"><i class="fa fa-thumbs-up"></i></a>
								<a <?php if($check_like['like_count'] == 0){ ?> style="pointer-events: none;" <?php  } ?> href="<?php echo base_url();?>em_knowledge/add_like_details/<?php echo $id; ?>/0" class="btn btn-danger <?php if($check_like['like_count'] == 0){ echo 'active'; } ?>"><i class="fa fa-thumbs-down"></i></a>
							</p>
							<p><?php echo count($likes); ?> found this helpful</p>
						</div>
					<?php //} ?>
					<div class="comment-section">
						<div class="comments-area clearfix">
							<h3>(<?php echo $no_of_comments;?>) Comments</h3>
							<?php if($comments) { 
									foreach ($comments as $comment) { 
										$user_details = $this->db->get_where('account_details',array('user_id'=>$comment->user_id))->row_array();
										$dt = new DateTime($comment->created_datetime);
										$time = $dt->format('H:i A'); ?>
										<ul class="comment-list">
											<li>
												<div class="comment">
												    <input type="hidden" class="form-control comment_id" name="comment_id" id="comment_id" value="<?php echo $comment->id; ?>">
													<input type="hidden" class="form-control knowledge_id" name="knowledge_id" id="knowledge_id" value="<?php echo $id; ?>">
													<input type="hidden" class="form-control user_id" name="user_id" id="user_id" value="<?php echo $comment->user_id; ?>">
													<div class="comment-author">
														<img width="86" height="86" class="avatar avatar-86 photo" src="<?php echo base_url(); ?>assets/img/user.jpg" alt="">
													</div>
													<div class="comment-details">
														<div class="author-name">
															<a class="url" href="#"><?php echo ucfirst($user_details['fullname']); ?></a> <span class="commentmetadata"><?php echo date('F d, Y', strtotime(str_replace('/', '-', $comment->created_datetime))); ?> at <?php echo $time; ?></span>
															<!-- October 25, 2016 at 01:10 pm -->
														</div>
														<div class="comment-body">
															<p><?php echo $comment->comments; ?></p>
														</div>
														<!-- <div class="reply"><a href="#" class="comment-reply-link" rel="nofollow"><i class="fa fa-reply"></i> Reply</a></div> -->
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

												//$this->db->get_where('ad_knowledge_comments',array('id'=>$comment->id,'knowledge_id'=>$id,'reply_user_id!='=>'','reply_user_comments!='=>''))->result(); 

													//echo 'rlycomments<pre>'; print_r($comments); exit;
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
							
							<!-- <div class="comment-reply">
								<h3 class="comment-reply-title">Leave a Reply</h3>
								<form>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="name">Name</label>
												<input type="text" id="name" name="name" class="form-control">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="email">Email</label>
												<input type="email" id="email" name="email" class="form-control">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="website">Website</label>
												<input type="text" id="website" name="website" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="comment">Comment *</label>
										<textarea id="comment" name="comment" class="form-control" rows="3" cols="5"></textarea>
									</div>
									<div class="submit-section">
										<button type="submit" class="btn btn-primary submit-btn">Post Comment</button>
									</div>
								</form>
							</div> -->
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
						<?php foreach($categories as $category) { ?>
						<li>
							<a href="#" style="pointer-events: none;"><?php echo $category->category_name; ?></a>
						</li>
						<?php } ?>
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
						<textarea id="comment" name="comment" class="form-control" rows="3" cols="5"></textarea>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="post_comment">Post Comment</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>	
