<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0">Data Items</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_CREATE)): ?>
                    <a href="<?= site_url('master/item-category/create') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Item Code</th>
                <th>Category</th>
                <th>Name</th>
                <th>Total Vendor</th>
                <th>Is Reserved</th>
                <th style="min-width: 120px" class="text-sm-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = isset($items) ? ($items['current_page'] - 1) * $items['per_page'] : 0 ?>
            <?php foreach ($items['data'] as $item): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $item['item_code'] ?></td>
                    <td><?= $item['category_name'] ?></td>
                    <td><?= $item['item_name'] ?></td>
                    <td>
                        <a href="<?= site_url('master/vendor?item_category=' . urlencode($item['item_name'])) ?>">
                            <?= numerical($item['total_vendor']) ?> vendors
                        </a>
                    </td>
                    <td><?= $item['is_reserved'] == 1 ? 'Yes' : 'No'; ?></td>
                    <td class="text-md-right">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/item-category/view/' . $item['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_EDIT) && $item['is_reserved'] == 0): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/item-category/edit/' . $item['id']) ?>">
                                        <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_DELETE) && $item['is_reserved'] == 0): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                       data-id="<?= $item['id'] ?>" data-label="<?= $item['item_name'] ?>" data-title="Item"
                                       data-url="<?= site_url('master/item-category/delete/' . $item['id']) ?>">
                                        <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($items['data'])): ?>
                <tr>
                    <td colspan="4">No items data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $items]) ?>
    </div>
</div>

<?php $this->load->view('item_category/_modal_filter') ?>
<?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>