<div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">


            <?php if (isset($task)) : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title h3"><?=$task->task_name?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('projects/view/'.$task->project)?>"><?=$task->project_title?></a>
                    </span>
                    <?=lang('project')?>
                </li>
                <?php if ($task->milestone > 0) : ?>
                <li class="list-group-item">
                    <span class="float-right">
                        <?php
                        $milestones = $this->db->where('id',$task->milestone)->get('milestones')->result();
                        $milestone = $milestones[0];  ?>
                        <a href="<?=base_url('projects/view/'.$task->project.'/?group=milestones&view=milestone&id='.$task->milestone)?>"><?=$milestone->milestone_name?></a>
                    </span>
                    <?=lang('milestone')?>
                </li>
                <?php endif; ?>
                <li class="list-group-item">
                    <span class="float-right">
                        <label class="badge badge-default"><?php echo User::displayName($task->added_by); ?></label>
                    </span>
                    <?=lang('added_by')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-success"><?=$task->start_date?></label>
                    </span>
                    <?=lang('start_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-danger"><?=$task->due_date?></label>
                    </span>
                    <?=lang('due_date')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-success"><?=$task->task_progress?>%</label>
                    </span>
                    <?=lang('progress')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                        <?php foreach (Project::task_team($task->t_id) as $u) : ?>
                        <?php $names[] = User::displayName($u->assigned_user); ?>
                        <?php endforeach; ?>
                        <?=isset($names) ? implode(", ", $names) : ''?>
                    </span>
                    <?=lang('assigned_to')?>
                </li>
				<li class="list-group-item"><p><?=$task->task_description?></p></li>
            </ul>
            
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
            <a href="<?=base_url('projects/view/' . $task->project . '?group=tasks&view=task&id=' . $task->t_id)?>" class="btn btn-success text-white"><?=lang('view_task')?></a>
        </div>
            <?php endif; ?>


            <?php if (isset($payment)) : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title h3"><?=lang('payment')?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('companies/view/'.$payment->paid_by)?>"><?=$payment->company_name?></a>
                    </span>
                    <?=lang('client')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('invoices/view/'.$payment->inv_id)?>"><?=$payment->reference_no?></a>
                    </span>
                    <?=lang('invoice')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                    <label class="badge badge-success">
                    <?=strftime(config_item('date_format'), strtotime($payment->payment_date))?>
                    </label>
                    </span>
                    <?=lang('payment_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right"><?=$payment->method_name?></span><?=lang('payment_method')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                    <label class="badge badge-success"><?=Applib::format_currency($payment->currency, $payment->amount)?>
                    </label>
                    </span><?=lang('amount')?>

                </li>
            </ul>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
            <a href="<?= base_url('payments/view/'.$payment->p_id) ?>" class="btn btn-success text-white"><?=lang('view_payment')?></a>
        </div>
            <?php endif; ?>

            <?php if (isset($project)) : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title h3"><?=$project->project_title?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('companies/view/'.$project->client)?>"><?=$project->company_name?></a>
                    </span>
                    <?=lang('client')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-success"><?=$project->start_date?></label>
                    </span>
                    <?=lang('start_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-danger"><?=$project->due_date?></label>
                    </span>
                    <?=lang('due_date')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                        <label class="badge badge-success"><?=Project::get_progress($project->project_id);?>%</label>
                    </span>
                    <?=lang('progress')?>
                </li>
				<li class="list-group-item"><p><?=$project->description?></p></li>
            </ul>
            
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
            <a href="<?= base_url('projects/view/' . $project->project_id) ?>" class="btn btn-success text-white"><?=lang('view_project')?></a>
        </div>
            <?php endif; ?>

            <?php if (isset($invoice)) : ?>
                <?php $this->load->model('Invoice'); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('invoice')?> <?=$invoice->reference_no?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('companies/view/'.$invoice->client)?>"><?=$invoice->company_name?></a>
                    </span>
                    <?=lang('client')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                        <?=lang(Invoice::payment_status($invoice->inv_id)); ?>
                    </span>
                    <?=lang('payment_status')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                    <label class="badge badge-success">
                        <?=Applib::format_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id))?>
                    </label>
                    </span>
                    <?=lang('balance_due')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                    <label class="badge badge-danger">
                    <?=strftime(config_item('date_format'), strtotime($invoice->due_date))?>
                    </label>
                    </span>
                    <?=lang('due_date')?>
                </li>
				<li class="list-group-item"><p><?=$invoice->notes?></p></li>
            </ul>
            
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
            <a href="<?= base_url('invoices/view/' . $invoice->inv_id) ?>" class="btn btn-success text-white"><?=lang('view_invoice')?></a>
        </div>
            <?php endif; ?>

            <?php if (isset($estimate)) : ?>
                <?php $this->load->model('Estimate'); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('estimate')?> <?=$estimate->reference_no?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <a href="<?=base_url('companies/view/'.$estimate->client)?>"><?=$estimate->company_name?></a>
                    </span>
                    <?=lang('client')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                        <?=lang(strtolower($estimate->status))?>
                    </span>
                    <?=lang('estimate_status')?>
                </li>

                <li class="list-group-item">
                    <span class="float-right">
                    <label class="badge badge-danger">
                    <?=strftime(config_item('date_format'), strtotime($estimate->due_date))?>
                    </label>
                    </span>
                    <?=lang('due_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                     <label class="badge badge-success">
                        <?=Applib::format_currency($estimate->currency, Estimate::due($estimate->est_id))?>
                        </label>
                    </span>
                    <?=lang('estimate_cost')?>
                </li>
				<li class="list-group-item"><p><?=$estimate->notes?></p></li>
            </ul>
            
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
            <a href="<?= base_url('estimates/view/' . $estimate->est_id) ?>" class="btn btn-success text-white"><?=lang('view_estimate')?></a>
        </div>
            <?php endif; ?>

            <?php if (isset($event)) : ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title h3"><?=$event->event_name?></h4>
        </div>
        <div class="modal-body">
            <ul class="list-group no-radius">
                <li class="list-group-item">
                    <span class="float-right">
                        <?=$event->event_name?>
                    </span>
                    <?=lang('event_name')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-success"><?=$event->start_date?></label>
                    </span>
                    <?=lang('start_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                         <label class="badge badge-danger"><?=$event->end_date?></label>
                    </span>
                    <?=lang('end_date')?>
                </li>
                <li class="list-group-item">
                    <span class="float-right">
                    <a class="thumb-xs avatar">
      <img src="<?=User::avatar_url($event->added_by); ?>" class="rounded">

          </a> <label class="badge badge-default"><?=User::displayName($event->added_by)?></label></span>
                    <?=lang('added_by')?>
                </li>

                <?php if($event->project > 0) : ?>

                    <li class="list-group-item">
                    <span class="float-right">
                         <a href="<?=base_url()?>projects/view/<?=$event->project;?>">
                         <?=Project::by_id($event->project)->project_title;?>
                         </a>
                    </span>
                    <?=lang('project')?>
                </li>
            <?php endif; ?>
			<li class="list-group-item"><p><?=$event->description?></p></li>
            </ul>
            
        </div>
        <div class="modal-footer">
            <?php if(User::is_admin() || User::is_staff()) : ?>
            <a href="<?= base_url('calendar/edit_event/'.$event->id) ?>" class="btn btn-success text-white" data-toggle="ajaxModal" data-dismiss="modal"><?=lang('edit_event')?>

            </a>
        <?php endif; ?>
            <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
        </div>
            <?php endif; ?>


</div>
</div>
