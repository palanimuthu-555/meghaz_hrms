<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_item')?></h4>
		</div>
		<?php $item = Item::view_item($id); ?>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'itemsEditItem'); echo form_open(base_url().'items/edit_item',$attributes); ?>
			<input type="hidden" name="r_url" value="<?=base_url()?>items">
			<input type="hidden" name="item_id" value="<?=$item->item_id?>">
			<div class="modal-body">
				<div class="form-group">
					<label class=""><?=lang('item_name')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" value="<?=$item->item_name?>" name="item_name">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('item_description')?> <span class="text-danger">*</span></label>
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
							<?php foreach ($rates as $key => $tax) { ?>
							<option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="items_edit_item"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>