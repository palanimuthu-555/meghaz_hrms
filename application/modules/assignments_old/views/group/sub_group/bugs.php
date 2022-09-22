<div class="card panel-table">
	<div class="card-header card-h-p1">
		<div class="row">
			<div class="col-6">
				<h3 class="card-title m-t-6">Bugs</h3>
			</div>
			<div class="col-6">
				<a href="<?=base_url()?>projects/bugs/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-primary btn-sm float-right"><?=lang('new_bug')?></a> 
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table id="table-bugs" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr>
						<th class="col-sm-3"><?=lang('issue_title')?></th>
						<th><?=lang('date')?></th>
						<th><?=lang('status')?></th>
						<th><?=lang('severity')?></th>
						<th class="col-options no-sort"></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach (Project::has_bugs($project_id) as $key => $b) { 
                $issue_title = $b->issue_title ? $b->issue_title : $b->issue_ref;

                switch ($b->bug_status) {
                  case 'Resolved':
                    $status_label = 'success'; 
                      break;
                    case 'Verified':
                      $status_label = 'success'; 
                      break;
                    case 'Confirmed':
                     $status_label = 'info';
                      break;
                    case 'Pending':
                         $status_label = 'primary'; 
                      break;
                  default:
                     $status_label = 'default'; 
                    break;
                }
                ?>
					<tr>   
						<td>
							<a class="thumb-sm avatar" data-toggle="tooltip" data-original-title="<?=User::displayName($b->reporter)?>" href="<?=base_url()?>projects/view/<?=$b->project?>?group=bugs&view=bug&id=<?=$b->bug_id?>" data-toggle="tooltip" data-placement="right" title = ""><img src="<?=User::avatar_url($b->reporter); ?>" class="rounded"></a>
							<h2><a href="<?=base_url()?>projects/view/<?=$b->project?>?group=bugs&view=bug&id=<?=$b->bug_id?>"><?=word_limiter($issue_title,4)?> </a></h2>
						</td>					
                        <td><?=$b->reported_on?></td>
                        <td>
							<span class="badge badge-<?=$status_label?>"><?=lang(strtolower($b->bug_status))?></span>
						</td>
                        <td><?=ucfirst($b->severity)?></td>
						<td class="text-right">
							<?php if (!User::is_client() || $b->reporter == User::get_id()) { ?>
							<?php if (!User::is_client()){ ?>
							<div class="btn-group">
								<button class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-ellipsis-h"></i>
								</button>
								<div class="dropdown-menu float-right">
									
										<a class="dropdown-item" href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=unconfirmed">
											<?=lang('unconfirmed')?>
										</a>
									
										<a class="dropdown-item" href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=confirmed">
											<?=lang('confirmed')?>
										</a>
									
										<a class="dropdown-item" href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=pending">
											<?=lang('pending')?>
										</a>
									
										<a class="dropdown-item" href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=resolved">
											<?=lang('resolved')?>
										</a>
									
									
										<a class="dropdown-item" href="<?=base_url()?>projects/bugs/status/<?=$b->project?>/?id=<?=$b->bug_id?>&s=verified">
											<?=lang('verified')?>
										</a>
									
								</div>
							</div>
							<?php } ?>
							<a class="btn btn-sm btn-success" href="<?=base_url()?>projects/bugs/edit/<?=$b->project?>/?id=<?=$b->bug_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>
							<a class="btn btn-sm btn-danger" href="<?=base_url()?>projects/bugs/delete/<?=$b->project?>/?id=<?=$b->bug_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>
							<?php } ?>
						</td>
                    </tr>
                    <?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>