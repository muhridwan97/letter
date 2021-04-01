<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0">Data Cities</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_CREATE)): ?>
                    <a href="<?= site_url('master/city/create') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Type</th>
                <th>Province</th>
                <th style="min-width: 120px" class="text-sm-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = isset($cities) ? ($cities['current_page'] - 1) * $cities['per_page'] : 0 ?>
            <?php foreach ($cities['data'] as $city): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $city['city'] ?></td>
                    <td><?= $city['province'] ?></td>
                    <td class="text-md-right">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/city/view/' . $city['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_EDIT)): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/city/edit/' . $city['id']) ?>">
                                        <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_DELETE)): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                       data-id="<?= $city['id'] ?>" data-label="<?= $city['city'] ?>" data-title="City"
                                       data-url="<?= site_url('master/city/delete/' . $city['id']) ?>">
                                        <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($cities['data'])): ?>
                <tr>
                    <td colspan="4">No cities data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $cities]) ?>
    </div>
</div>

<?php $this->load->view('city/_modal_filter') ?>
<?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>