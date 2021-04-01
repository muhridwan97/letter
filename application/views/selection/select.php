<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Selecting Vendor</h4>
        <p class="text-muted">Selecting vendor who take requisition order</p>
        <form class="form-plaintext">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $requisition['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="request_title">
                                <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                    <?= $requisition['request_title'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="deadline">Deadline</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline">
                                <?= format_date($requisition['deadline'], 'd F Y') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="requester">
                                <?= $requisition['employee_name'] ?> (<?= if_empty($requisition['supervisor_name'], 'No SPV') ?>)
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="urgency">Urgency</label>
                        <div class="col-sm-9">
                            <?php
                            $statusLabels = [
                                'LOW' => 'success',
                                'NORMAL' => 'primary',
                                'HIGH' => 'warning',
                                'VERY HIGH' => 'danger',
                            ]
                            ?>
                            <p class="form-control-plaintext" id="urgency">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['urgency'], 'secondary') ?>">
                                    <?= $requisition['urgency'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="category">Category</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="category">
                                <?= if_empty($requisition['category'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title">Select Vendor</h4>
                <p class="text-muted">Selecting vendor who take requisition order</p>
            </div>
            <span>
                <strong class="text-success"><?= count(array_filter($purchaseOffers, function($offer) {
                        return in_array($offer['status'], [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_COMPLETED]);
                    })) ?> / <?= count($purchaseOffers) ?>
                    offers</strong> selected
            </span>
        </div>

        <table class="table responsive" id="table-comparison">
            <thead>
            <tr>
                <th>No</th>
                <th>Vendor</th>
                <th>Rating</th>
                <th>Lead Time</th>
                <th>TOP</th>
                <th>Selected</th>
                <th>Document</th>
            </tr>
            </thead>

            <?php $no = 1;
            foreach ($purchaseOffers as $offer): ?>

                <tr data-id-purchase-offer="<?= $offer['id'] ?>"
                    data-request-title="<?= $offer['request_title'] ?>"
                    data-vendor="<?= $offer['vendor'] ?>" style="margin-bottom: -1px"
                    class="mb-md-auto row-offer <?= $offer['status'] == PurchaseOfferModel::STATUS_SELECTED ? 'success' : '' ?>">
                    <td><?= $no++ ?></td>
                    <td>
                        <a href="<?= site_url('purchasing/offer/view/' . $offer['id']) ?>">
                            <?= $offer['vendor'] ?>
                        </a>
                    </td>
                    <td class="text-nowrap" data-search="<?= $offer['vendor_data']['average_rating'] ?>">
                        <?php $totalRating = round(if_empty($offer['vendor_data']['average_rating'], 0)) ?>
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <?php if ($i < $totalRating): ?>
                                <i class="mdi mdi-star"></i>
                            <?php else: ?>
                                <i class="mdi mdi-star-outline"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        &nbsp; (<?= numerical($offer['vendor_data']['average_rating'], 1); ?>)
                    </td>
                    <td>
                        <p class="mb-0"><?= format_date($offer['lead_time'], 'd F Y') ?></p>
                        <span class="text-muted">
                                <?php $differenceDay = difference_date($offer['lead_time'], $requisition['deadline']); ?>
                            <?php if ($differenceDay == 0): ?>
                                In Time
                            <?php elseif ($differenceDay < 0): ?>
                                <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> late
                            <?php else: ?>
                                <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> early
                            <?php endif; ?>
                            </span>
                    </td>
                    <td><?= numerical($offer['top']) ?> Days</td>
                    <td><?= $offer['vendor_data']['total_selected'] ?> X</td>
                    <td>
                        <?php if (empty($offer['document'])): ?>
                            No document
                        <?php else: ?>
                            <a href="<?= asset_url($offer['document']) ?>">
                                Attachment
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr class="ml-5">
                    <td colspan="7">
                        <?php $selectedStatus = [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_COMPLETED] ?>
                        <div class="card border-<?= in_array($offer['status'], $selectedStatus) ? 'success' : 'secondary' ?> mb-2">
                            <?php if (in_array($offer['status'], $selectedStatus)): ?>
                                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                    <strong><?= $offer['vendor'] ?></strong>
                                    <span class="badge badge-success"><?= $offer['status'] ?></span>
                                </div>
                            <?php else: ?>
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <strong><?= $offer['vendor'] ?></strong>
                                    <span class="badge <?= $offer['status'] == PurchaseOfferModel::STATUS_UNSELECTED  ? 'badge-danger' : 'badge-info' ?>">
                                        <?= $offer['status'] ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body p-3">
                                <table class="table table-sm table-bordered responsive">
                                    <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Brand</th>
                                        <th>Quantity</th>
                                        <th class="text-md-right">Unit Price</th>
                                        <th class="text-md-right">Unit Disc</th>
                                        <th class="text-right">Total Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($offer['items'] as $item): ?>
                                        <tr class="<?= in_array($offer['status'], $selectedStatus) && !empty($item['quantity_selected']) ? ' table-success' : '' ?>">
                                            <td>
												<span class="d-block"><?= $item['item_name'] ?></span>
												<small class="text-muted"><?= $item['specification'] ?></small>
											</td>
                                            <td><?= if_empty($item['brand'], '-') ?></td>
                                            <td><?= numerical($item['quantity']) ?> <?= $item['unit'] ?></td>
                                            <td class="text-md-right">
                                                Rp. <?= numerical($item['price']) ?>
                                            </td>
                                            <td class="text-md-right">
                                                Rp. <?= numerical($item['discount']) ?>
                                            </td>
                                            <td class="text-md-right">
                                                Rp. <?= numerical($item['total']) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr class="row-no-header">
                                        <td colspan="3" rowspan="4">
                                            <p class="mb-0"><strong>Notes: </strong>
                                                <?= if_empty($offer['description'], '-') ?>
                                            </p>
                                        </td>
                                        <td colspan="2" class="text-md-right"><strong>Sub Total</strong></td>
                                        <td class="text-md-right">
                                            <strong>Rp. <?= numerical($offer['extended_total']) ?></strong>
                                        </td>
                                    </tr>
                                    <tr class="row-no-header">
                                        <td colspan="2" class="text-md-right"><strong>Offer Discount</strong></td>
                                        <td class="text-md-right">
                                            <strong>Rp. <?= numerical($offer['offer_discount']) ?></strong>
                                        </td>
                                    </tr>
                                    <tr class="row-no-header">
                                        <?php
                                        if($offer['vendor_data']['is_pkp']){
                                            $taxAmount = floor(10 / 100 * $offer['total_price']);
                                            $taxPercent = 10;
                                        } else {
                                            $taxAmount = 0;
                                            $taxPercent = 0;
                                        }
                                        ?>
                                        <td colspan="2" class="text-md-right"><strong>Tax <?= $taxPercent ?>%</strong></td>
                                        <td class="text-md-right">
                                            <strong>Rp. <?= numerical($taxAmount) ?></strong>
                                        </td>
                                    </tr>
                                    <tr class="row-no-header">
                                        <td colspan="2" class="text-md-right"><strong>Total Price</strong></td>
                                        <td class="text-md-right">
                                            <strong>Rp. <?= numerical($offer['total_price'] + $taxAmount) ?></strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>

            <?php endforeach; ?>

        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Admin Recommendation</h4>
        <p class="text-danger mb-0">
            <strong>
                <?= if_empty($requisition['purchasing_note'], 'No recommendation from Purchasing') ?>
            </strong>
        </p>
    </div>
</div>

<?php if($requisition['status'] == RequisitionModel::STATUS_ASK_SELECTION): ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Put your selection here</h4>
            <form action="<?= site_url('purchasing/vendor-selection/save-selection/' . $requisition['id'] . '/' . $token) ?>" method="post">
                <?= _csrf() ?>
                <input type="hidden" name="email" value="<?= get_url_param('email') ?>">
                <div class="form-group">
                    <label for="selection_note">Selection Note</label>
                    <textarea name="selection_note" id="selection_note" class="form-control" required maxlength="500"
                              rows="4" placeholder="Select the vendors and details"></textarea>
                    <span class="form-text">Eg. I prefer 5 units item A from Vendor 1 and 3 Units item B from Vendor 2</span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-block"
                            data-toggle="one-touch" data-touch-message="Submitting...">SEND DECISION</button>
                </div>
            </form>
        </div>
    </div>
    <?php $this->load->view('partials/modals/_confirm') ?>
<?php else: ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Selection Note</h4>
            <p class="text-danger mb-0">
                <strong>
                    <?= if_empty($requisition['selection_note'], 'No selection note') ?>
                </strong>
            </p>
        </div>
    </div>
<?php endif; ?>
