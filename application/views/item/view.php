<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Item</h4>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="no_item">No Item</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="no_item">
                        <?= $item['no_item'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="item_name">Item Name</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="item_name">
                        <?= if_empty($item['item_name'], '-') ?>
                    </p>
                </div>
            </div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label" for="unit">Unit</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext" id="unit">
						<?= if_empty($item['unit'], '-') ?>
					</p>
				</div>
			</div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="brand">Brand</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="brand">
                        <?= if_empty($item['brand'], '-') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="specification">Specification</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="specification">
                        <?= if_empty($item['specification'], '-') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="is_controlled_item">Is Controlled</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="is_controlled_item">
                        <?= $item['is_controlled_item'] ? 'YES' : 'NO' ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="description">Description</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="description">
                        <?= if_empty($item['description'], 'No description') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="created_at">
                        <?= format_date($item['created_at'], 'd F Y H:i') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="updated_at">
                        <?= if_empty(format_date($item['updated_at'], 'd F Y H:i'),  '-') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_EDIT)): ?>
                <a href="<?= site_url('master/item/edit/' . $item['id']) ?>" class="btn btn-primary">
                    Edit Item
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>
