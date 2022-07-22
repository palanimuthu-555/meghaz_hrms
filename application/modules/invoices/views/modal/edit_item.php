<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_item')?></h4>
		</div>
		<?php $item = Invoice::view_item($id); ?>
		<?php $attributes = array('class' => 'bs-example form-horizontal'); echo form_open(base_url().'invoices/items/edit',$attributes); ?>
			<input type="hidden" name="item_id" value="<?=$item->item_id?>">
			<input type="hidden" name="item_order" value="<?=$item->item_order?>">
			<input type="hidden" name="invoice_id" value="<?=$item->invoice_id?>">
			<div class="modal-body">
				<div class="form-group">
					<label class=""><?=lang('item_name')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" value="<?=$item->item_name?>" name="item_name">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('item_description')?> </label>
					<div class="">
						<textarea class="form-control ta" name="item_desc"><?=$item->item_desc?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('quantity')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" value="<?=$item->quantity?>" name="quantity">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('unit_price')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" value="<?=$item->unit_cost?>" name="unit_cost">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('tax_rate')?> <span class="text-danger">*</span></label>
					<div class="">
						<select name="item_tax_rate" class="form-control m-b">
						<option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option>
						<option value="0.00"><?=lang('none')?></option>
						<?php foreach (Invoice::get_tax_rates() as $key => $tax) { ?>
						<option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
						<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>