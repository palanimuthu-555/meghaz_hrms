<div class="content">
    <div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-12">
			<h4 class="page-title"><?=lang('notice_board')?></h4>
             <ul class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?=lang('notice_board')?></li>
            </ul>
		</div>
        <div class="col-sm-4 text-right col-12 m-b-20">
		<?php
		
			if(App::is_permit('menu_notice_board','create'))
			{
			?>
            <a class="btn add-btn" href="<?=base_url()?>notice_board/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Notice Board</a>
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
                    <table id="notice_board" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>                                 
                                <th>Description</th>
								<?php if(App::is_permit('menu_notice_board','write')==true || App::is_permit('menu_notice_board','delete')==true)
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
                                if (!empty($notice_boards)) 
                                {
                                    $j =1;
                                    foreach ($notice_boards as $key => $notice_board) 
                                    { ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$notice_board->title?> </td> 
                                            <td><?php echo wordwrap($notice_board->description,50,"<br>\n")?></td>
											<?php if(App::is_permit('menu_notice_board','write')==true || App::is_permit('menu_notice_board','delete')==true)
											{
											?>
                                            <td class="text-right"> 
												<div class="dropdown">
                                                    <a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu float-right">
														<?php
														if(App::is_permit('menu_notice_board','write'))
														{
														?>
                                                        <a class="dropdown-item" href="<?=base_url()?>notice_board/edit/<?=$notice_board->id?>" title="" data-toggle="ajaxModal">
                                                            <i class="fa fa-pencil m-r-5"></i>Edit</a>
														<?php
														}
														if(App::is_permit('menu_notice_board','delete'))
														{
														?>
                                                            <a class="dropdown-item" href="<?=base_url()?>notice_board/delete/<?=$notice_board->id?>"  data-toggle="ajaxModal" title="" data-original-title="Delete">
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
                                    <tr>No Results</tr>
                                <?php 
                                } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>

</div>