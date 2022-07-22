<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('new_item')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'itemsAddItem'); echo form_open(base_url().'items/add_item',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class=""><?=lang('item_name')?> <span class="text-danger">*</span></label>
					<input type="text" class="form-control" placeholder="<?=lang('item_name')?>" name="item_name">
					
				</div>
				<div class="form-group">
					<label class=""><?=lang('item_description')?> <span class="text-danger">*</span></label>
					<div>
						<textarea class="form-control ta" name="item_desc" placeholder="<?=lang('item_description')?>"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('quantity')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" placeholder="2" name="quantity">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('unit_price')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" placeholder="350.00" name="unit_cost">
					</div>
				</div>
				<div class="form-group">
					<label class=""><?=lang('tax_rate')?> <span class="text-danger">*</span></label>
					<div class="">
						<select name="item_tax_rate" class="form-control m-b">
							<option value="0.00"><?=lang('none')?></option>
							<?php foreach ($rates as $key => $tax) { ?>
							<option value="<?=$tax->tax_rate_percent?>"><?=$tax->tax_rate_name?></option>
							<?php } ?>
                        </select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-close" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="items_add_item"><?=lang('add_item')?></button>
			</div>
		</form>
	</div>
</div>