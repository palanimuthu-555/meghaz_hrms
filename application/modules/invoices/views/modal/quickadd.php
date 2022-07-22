<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_item')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'invoiceAddItem'); echo form_open(base_url().'invoices/items/insert',$attributes); ?>
			<input type="hidden" name="invoice" value="<?=$invoice?>">
			<div class="modal-body pt-2">
				<div class="form-group mb-0">
					<label class=""><?=lang('item_name')?> <span class="text-danger">*</span></label>
					<div class="">
						<select name="item" class="form-control" required="required">
							<option value=""><?=lang('choose_template')?></option>
							<?php foreach (Invoice::saved_items() as $key => $item) { ?>
							<option value="<?=$item->item_id?>"><?=$item->item_name?> - <?=$item->unit_cost?></option>
							<?php } ?>					
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button class="btn btn-success" id="inview_add_item"><?=lang('add_item')?></button>
			</div>
		</form>
	</div>
</div>