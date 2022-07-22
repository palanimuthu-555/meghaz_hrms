<div class="content">
	 <div class="page-header">
	<div class="row">
		<div class="col-sm-8 col-4">
			<h4 class="page-title"><?='Holidays of <span class="yr_cls_h">'.date('Y');?></h4>
			<ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
                <li class="breadcrumb-item active"><?='Holidays of <span class="yr_cls_h">'.date('Y');?></li>
            </ul>
		</div>
		<div class="col-sm-4 col-8 text-right m-b-20">
			<?php if(App::is_permit('menu_holidays','create'))
			{ ?>
			<a class="btn add-btn" href="<?=base_url()?>holidays/add"><i class="fa fa-plus"></i> <?='Add New Holiday';?></a>
			<?php } ?>
		</div>
	</div>
</div>

	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group form-focus focused">
					<label class="control-label">Select Holidays</label>
					<select class="select2-option form-control m-b-0" onchange="get_year_holidays(this.value);"> 
						<option value="0" selected="selected" disabled>-- Filter Holidays -- </option>
						<?php for($i = 2015;$i <= date('Y')+1; $i++ ){ 
						$sel = '';
						if($i == date('Y')) { $sel = 'selected="selected"'; }
						?>
						<option value="<?=$i?>" <?=$sel?>><?=$i?></option>
						<?php } ?>       
					</select>
					</div>
				</div>
			</div> 
		   <div class="table-responsive">
				<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th><?='No';?></th>
							<th><?='Title';?> </th>
							<th><?='Holiday Date';?></th>
							<th><?='Description';?></th> 
							<?php if(App::is_permit('menu_holidays','write')==true || App::is_permit('menu_holidays','delete')==true)
							{
							?>
							
							<th class="text-right"><?='Action';?></th> 
							<?php } ?>
						</tr>
					</thead>
					<tbody id="holiday_tbl_body">
					<?php  
					$i = 1;  foreach($holidays as $key => $hldays){
						 $curdate       = strtotime(date('d-m-Y'));
						 $hlidate       = strtotime($hldays['holiday_date']); 
						 ?>
						<tr <?php if($curdate > $hlidate){  echo 'class="holiday-completed"'; }else{ echo 'class="holiday-upcoming"'; } ?> >
							<td><?=$i?></td>
							<td><?=$hldays['title']?></td>
							<td><?php
							 echo date('d-m-Y',strtotime($hldays['holiday_date']));
							 /*if($curdate > $hlidate){
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-danger"> End </span>';
							 }else if($curdate < $hlidate){ 
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-success"> Upcoming </span>';
							 }else{
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-info"> Today </span>';
							 }*/
							?></td>
							<td width="50%"><?=$hldays['description']?></td>
							<?php
						
							
							  if($curdate > $hlidate){ 
							  $url = '#'; $del_url='#'; $dis = 'disabled'; $modl = '';
							  }
							  else{ 
							  $url = base_url().'holidays/edit/'.$hldays['id']; $del_url=base_url().'holidays/delete/'.$hldays['id']; $dis=''; $modl='data-toggle="ajaxModal"'; 
							  }  ?>
							  
							<?php if(App::is_permit('menu_holidays','write')==true || App::is_permit('menu_holidays','delete')==true)
							{
							?>
							  
							  <td class="text-right">
									<div class="dropdown">
										<?php if($curdate > $hlidate)
										{
											?>
										<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu float-right">
										<a class="dropdown-item" title="No action Found"  href="#" data-original-title="No action Found">
										No action Found
										</a>
									</div>
										<?php
									     }
									     else
									     	{?>
										<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
										<div class="dropdown-menu float-right">
										<?php if(App::is_permit('menu_holidays','write'))
										{?>
										<a href="<?php echo $url?>" <?php $dis?> class="dropdown-item"  title="<?php echo lang('edit')?>">
										<i class="fa fa-edit m-r-5"></i> Edit
										</a>
										<?php
										}
										if(App::is_permit('menu_holidays','delete'))
										{
										?>
										<a class="dropdown-item" title="Delete" <?php echo $modl?>  href="<?php echo $del_url?>" <?php echo $dis ?>data-original-title="Delete">
										<i class="fa fa-trash-o m-r-5"></i>Delete
										</a>
										<?php
										}
										}
										?>
										</div>
									</div>

							</td>
							


							<?php  
							}
							 ?>
					   </tr>
					 <?php $i++; } ?>  
					</tbody>
			   </table>    
			</div>
		</div>
	</div>

</div> 
<script language="javascript">
function get_year_holidays(year)
{ 	 if(year != "" || year != 0){   
      $('#holiday_tbl_body').html('<tr> <td class="text-center" colspan="5"><img src="<?=base_url()?>assets/images/loader-mini.gif" alt="Loading"></td> </tr>'); 
      $.ajax({
          url     : "<?=base_url()?>"+'holidays/year_holidays/',
          data    : { year : year,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },
          type    :"POST",
          success : function(res){ 
              $('#holiday_tbl_body').html(res); 
          }
      });
    }									
}
</script>