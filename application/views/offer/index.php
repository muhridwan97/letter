<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Active Requisition</h4>
            <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=requisition" class="btn btn-info btn-sm pr-2 pl-2">
                <i class="mdi mdi-file-download-outline mr-0"></i>
            </a>
        </div>

        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Requisition</th>
                <th>Deadline</th>
                <th>Urgency</th>
                <th class="text-nowrap">Total Offers</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $statusLabels = [
                'LOW' => 'success',
                'NORMAL' => 'primary',
                'HIGH' => 'warning',
                'VERY HIGH' => 'danger',
            ]
            ?>
            <?php $no = 1 ?>
            <?php foreach ($activeRequisitions as $requisition): ?>
                <tr>
                    <td class="text-md-center"><?= $no++ ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                            <?= $requisition['request_title'] ?>
                        </a>
                    </td>
                    <td><?= format_date($requisition['deadline'], 'd F Y') ?></td>
                    <td>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['urgency'], 'secondary') ?>">
                            <?= $requisition['urgency'] ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <a href="<?= site_url('purchasing/selection/comparison/' . $requisition['id']) ?>">
                            <?= numerical($requisition['total_offer']) ?> offers
                        </a>
                    </td>
                    <td class="text-md-right row-requisition" data-request-title="<?= $requisition['request_title'] ?>">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_CREATE)): ?>
                                    <a class="dropdown-item" href="<?= site_url('purchasing/offer/create/' . $requisition['id']) ?>">
                                        <i class="mdi mdi-file-document-box-multiple-outline mr-2"></i> Add Offer
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <?php if($requisition['total_offer'] == 0): ?>
                                        <a class="dropdown-item btn-ready-selection disabled" href="javascript:void(0)" style="pointer-events: auto">
                                            <i class="mdi mdi-check-circle-outline mr-2"></i> No vendor offers
                                        </a>
                                    <?php else: ?>
                                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_DELETE) || $requisition['total_offer'] >= 3): ?>
                                            <a class="dropdown-item btn-ask-selection" data-confirm-top-manager="<?= if_empty($requisition['confirm_top_manager'], 0) ?>" href="<?= site_url('requisition/ask-selection/' . $requisition['id'] . '?redirect=' . site_url(uri_string())) ?>">
                                                <i class="mdi mdi-help-circle-outline mr-2"></i> Ask for Selection
                                            </a>
                                        <?php else: ?>
                                            <a class="dropdown-item btn-ready-selection disabled" href="javascript:void(0)">
                                                <i class="mdi mdi-check-circle-outline mr-2"></i> Find <?= 3 - $requisition['total_offer'] ?> more vendors
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <a class="dropdown-item" href="<?= site_url('purchasing/selection/comparison/' . $requisition['id']) ?>">
                                    <i class="mdi mdi-select-compare mr-2"></i> Comparison
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($activeRequisitions)): ?>
                <tr>
                    <td colspan="6" class="text-center">All requisition has been proceed</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0">Purchase Offers</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=offer" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_VIEW)): ?>
                    <a href="<?= site_url('purchasing/offer/create') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-offer">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Requisition</th>
                <th>Vendor</th>
                <th>Item</th>
                <th>Total</th>
                <th class="text-nowrap">Lead Time</th>
                <th>Status</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
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
            ]
            ?>
            <?php $no = isset($purchaseOffers) ? ($purchaseOffers['current_page'] - 1) * $purchaseOffers['per_page'] : 0 ?>
            <?php foreach ($purchaseOffers['data'] as $offer): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $offer['id_requisition']) ?>">
                            <?= $offer['request_title'] ?>
                        </a>
                    </td>
                    <td>
                        <a href="<?= site_url('master/vendor/view/' . $offer['id_vendor']) ?>">
                            <?= $offer['vendor'] ?>
                        </a>
                    </td>
                    <td><?= numerical($offer['total_item_quantity']) ?></td>
                    <td>Rp. <?= numerical($offer['total_price']) ?></td>
                    <td><?= format_date($offer['lead_time'], 'd F Y') ?></td>
                    <td>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $offer['status'], 'secondary') ?>">
                            <?= $offer['status'] ?>
                        </span>
                    </td>
                    <td class="text-md-right">
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('purchasing/offer/view/' . $offer['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View
                                    </a>
                                <?php endif; ?>
                                <?php if($offer['status'] == 'ON REVIEW'): ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_EDIT)): ?>
                                        <a class="dropdown-item" href="<?= site_url('purchasing/offer/edit/' . $offer['id']) ?>">
                                            <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_DELETE)): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                           data-id="<?= $offer['id'] ?>" data-label="Offer from <?= $offer['vendor'] ?>" data-title="Purchase Offer"
                                           data-url="<?= site_url('purchasing/offer/delete/' . $offer['id']) ?>">
                                            <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($purchaseOffers['data'])): ?>
                <tr>
                    <td colspan="8">No offers data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $purchaseOffers]) ?>
    </div>
</div>

<?php $this->load->view('offer/_modal_filter') ?>
<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_SELECT)): ?>
    <?php $this->load->view('offer/_modal_ask_selection') ?>
<?php endif; ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
