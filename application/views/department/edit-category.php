<form action="<?= site_url('master/department/update-category/' . $department['id']) ?>" method="POST" id="form-department">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit Department Category</h4>
            <div class="form-group">
                <label for="categories">Categories</label>
                <select class="form-control select2" name="categories[]" id="categories" multiple>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"<?= set_select('categories[]', $category['id'], get_if_exist($category, 'selected', false)) ?>>
                            <?= $category['category'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= form_error('categories') ?>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Category List</button>
        </div>
    </div>
</form>