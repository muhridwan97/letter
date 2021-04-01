<form action="<?= site_url('master/item/update/' . $item['id']) ?>" method="POST" id="form-item">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit item</h4>
			<div class="form-group">
				<label for="item_name">Item Name</label>
				<input type="text" class="form-control" id="item_name" name="item_name" required maxlength="200"
					   value="<?= set_value('item_name', $item['item_name']) ?>" placeholder="Enter a item name">
				<?= form_error('item_name') ?>
			</div>
			<div class="form-group">
				<label for="specification">Specification</label>
				<input type="text" class="form-control" id="specification" name="specification" required maxlength="100"
					   value="<?= set_value('specification', $item['specification']) ?>" placeholder="Item specification description">
				<?= form_error('specification') ?>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="unit">Unit</label>
						<input type="text" class="form-control" id="unit" name="unit" required maxlength="50"
							   value="<?= set_value('unit', $item['unit']) ?>" placeholder="Enter a unit name">
						<?= form_error('unit') ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="brand">Brand</label>
						<input type="text" class="form-control" id="brand" name="brand" required maxlength="200"
							   value="<?= set_value('brand', $item['brand']) ?>" placeholder="Enter brand item">
						<?= form_error('brand') ?>
					</div>
				</div>
			</div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter item description"><?= set_value('description', $item['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
			<div class="form-check">
				<label class="form-check-label" for="is_controlled_item">
					<input type="checkbox" class="form-check-input" value="1" name="is_controlled_item" id="is_controlled_item" <?= set_checkbox('is_controlled_item', 1, $item['is_controlled_item'] == 1) ?>>
					Is Controlled Item
				</label>
			</div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Item</button>
        </div>
    </div>
</form>
