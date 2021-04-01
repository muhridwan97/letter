<form action="<?= site_url('master/category/update/' . $category['id']) ?>" method="POST" id="form-category">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit Category</h4>
            <div class="form-group">
                <label for="category">Category Title</label>
                <input type="text" class="form-control" id="category" name="category" required maxlength="50"
                       value="<?= set_value('category', $category['category']) ?>" placeholder="Enter a category title">
                <?= form_error('category') ?>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter category description"><?= set_value('description', $category['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Category</button>
        </div>
    </div>
</form>