<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0"><?= $title ?></h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-handover">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Requester</th>
                <th>Request</th>
                <th class="text-nowrap">No Purchase</th>
                <th class="text-nowrap">Handover Note</th>
                <th class="text-nowrap">Received At</th>
                <th>Status</th>
                <th style="width: 120px" class="text-sm-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $statusLabels = [
                PurchaseOfferModel::STATUS_ON_REVIEW => 'warning',
                PurchaseOfferModel::STATUS_UNSELECTED => 'danger',
                PurchaseOfferModel::STATUS_SELECTED => 'primary',
                PurchaseOfferModel::STATUS_ORDERED => 'info',
                PurchaseOfferModel::STATUS_ASK_CONFIRMATION => 'warning',
                PurchaseOfferModel::STATUS_CONFIRMED => 'primary',
                PurchaseOfferModel::STATUS_UNCONFIRMED => 'danger',
                PurchaseOfferModel::STATUS_COMPLETED => 'success',
            ];
            $this->load->helper('text');
            ?>
            <?php $no = isset($handovers) ? ($handovers['current_page'] - 1) * $handovers['per_page'] : 0 ?>
            <?php foreach ($handovers['data'] as $handover): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $handover['employee_name'] ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $handover['id_requisition']) ?>">
                            <?= $handover['request_title'] ?>
                        </a><br>
                        <small class="text-muted"><?= $handover['no_requisition'] ?></small>
                    </td>
                    <td><?= $handover['no_purchase'] ?></td>
                    <td>
                        <?= character_limiter(if_empty($handover['handover_note'], '-'), 100) ?>
                        <?php if($handover['status'] == PurchaseOfferModel::STATUS_UNCONFIRMED): ?>
                            <br>
                            <small class="text-danger">
                                <strong>REJECT:</strong>
                                <?= character_limiter(if_empty($handover['receiving_note'], '-'), 100) ?>
                            </small>
                        <?php endif; ?>
                    </td>
                    <td><?= if_empty(format_date(if_empty($handover['received_date_user'], $handover['received_date']), 'd F Y'), '-') ?></td>
                    <td>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $handover['status'], 'secondary') ?>">
                            <?= $handover['status'] ?>
                        </span>
                    </td>
                    <td class="text-md-right">
                        <div class="dropdown row-handover"
                             data-no-requisition="<?= $handover['no_requisition'] ?>"
                             data-request-title="<?= $handover['request_title'] ?>"
                             data-no-purchase="<?= $handover['no_purchase'] ?>"
                             data-received-date="<?= format_date($handover['received_date'], 'd/m/Y') ?>">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url('purchasing/handover/view/' . $handover['id_purchase_order']) ?>">
                                    <i class="mdi mdi-eye-outline mr-2"></i> View
                                </a>
								<?php if(AuthorizationModel::hasPermission([PERMISSION_ORDER_VIEW, PERMISSION_REQUISITION_CREATE])): ?>
									<a class="dropdown-item" href="<?= site_url('purchasing/handover/print-handover/' . $handover['id_purchase_order']) ?>">
										<i class="mdi mdi-cloud-print-outline mr-2"></i> Print Handover
									</a>
								<?php endif; ?>
                                <?php if($handover['status'] == PurchaseOfferModel::STATUS_ASK_CONFIRMATION && $handover['id_employee'] == UserModel::loginData('id_employee')): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-confirm" href="<?= site_url('purchasing/handover/confirm/' . $handover['id_purchase_order']) ?>">
                                        <i class="mdi mdi-check mr-2"></i> Confirm
                                    </a>
                                    <a class="dropdown-item btn-validate" data-validate="unconfirmed" href="<?= site_url('purchasing/handover/unconfirmed/' . $handover['id_purchase_order']) ?>">
                                        <i class="mdi mdi-close mr-2"></i> Unconfirmed
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($handovers['data'])): ?>
                <tr>
                    <td colspan="8">No handover data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $handovers]) ?>
    </div>
</div>

<?php $this->load->view('handover/_modal_filter') ?>
<?php $this->load->view('handover/_modal_confirm_order') ?>
<?php $this->load->view('partials/modals/_validate') ?>
