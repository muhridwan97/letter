<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0"><?= $title ?></h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
                <a href="<?= site_url('master/vendor/offers') ?>" class="btn btn-sm btn-primary">
                    <i class="mdi mdi-format-list-numbered mr-2"></i>Offers
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_CREATE)): ?>
                    <span data-toggle="tooltip"data-title="Send link identity company" >
                        <a href="#modal-send" data-toggle="modal" class="btn btn-danger btn-sm pr-2 pl-2">
                            <i class="mdi mdi-send mr-0"></i>
                        </a>
                    </span>
                    <a href="<?= site_url('master/vendor/create') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Vendor</th>
                <th>Contact</th>
                <th>Email</th>
                <th>City</th>
                <th>Province</th>
                <th>Offer</th>
                <th>Selected</th>
                <th>Rating</th>
                <th>Is Active</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = isset($vendors) ? ($vendors['current_page'] - 1) * $vendors['per_page'] : 0 ?>
            <?php foreach ($vendors['data'] as $vendor): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $vendor['vendor'] ?></td>
                    <td><?= if_empty($vendor['contact'], '-') ?></td>
                    <td><?= if_empty($vendor['email'], '-') ?></td>
                    <td><?= if_empty($vendor['cities'], '-') ?></td>
                    <td><?= if_empty($vendor['provinces'], '-') ?></td>
                    <td>
                        <?php if(empty($vendor['total_offer'])): ?>
                            0
                        <?php else: ?>
                            <a href="<?= site_url('purchasing/offer?vendors=' . $vendor['id'])?>">
                                <?= $vendor['total_offer'] ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if(empty($vendor['total_selected'])): ?>
                            0
                        <?php else: ?>
                            <a href="<?= site_url('purchasing/offer?vendors=' . $vendor['id'] . '&statuses[]=SELECTED&statuses[]=UNCONFIRMED&statuses[]=CONFIRMED&statuses[]=ASK+CONFIRMATION&statuses[]=ORDERED&statuses[]=COMPLETED')?>">
                                <?= $vendor['total_selected'] ?>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php $totalRating = round(if_empty($vendor['average_rating'], 0)) ?>
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <?php if ($i < $totalRating): ?>
                                <i class="mdi mdi-star"></i>
                            <?php else: ?>
                                <i class="mdi mdi-star-outline"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </td>
					<td class="text-md-center">
						<div class="badge badge-<?= $vendor['is_active'] ? 'success' : 'danger' ?>">
							<?= $vendor['is_active'] ? 'YES' : 'NO' ?>
						</div>
					</td>
                    <td class="text-md-right">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/vendor/view/' . $vendor['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_EDIT)): ?>
                                    <a class="dropdown-item" href="<?= site_url('master/vendor/edit/' . $vendor['id']) ?>">
                                        <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                    </a>
                                <?php endif; ?>
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_DELETE)): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                       data-id="<?= $vendor['id'] ?>" data-label="<?= $vendor['vendor'] ?>" data-title="Vendor"
                                       data-url="<?= site_url('master/vendor/delete/' . $vendor['id']) ?>">
                                        <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($vendors['data'])): ?>
                <tr>
                    <td colspan="9">No vendors data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $vendors]) ?>
    </div>
</div>

<?php $this->load->view('vendor/_modal_filter') ?>
<?php $this->load->view('vendor/_modal_send') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
