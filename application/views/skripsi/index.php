<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Skripsi</h5>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(!$this->config->item('sso_enable')): ?>
                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_CREATE)): ?>
                        <a href="<?= site_url('skripsi/create') ?>" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="<?= $skripsis['total_data'] > 3 ? 'table-responsive' : '' ?>">
            <table class="table table-hover table-sm mt-3 responsive" id="table-skripsi">
                <thead>
                <tr>
                    <th class="text-md-center" style="width: 60px">No</th>
                    <th>Judul</th>
                    <th>Mahasiswa</th>
                    <th>NIM</th>
                    <th>Pembimbing</th>
                    <th>NIP</th>
                    <th style="width: 20px">Status</th>
                    <th style="min-width: 20px" class="text-md-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $statuses = [
                    SkripsiModel::STATUS_ACTIVE => 'success',
                    SkripsiModel::STATUS_REJECTED => 'danger',
                    SkripsiModel::STATUS_PENDING => 'default',
                ]
                ?>
                <?php $no = isset($skripsis) ? ($skripsis['current_page'] - 1) * $skripsis['per_page'] : 0 ?>
                <?php foreach ($skripsis['data'] as $skripsi): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td><?= $skripsi['judul'] ?></td>
                        <td><?= $skripsi['nama_mahasiswa'] ?></td>
                        <td><?= $skripsi['no_student'] ?></td>
                        <td><?= $skripsi['nama_pembimbing'] ?></td>
                        <td><?= $skripsi['no_lecturer'] ?></td>
                        <td>
                            <label class="badge badge-<?= $statuses[$skripsi['status']] ?>">
                                <?= $skripsi['status'] ?>
                            </label>
                        </td>
                        <td class="text-md-right">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right row-skripsi"
                                     data-id="<?= $skripsi['id'] ?>"
                                     data-label="<?= $skripsi['name'] ?>">
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_VIEW)): ?>
                                        <a class="dropdown-item" href="<?= site_url('master/skripsi/view/' . $skripsi['id']) ?>">
                                            <i class="mdi mdi-eye-outline mr-2"></i> View
                                        </a>
                                    <?php endif; ?>
                                    <?php if(!$this->config->item('sso_enable')): ?>
                                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_EDIT)): ?>
                                            <a class="dropdown-item" href="<?= site_url('master/skripsi/edit/' . $skripsi['id']) ?>">
                                                <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                            </a>
                                        <?php endif; ?>
                                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_DELETE)): ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                               data-id="<?= $skripsi['id'] ?>" data-label="<?= $skripsi['name'] ?>" data-title="Skripsi"
                                               data-url="<?= site_url('master/skripsi/delete/' . $skripsi['id']) ?>">
                                                <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($skripsis['data'])): ?>
                    <tr>
                        <td colspan="8">No skripsis data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $skripsis]) ?>
    </div>
</div>

<?php $this->load->view('skripsi/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
