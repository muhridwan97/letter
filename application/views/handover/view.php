<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Handover Purchase Order</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="no_purchase">Purchase Order</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="no_purchase">
                                <a href="<?= site_url('purchasing/order/print-order/' . $purchaseOrder['id']) ?>">
                                    <?= $purchaseOrder['no_purchase'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $purchaseOrder['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="request_title">
                                <a href="<?= site_url('requisition/view/' . $purchaseOrder['id_requisition']) ?>">
                                    <?= $purchaseOrder['request_title'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="vendor">Vendor</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="vendor">
                                <?= $purchaseOrder['vendor'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="handover_date">Handover Date</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="handover_date">
                                <?= if_empty(format_date($purchaseOrder['handover_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="handover_note">Handover Note</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="handover_note">
                                <?= if_empty($purchaseOrder['handover_note'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Status</label>
                        <div class="col-sm-8">
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
                            <p class="form-control-plaintext" id="status">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $purchaseOrder['status'], 'secondary') ?>">
                                    <?= $purchaseOrder['status'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="receiving_date">Received Date</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="receiving_date">
                                <?= if_empty(format_date($purchaseOrder['received_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="receiving_date">Received Date User</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="receiving_date">
                                <?= if_empty(format_date($purchaseOrder['received_date_user'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="receiving_note">Receiving Note</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="receiving_note">
                                <?= if_empty($purchaseOrder['receiving_note'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="rating">Rating Purchasing</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="rating">
                                <?= if_empty($purchaseOrder['rating'], '-') ?> / 5
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="rating">Rating User</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="rating">
                                <?= if_empty($purchaseOrder['rating_user'], '-') ?> / 5
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="rating_user_reason">Rating User Reason</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="rating_user_reason">
                                <?= if_empty($purchaseOrder['rating_user_reason'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($purchaseOrder['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchased Items</h4>
            <table class="table responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($selectedOfferItems as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <p class="mb-0"><?= if_empty($item['item_name'], '-') ?></p>
                            <small class="text-muted"><?= $item['brand'] ?></small>
                            <small class="text-muted"><?= if_empty($item['description'], '', '(', ')') ?></small>
                        </td>
                        <td>
                            <?= if_empty(numerical($item['quantity_selected']), '0') ?>
                            <?= if_empty($item['unit'], '-') ?>
                        </td>
                        <td><?= if_empty($item['type'], '-') ?></td>
                        <td><?= if_empty($item['item_description'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Status History</h4>
            <table class="table responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Created By</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($requisitionStatuses as $status): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($status['status'], '-') ?></td>
                        <td><?= if_empty($status['description'], '-') ?></td>
                        <td><?= if_empty(format_date($status['created_at'], 'd F Y H:i'), '-') ?></td>
                        <td><?= if_empty($status['creator_name'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($requisitionStatuses)): ?>
                    <tr>
                        <td colspan="6">No status available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::hasPermission([PERMISSION_ORDER_VIEW, PERMISSION_REQUISITION_CREATE])): ?>
				<a href="<?= site_url('purchasing/handover/print-handover/' . $purchaseOrder['id']) ?>" class="btn btn-primary">
					Print Handover
				</a>
			<?php endif; ?>
        </div>
    </div>
</form>
