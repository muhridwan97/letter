<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Category</h4>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="category">Category Title</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="category">
                        <?= $category['category'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_vendor">Total Vendor</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_vendor">
                        <?= if_empty($category['total_vendor'], 0) ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="description">Description</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="description">
                        <?= if_empty($category['description'], 'No description') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="created_at">
                        <?= format_date($category['created_at'], 'd F Y H:i') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="updated_at">
                        <?= if_empty(format_date($category['updated_at'], 'd F Y H:i'),  '-') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Vendor List</h4>
            <table class="table responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Vendor Name</th>
                    <th>City</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Rating</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; ?>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <a href="<?= site_url('master/vendor/view/'. $vendor['id']) ?>" target="_blank">
                                <?= $vendor['vendor'] ?>
                            </a>
                        </td>
                        <td><?= $vendor['city'] ?></td>
                        <td><?= if_empty($vendor['contact'], '-') ?></td>
                        <td><?= if_empty($vendor['email'], '-') ?></td>
                        <td><?= numerical($vendor['average_rating']) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if(empty($vendors)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No vendor available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_CATEGORY_EDIT)): ?>
                <a href="<?= site_url('master/category/edit/' . $category['id']) ?>" class="btn btn-primary">
                    Edit Category
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>