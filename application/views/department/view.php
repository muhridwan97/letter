<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Department</h4>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="department">Department Title</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="department">
                        <?= $department['department'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_employee">Total Employee</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_employee">
                        <?= $department['total_employee'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_category_goods">Total Category Goods</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_category_goods">
                        <?= $department['total_category_goods'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_category_services">Total Category Services</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_category_services">
                        <?= $department['total_category_services'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="total_requisition">Total Requisition</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="total_requisition">
                        <?= $department['total_requisition'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="description">Description</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="description">
                        <?= if_empty($department['description'], 'No description') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="created_at">
                        <?= format_date($department['created_at'], 'd F Y H:i') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="updated_at">
                        <?= if_empty(format_date($department['updated_at'], 'd F Y H:i'), '-') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Categories</h4>
            <div class="table-responsive">
                <table class="table" id="table-category">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Category</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($departmentCategories as $category): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= if_empty($category['category'], '-') ?></td>
                            <td><?= if_empty($category['description'], '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(!$this->config->item('sso_enable')): ?>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_EDIT)): ?>
                    <a href="<?= site_url('master/department/edit/' . $department['id']) ?>" class="btn btn-primary">
                        Edit Department
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</form>