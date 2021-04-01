<form action="<?= site_url('purchasing/selection/update-comparison/' . $requisitionId) ?>" method="POST" id="form-item">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit item</h4>
			<div class="form-group">
				<label for="item_name">Item Name</label>
				<input type="text" class="form-control" id="item_name" name="item_name" required maxlength="200" readonly
					   value="<?= set_value('item_name', $comparisonItem) ?>" placeholder="Enter a item name">
				<?= form_error('item_name') ?>
                <input type="hidden" name="id_requisition" value="<?= $requisitionId ?>">
			</div>
			<?php foreach ($purchaseOffers as $offer) : ?>
                <?php $offerId = $offer['id'] ?>
            <div class="form-group">
                <label for="value_item[<?= $offerId ?>]">Value Item <?= $offer['vendor'] ?></label>
                <input type="text" class="form-control" id="value_item[<?= $offerId ?>]" name="value_item[<?= $offerId ?>]" required maxlength="200"
                       value="<?= set_value("value_item[<?= $offerId?> ]", $offer['item_value']['description']) ?>" placeholder="Enter a item value">
                <?= form_error("value_item[<?= $offerId?>]") ?>
            </div>
            <?php endforeach;?>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Item</button>
        </div>
    </div>
</form>
