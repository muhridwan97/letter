<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchase Offer</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $purchaseOffer['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="request_title">
                                <a href="<?= site_url('requisition/view/' . $purchaseOffer['id_requisition']) ?>">
                                    <?= $purchaseOffer['request_title'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="vendor">Vendor</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="vendor">
                                <a href="<?= site_url('master/vendor/view/' . $purchaseOffer['id_vendor']) ?>">
                                    <?= $purchaseOffer['vendor'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm-9">
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
                                <span class="badge badge-<?= get_if_exist($statusLabels, $purchaseOffer['status'], 'secondary') ?>">
                                    <?= $purchaseOffer['status'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="lead_time">Lead Time</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="lead_time">
                                <?= format_date($purchaseOffer['lead_time'], 'd F Y') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="top">TOP</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="top">
                                <?= if_empty($purchaseOffer['top'], '-') ?> days
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="attachment">Attachment</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="attachment">
                                <?php if(empty($purchaseOffer['document'])): ?>
                                    -
                                <?php else: ?>
                                    <a href="<?= asset_url($purchaseOffer['document']) ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($purchaseOffer['description'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($purchaseOffer['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($purchaseOffer['creator_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($purchaseOffer['updated_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Offer Items</h4>
            <table class="table responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th class="text-md-right text-nowrap">Unit Price</th>
                    <th class="text-md-right text-nowrap">Unit Discount</th>
                    <th class="text-md-right text-nowrap">Ext Price</th>
                    <th class="text-md-right text-nowrap">Ext Discount</th>
                    <th class="text-md-right text-nowrap">Total Price</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($purchaseOfferItems as $item): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <p class="mb-0"><?= if_empty($item['item_name'], '-') ?></p>
                            <small class="text-muted"><?= $item['brand'] ?></small>
                            <small class="text-muted"><?= $item['specification'] ?></small>
                            <small class="text-muted"><?= if_empty($item['description'], '', '(', ')') ?></small>
                        </td>
                        <td>
                            <?= if_empty(numerical($item['quantity']), '0') ?>
                            <?= if_empty($item['unit'], '-') ?>
                        </td>
                        <td class="text-md-right text-nowrap">Rp. <?= numerical($item['price']) ?></td>
                        <td class="text-md-right text-nowrap">(Rp. <?= numerical($item['discount']) ?>)</td>
                        <td class="text-md-right text-nowrap">Rp. <?= numerical($item['extended_price']) ?></td>
                        <td class="text-md-right text-nowrap">(Rp. <?= numerical($item['extended_discount']) ?>)</td>
                        <td class="text-md-right text-nowrap"><strong>Rp. <?= numerical($item['total']) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="7">Sub Total</th>
                    <th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOffer['extended_total']) ?></th>
                </tr>
                <tr>
                    <th colspan="7">Offer Discount</th>
                    <th class="text-md-right text-nowrap">(Rp. <?= numerical($purchaseOffer['offer_discount']) ?>)</th>
                </tr>
                <tr>
                    <th colspan="7">Total Price</th>
                    <th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOffer['total_price']) ?></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php if(!in_array($purchaseOffer['status'], [PurchaseOfferModel::STATUS_ON_REVIEW, PurchaseOfferModel::STATUS_UNSELECTED])): ?>
        <div class="card grid-margin">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="card-title mb-0">Selected Items</h4>
                    <?php if(!empty($purchaseOffer['id_purchase_order'])): ?>
                        <a href="<?= site_url('purchasing/order/print-order/' . $purchaseOffer['id_purchase_order']) ?>" class="btn btn-sm btn-primary">
                            Print Purchase Order
                        </a>
                    <?php else: ?>
                        <span class="badge badge-danger">Waiting Order</span>
                    <?php endif; ?>
                </div>
                <table class="table responsive">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th class="text-md-right text-nowrap">Unit Price</th>
                        <th class="text-md-right text-nowrap">Unit Discount</th>
                        <th class="text-md-right text-nowrap">Ext Price</th>
                        <th class="text-md-right text-nowrap">Ext Discount</th>
                        <th class="text-md-right text-nowrap">Total Price</th>
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
								<small class="text-muted"><?= $item['specification'] ?></small>
                                <small class="text-muted"><?= if_empty($item['description'], '', '(', ')') ?></small>
                            </td>
                            <td>
                                <?= if_empty(numerical($item['quantity_selected']), '0') ?>
                                <?= if_empty($item['unit'], '-') ?>
                            </td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($item['price']) ?></td>
                            <td class="text-md-right text-nowrap">(Rp. <?= numerical($item['discount']) ?>)</td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($item['selected_extended_price']) ?></td>
                            <td class="text-md-right text-nowrap">(Rp. <?= numerical($item['selected_extended_discount']) ?>)</td>
                            <td class="text-md-right text-nowrap"><strong>Rp. <?= numerical($item['selected_total']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="7">Sub Total</th>
                        <th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOffer['selected_extended_total']) ?></th>
                    </tr>
                    <tr>
                        <th colspan="7">Offer Discount</th>
                        <th class="text-md-right text-nowrap">(Rp. <?= numerical($purchaseOffer['offer_discount']) ?>)</th>
                    </tr>
                    <tr>
                        <th colspan="7">Total Price</th>
                        <th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOffer['selected_total_price']) ?></th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if($purchaseOffer['status'] == 'ON REVIEW'): ?>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_EDIT)): ?>
                    <a href="<?= site_url('purchasing/offer/edit/' . $purchaseOffer['id']) ?>" class="btn btn-primary">
                        Edit Purchase Offer
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</form>
