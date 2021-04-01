<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0">Data Departments</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(!$this->config->item('sso_enable')): ?>
                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_CREATE)): ?>
                        <a href="<?= site_url('master/department/create') ?>" class="btn btn-sm btn-success">
                            <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mt-3 responsive">
                <thead>
                <tr>
                    <th class="text-md-center" style="width: 60px">No</th>
                    <th>Department</th>
                    <th>Employee</th>
                    <th>Category</th>
                    <th>Requisition</th>
                    <th style="min-width: 120px" class="text-md-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = isset($departments) ? ($departments['current_page'] - 1) * $departments['per_page'] : 0 ?>
                <?php foreach ($departments['data'] as $department): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td><?= $department['department'] ?></td>
                        <td><?= $department['total_employee'] ?></td>
                        <td><?= $department['total_category'] ?></td>
                        <td><?= $department['total_requisition'] ?></td>
                        <td class="text-md-right">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_VIEW)): ?>
                                        <a class="dropdown-item"
                                           href="<?= site_url('master/department/view/' . $department['id']) ?>">
                                            <i class="mdi mdi-eye-outline mr-2"></i> View
                                        </a>
                                    <?php endif; ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_EDIT)): ?>
                                        <a class="dropdown-item"
                                           href="<?= site_url('master/department/edit-category/' . $department['id']) ?>">
                                            <i class="mdi mdi-square-edit-outline mr-2"></i> Manage Category
                                        </a>
                                    <?php endif; ?>
                                    <?php if(!$this->config->item('sso_enable')): ?>
                                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_EDIT)): ?>
                                            <a class="dropdown-item"
                                               href="<?= site_url('master/department/edit/' . $department['id']) ?>">
                                                <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                            </a>
                                        <?php endif; ?>
                                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_DELETE)): ?>
                                            <div class="dropdown-divider d-none"></div>
                                            <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                               data-id="<?= $department['id'] ?>" data-label="<?= $department['department'] ?>" data-title="Department"
                                               data-url="<?= site_url('master/department/delete/' . $department['id']) ?>">
                                                <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($departments['data'])): ?>
                    <tr>
                        <td colspan="6">No departments data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $departments]) ?>
    </div>
</div>

<?php $this->load->view('department/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>