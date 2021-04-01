<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Waiting for Selection</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-selection">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>No Request</th>
                <th>Request</th>
                <th>Status</th>
                <th>Offers</th>
                <th>Selected</th>
                <th style="width: 190px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $statusLabels = [
                RequisitionModel::STATUS_ASK_SELECTION => 'success',
                RequisitionModel::STATUS_READY => 'primary',
            ]
            ?>
            <?php $no = 1 ?>
            <?php foreach ($requisitions as $requisition): ?>
                <tr>
                    <td class="text-md-center"><?= $no++ ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                            <?= $requisition['no_requisition'] ?>
                        </a>
                    </td>
                    <td>
                        <span class="d-block">
                            <?= $requisition['request_title'] ?>
                        </span>
                        <span class="small text-muted">
                            <?= if_empty($requisition['category'], 'Category is not set') ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
                            <?= $requisition['status'] ?>
                        </span>
                    </td>
                    <td><?= $requisition['total_offer'] ?></td>
                    <td><?= $requisition['total_selected'] ?></td>
                    <td class="text-md-right row-requisition"
                        data-request-title="<?= $requisition['request_title'] ?>"
                        data-purchasing-note="<?= $requisition['purchasing_note'] ?>"
                        data-decision-maker-id="<?= $requisition['id_decision_maker'] ?>"
                        data-decision-maker-email="<?= $requisition['decision_maker_email'] ?>">
                        <div class="btn-group">
                            <a href="<?= site_url('purchasing/selection/comparison/' . $requisition['id']) ?>" class="btn btn-sm btn-primary px-3">
                                <i class="mdi mdi-select-compare"></i> Select Vendor
                            </a>
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View Requisition
                                    </a>
                                    <div class="dropdown-divider"></div>
                                <?php endif; ?>
                                <?php if($requisition['status'] == RequisitionModel::STATUS_READY): ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_SELECT)): ?>
                                        <a class="dropdown-item btn-finish-selection" href="<?= site_url('purchasing/selection/finish-selection/' . $requisition['id'] . '?redirect=' . site_url(uri_string())) ?>"
                                           data-request-title="<?= $requisition['request_title'] ?> (<?= $requisition['no_requisition'] ?>)">
                                            <i class="mdi mdi-check-circle-outline mr-2"></i> Finish Selection
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_SELECT)): ?>
                                        <a class="dropdown-item btn-ask-selection" href="<?= site_url('requisition/ask-selection/' . $requisition['id'] . '?redirect=' . site_url(uri_string())) ?>">
                                            <i class="mdi mdi-help-circle-outline mr-2"></i> Re-Ask for Selection
                                        </a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_MANAGE)): ?>
                                        <a class="dropdown-item btn-ready-selection" href="<?= site_url('requisition/set-ready/' . $requisition['id'] . '?redirect=' . site_url(uri_string())) ?>"
                                           data-request-title="<?= $requisition['request_title'] ?> (<?= $requisition['no_requisition'] ?>)">
                                            <i class="mdi mdi-checkbox-marked-outline mr-2"></i> Set Ready Selection
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if($requisition['total_offer']>1): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?= site_url('purchasing/selection/excel_comparison/' . $requisition['id']) ?>">
                                        <i class="mdi mdi-compare mr-2"></i> Excel Comparison
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($requisitions)): ?>
                <tr>
                    <td colspan="7" class="text-center">All pending selections have been proceed</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<form action="#" method="post" id="form-selection">
    <?= _csrf() ?>
    <?= _method('put') ?>
</form>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_SELECT)): ?>
	<?php $this->load->view('offer/_modal_ask_selection') ?>
<?php endif; ?>
<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_MANAGE)): ?>
    <?php $this->load->view('selection/_modal_ready_selection') ?>
<?php endif; ?>

<?php $this->load->view('partials/modals/_confirm') ?>
