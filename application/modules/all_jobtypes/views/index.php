<div class="content">
        <div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-5">
			<h4 class="page-title"><?=lang('job_types')?></h4>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('job_types')?></li>
            </ul>
		</div>
        <div class="col-sm-4 col-7 text-right m-b-20">
		<?php
		if(App::is_permit('menu_alljobtypes','create')){
           ?>
            <a class="btn add-btn" href="<?=base_url()?>all_jobtypes/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Job Type</a>
        <?php } ?>
		</div>
	</div>
</div>

    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('job_type_name')?></th>                                 
                                <th>Status</th>
								<?php
								if(App::is_permit('menu_alljobtypes','write')==true|| App::is_permit('menu_alljobtypes','delete')==true)
								{
								?>
                                <th class="col-options no-sort text-right"><?=lang('action')?></th>
								<?php
								}
								?>
							</tr>
                        </thead>
                        <tbody>
                            <?php                                      
                                if (!empty($job_types)) 
                                {
                                    $j =1;
                                    foreach ($job_types as $key => $job_type) 
                                    { 
                                        $status = "Active";
                                        if($job_type->status==0)
                                        {
                                            $status = "Inactive";
                                        }
                                        ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$job_type->job_type?> </td> 
                                            <td> 
                                                <div class="action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded" href="javascript:void(0);">
                                                        <i class="fa fa-dot-circle-o text-success"></i><?php echo $status; ?>
                                                    </a>
                                                </div>
                                                </td>
                                                <?php
						if(App::is_permit('menu_alljobtypes','write')==true|| App::is_permit('menu_projects','delete')==true)
						{
						
						?>
                                            <td class="text-right"> 
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon" data-toggle="dropdown"><i class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                <?php
                                                if(App::is_permit('menu_alljobtypes','write'))
                                                {
                                                ?>
                                                <a href="<?=base_url()?>all_jobtypes/edit/<?=$job_type->id?>" class="dropdown-item" data-toggle="ajaxModal">
                                                    <i class="fa fa-edit m-r-5"></i> Edit
                                                </a>
                                                <?php
                                                }
                                                if(App::is_permit('menu_alljobtypes','delete'))
                                                {
                                                ?>
                                                <a href="<?=base_url()?>all_jobtypes/delete/<?=$job_type->id?>" class="dropdown-item" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                    <i class="fa fa-trash-o m-r-5"></i> Delete
                                                </a> 
                                                <?php
                                                }
                                                ?>
                                                </div>
                                                </div>

                                            </td>
                                            <?php
						}
						?>
                                        </tr>
                                        <?php $j++; 
                                    } 
                                } 
                                else
                                {?>
                                    <tr>No Results</tr>
                                <?php 
                                } 
								?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>

</div>