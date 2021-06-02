<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Repository</h5>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(!$this->config->item('sso_enable')): ?>
                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_REPOSITORY_CREATE)): ?>
                        <a href="<?= site_url('repository/create') ?>" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-plus-box-outline mr-2"></i>Upload
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="<?= $repositories['total_data'] > 3 ? 'table-responsive' : '' ?>">
            <table class="table table-hover table-sm mt-3 responsive" id="table-repository">
                <thead>
                <tr>
                    <th class="text-md-center" style="width: 60px">No</th>
                    <th>Nama Surat</th>
                    <th style="width: 100px">Created At</th>
                    <th style="min-width: 20px" class="text-md-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = isset($repositories) ? ($repositories['current_page'] - 1) * $repositories['per_page'] : 0 ?>
                <?php foreach ($repositories['data'] as $repository): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td><a href="<?= base_url().'uploads/'.$repository['src']?>"><?= $repository['name'] ?></a></td>
                        <td><?= format_date($repository['created_at'], 'd F Y') ?></td>
                        <td class="text-md-right">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right row-repository"
                                     data-id="<?= $repository['id'] ?>"
                                     data-label="<?= $repository['name'] ?>">
                                    <!-- <?php if(AuthorizationModel::isAuthorized(PERMISSION_REPOSITORY_VIEW)): ?>
                                        <a class="dropdown-item" href="<?= site_url('repository/view/' . $repository['id']) ?>">
                                            <i class="mdi mdi-eye-outline mr-2"></i> View
                                        </a>
                                    <?php endif; ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_REPOSITORY_EDIT)): ?>
                                        <a class="dropdown-item" href="<?= site_url('repository/edit/' . $repository['id']) ?>">
                                            <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                        </a>
                                    <?php endif; ?> -->
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_REPOSITORY_DELETE)): ?>
                                        <!-- <div class="dropdown-divider"></div> -->
                                        <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                            data-id="<?= $repository['id'] ?>" data-label="<?= $repository['name'] ?>" data-title="Repository"
                                            data-url="<?= site_url('repository/delete/' . $repository['id']) ?>">
                                            <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($repositories['data'])): ?>
                    <tr>
                        <td colspan="4">No repositories data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $repositories]) ?>
    </div>
</div>

<?php $this->load->view('repository/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_REPOSITORY_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
