<div class="content">
    <div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-5">
			<h4 class="page-title"><?=lang('wiki')?></h4>
             <ul class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('wiki')?></li>
            </ul>
		</div>
        <div class="col-sm-4 text-right col-7 m-b-20">
			<?php
			if(App::is_permit('menu_wiki','create'))
			{
			?>
            <a class="btn add-btn" href="<?=base_url()?>wiki/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Wiki</a>
			<?php
			}
			?>
		</div>
	</div>
</div>
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>                                 
                                <th style="width:300px">Description</th>
								<?php if(App::is_permit('menu_wiki','write')==true || App::is_permit('menu_wiki','delete')==true)
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
                                if (!empty($wikis)) 
                                {
                                    $j =1;
                                    foreach ($wikis as $key => $wiki) 
                                    { ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$wiki->title?> </td> 
                                            <td><?php echo wordwrap($wiki->description,50,"<br>\n")?></td>               
										<?php if(App::is_permit('menu_wiki','write')==true || App::is_permit('menu_wiki','delete')==true)
										{
										?>	
										   <td class="text-right"> 
													

														<div class="dropdown">
															<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false">
															<i class="fa fa-ellipsis-v"></i>
															</a>
															<div class="dropdown-menu float-right">
															<?php

															if(App::is_permit('menu_wiki','write'))
															{
															?>
															<a class="dropdown-item" href="<?=base_url()?>wiki/edit/<?=$wiki->id?>" title="" data-toggle="ajaxModal">
															<i class="fa fa-pencil m-r-5"></i>Edit</a>
															<?php
															}
															if(App::is_permit('menu_wiki','delete'))
															{
															?>
															<a class="dropdown-item" href="<?=base_url()?>wiki/delete/<?=$wiki->id?>"  data-toggle="ajaxModal" title="" data-original-title="Delete">
															<i class="fa fa-trash-o m-r-5"></i>Delete</a>
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
                                { ?>
                                    <tr><td colspan="4">No Results</td></tr>
                                <?php 
                                } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>

</div>