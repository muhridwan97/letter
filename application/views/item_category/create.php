<form action="<?= site_url('master/item-category/save') ?>" method="POST" id="form-category">
    <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Create New Item</h4>
            <div class="form-group">
                <label for="category">Category</label>
                <select class="custom-select" id="category" name="category" required>
                    <option value="">-- Category Name --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"
                            <?= set_select('category', $category['id']) ?>>
                            <?= $category['category'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= form_error('category') ?>
            </div>
            <div class="form-group">
                <label for="category">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" required maxlength="100"
                       value="<?= set_value('category') ?>" placeholder="Enter a item name">
                <?= form_error('category') ?>
            </div>
            <div class="form-group">
                <label for="is_reserved">Is Reserved?</label>
                <div class="form-row">
                    <div class="col-5 col-md-6">
                        <div class="form-check form-check-inline mt-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="is_reserved" id="is_reserved_yes" value="1"
                                    <?= set_radio('is_reserved', 1) ?> required>
                                YES
                                <i class="input-helper"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-5 col-md-6">
                        <div class="form-check form-check-inline mt-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="is_reserved" id="is_reserved_no" value="0"
                                    <?= set_radio('is_reserved', 0) ?>>
                                NO
                                <i class="input-helper"></i>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter item description"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save</button>
        </div>
    </div>
</form>