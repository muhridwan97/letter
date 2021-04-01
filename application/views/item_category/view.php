<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Item</h4>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="category">Item Code</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="category">
                        <?= $item['item_code'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_vendor">Item Name</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_vendor">
                        <?= if_empty($item['item_name'], '-') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="is_reserved">Is Reserved</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="is_reserved">
                        <?= $item['is_reserved'] == 1 ? 'Yes' : 'No'; ?>
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
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_EDIT) && $item['is_reserved'] == 0): ?>
                <a href="<?= site_url('master/item-category/edit/' . $item['id']) ?>" class="btn btn-primary">
                    Edit Item
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>