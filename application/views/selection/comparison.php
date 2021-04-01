<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title">Selecting Vendor</h4>
                <p class="text-muted">Selecting vendor who take requisition order</p>
            </div>
            <div>
                <a href="<?= site_url('requisition/print-offer/' . $requisition['id']) ?>" class="btn btn-primary btn-sm">
                    Print Offers
                </a>
                <a href="<?= site_url('requisition/print-comparison/' . $requisition['id']) ?>" class="btn btn-success btn-sm">
                    Print Comparison
                </a>
            </div>
        </div>
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
                                <?= $requisition['employee_name'] ?> (<?= if_empty($requisition['supervisor_name'], 'No SPV', 'SPV ') ?>)
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
        <h4 class="card-title">Request Items</h4>
        <table class="table table-sm responsive">
            <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Selected</th>
                <th>Unit</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = 1 ?>
            <?php foreach ($requisitionItems as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= if_empty($item['item_name'], '-') ?></td>
                    <td><?= if_empty($item['brand'], '-') ?></td>
                    <td><?= if_empty($item['type'], '-') ?></td>
                    <td><?= numerical($item['quantity']) ?></td>
                    <td><?= numerical($item['total_selected']) ?></td>
                    <td><?= if_empty($item['unit'], '-') ?></td>
                    <td><?= if_empty($item['description'], '-') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Admin Recommendation</h4>
        <p class="text-success mb-0">
            <strong>
                <?= if_empty($requisition['purchasing_note'], 'No recommendation from Purchasing') ?>
            </strong>
        </p>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Selection Note</h4>
        <p class="text-danger mb-0">
            <strong>
                <?= if_empty($requisition['selection_note'], 'No selection note from Manager') ?>
            </strong>
        </p>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title mb-1">Select Vendor</h4>
                <p class="text-muted">Selecting vendor who take requisition order</p>
            </div>
            <span class="d-inline-block mb-3">
                <strong class="text-success"><?= count(array_filter($purchaseOffers, function($offer) {
                        return in_array($offer['status'], [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_COMPLETED]);
                    })) ?> / <?= count($purchaseOffers) ?>
                    offers</strong> selected
            </span>
        </div>

        <?php
        $needMoreItem = false;
        foreach ($differenceItems as $difference) {
            if ($difference['difference_total'] > 0) {
                $needMoreItem = true;
                break;
            }
        }
        ?>

        <?php if ($needMoreItem): ?>
            <div class="alert alert-danger">
                <h4>Vendor offer incomplete</h4>
                <p class="mb-2">Please find another vendor to complete requisition item below:</p>
                <ul class="mb-1">
                    <?php foreach ($differenceItems as $difference): ?>
                        <li>
                            <strong><?= $difference['item_name'] ?></strong>:
                            find <?= $difference['difference_total'] ?> more item.
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
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
                    <th width="70px" class="text-md-right">Action</th>
                </tr>
                </thead>

                <?php $no = 1;
                $foundSelected = array_search(PurchaseOfferModel::STATUS_UNSELECTED, array_column(if_empty($purchaseOffers, []), 'status'));
                foreach ($purchaseOffers as $offer): ?>

                    <tr
                        data-id-purchase-offer="<?= $offer['id'] ?>"
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
                            <a href="<?= site_url('report/satisfied-detail?status=COMPLETED&vendor='.$offer['vendor'])?>" target="_blank">
                            <?php $totalRating = round(if_empty($offer['vendor_data']['average_rating'], 0)) ?>
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <?php if ($i < $totalRating): ?>
                                    <i class="mdi mdi-star"></i>
                                <?php else: ?>
                                    <i class="mdi mdi-star-outline"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            &nbsp; (<?= numerical($offer['vendor_data']['average_rating'], 1); ?>)
                            </a>
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
                        <td class="text-md-right">
                            <?php if ($requisition['status'] == RequisitionModel::STATUS_READY): ?>
                                <?php if ($offer['status'] == PurchaseOfferModel::STATUS_ON_REVIEW): ?>
                                    <?php if($foundSelected <= 0): ?>
                                        <a href="<?= site_url('purchasing/selection/select/' . $offer['id'] . '?redirect=' . site_url(uri_string())) ?>"
                                           class="btn btn-success px-3 btn-select text-nowrap">
                                            <i class="icon-check"></i> Select All
                                        </a>
                                    <?php else: ?>
                                        Pick Manual
                                    <?php endif; ?>
                                <?php elseif ($offer['status'] == PurchaseOfferModel::STATUS_SELECTED): ?>
                                    <a href="<?= site_url('purchasing/selection/cancel/' . $offer['id'] . '?redirect=' . site_url(uri_string())) ?>"
                                       class="btn btn-danger px-3 btn-cancel text-nowrap">
                                        <i class="icon-close"></i> Cancel All
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= $offer['status'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr class="ml-3">
                        <td colspan="8">
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
                                            <th class="text-md-right">Qty Offer</th>
                                            <th class="text-md-right">Unit Price</th>
                                            <th class="text-md-right">Unit Disc</th>
                                            <th class="text-md-right">Total Price</th>
                                            <th class="text-md-right">Qty Selected</th>
                                            <th class="text-md-right">Total Selected</th>
                                            <th class="text-md-center" style="width: 100px">Take</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($offer['items'] as $item): ?>
                                            <tr class="row-offer-item<?= in_array($offer['status'], $selectedStatus) && !empty($item['quantity_selected']) ? ' table-success' : '' ?>"
                                                data-item="<?= $item['item_name'] ?> (<?= if_empty($item['brand'], 'No Specific Brand') ?>)"
                                                data-unit="<?= $item['unit'] ?>"
                                                data-quantity="<?= $item['quantity'] ?>"
                                                data-selected="<?= $item['quantity_selected'] ?>">
                                                <td>
                                                    <span class="d-block"><?= $item['item_name'] ?></span>
                                                    <small class="text-muted"><?= if_empty($item['brand'], '', '', '', true) ?></small>
													<small class="text-muted"><?= $item['specification'] ?></small>
                                                </td>
                                                <td class="text-md-right">
                                                    <?= numerical($item['quantity']) ?> <?= $item['unit'] ?>
                                                </td>
                                                <td class="text-md-right">
                                                    Rp. <?= numerical($item['price']) ?>
                                                </td>
                                                <td class="text-md-right">
                                                    Rp. <?= numerical($item['discount']) ?>
                                                </td>
                                                <td class="text-md-right">
                                                    Rp. <?= numerical($item['total']) ?>
                                                </td>
                                                <td class="text-md-right">
                                                    <strong>
                                                        <?= numerical($item['quantity_selected']) ?> <?= $item['unit'] ?>
                                                    </strong>
                                                </td>
                                                <td class="text-md-right">
                                                    <strong>
                                                        Rp. <?= numerical($item['selected_total']) ?>
                                                    </strong>
                                                </td>
                                                <td class="text-md-center">
                                                    <?php if ($requisition['status'] == RequisitionModel::STATUS_READY): ?>
                                                        <?php
                                                        $activeSelect = true;
                                                        $maxAllowTake = $item['quantity'];
                                                        foreach ($requisitionItems as $requestItem) {
                                                            if ($requestItem['id'] == $item['id_requisition_item']) {
                                                                $maxAllowTake = $item['quantity_selected'] + ($requestItem['quantity'] - $requestItem['total_selected']);
                                                                if ($maxAllowTake > $item['quantity']) {
                                                                    $maxAllowTake = $item['quantity'];
                                                                }
                                                                if ($requestItem['total_selected'] >= $item['quantity_needed'] && $item['quantity_selected'] == 0) {
                                                                    $activeSelect = false;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <?php if($activeSelect): ?>
                                                            <a href="<?= site_url('purchasing/selection/select/' . $offer['id'] . '/' . $item['id'] . '?redirect=' . site_url(uri_string())) ?>"
                                                               data-max-allow-take="<?= $maxAllowTake ?>"
                                                               class="btn btn-sm btn-select-item px-3<?= empty($item['quantity_selected']) ? ' btn-success' : ' btn-info' ?>">
                                                                <i class="icon-check"></i> <?= empty($item['quantity_selected']) ? ' Select' : ' Change' ?>
                                                            </a>
                                                        <?php else: ?>
                                                            ALL ACQUIRED
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if(empty($item['quantity_selected'])): ?>
                                                            UNSELECTED
                                                        <?php else: ?>
                                                            <?= $offer['status'] ?>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="row-no-header">
                                            <td colspan="2" rowspan="4">
                                                <p class="mb-0"><strong>Notes: </strong>
                                                    <?= if_empty($offer['description'], '-') ?>
                                                </p>
                                            </td>
                                            <td colspan="2" class="text-md-right"><strong>Sub Total</strong></td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($offer['extended_total']) ?></strong>
                                            </td>
                                            <td rowspan="4"></td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($offer['selected_extended_total']) ?></strong>
                                            </td>
                                            <td rowspan="4"></td>
                                        </tr>
                                        <tr class="row-no-header">
                                            <td colspan="2" class="text-md-right"><strong>Offer Discount</strong></td>
                                            <td class="text-md-right">
                                                <strong>(Rp. <?= numerical($offer['offer_discount']) ?>)</strong>
                                            </td>
                                            <td class="text-md-right">
                                                <strong>
                                                    (Rp. <?= numerical($offer['status'] == PurchaseOfferModel::STATUS_SELECTED ? $offer['offer_discount'] : 0) ?>)
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr class="row-no-header">
                                            <?php
                                            if($offer['vendor_data']['is_pkp']){
                                                $taxAmount = floor(10 / 100 * $offer['total_price']);
                                                $taxAmountSelected = floor(10 / 100 * $offer['selected_total_price']);
                                                $taxPercent = 10;
                                            } else {
                                                $taxAmount = 0;
                                                $taxAmountSelected = 0;
                                                $taxPercent = 0;
                                            }
                                            ?>
                                            <td colspan="2" class="text-md-right"><strong>Tax <?= $taxPercent ?>%</strong></td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($taxAmount) ?></strong>
                                            </td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($taxAmountSelected) ?></strong>
                                            </td>
                                        </tr>
                                        <tr class="row-no-header">
                                            <td colspan="2" class="text-md-right"><strong>Total Price</strong></td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($offer['total_price'] + $taxAmount) ?></strong>
                                            </td>
                                            <td class="text-md-right">
                                                <strong>Rp. <?= numerical($offer['selected_total_price'] + $taxAmountSelected) ?></strong>
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
</div>

<div class="card grid-margin">
    <div class="card-body d-flex justify-content-between">
        <button onclick="history.back()" type="button" class="btn btn-light px-3 px-sm-4">Back</button>
        <?php if ($requisition['status'] == RequisitionModel::STATUS_READY): ?>
            <a href="<?= site_url('purchasing/selection/finish-selection/' . $requisition['id']) ?>" class="btn btn-primary btn-lg btn-finish-selection"
                    data-request-title="<?= $requisition['request_title'] ?> (<?= $requisition['no_requisition'] ?>)">
                Complete Selection
            </a>
		<?php elseif ($requisition['status'] == RequisitionModel::STATUS_ASK_SELECTION): ?>
			<a class="btn btn-primary btn-ready-selection px-3 px-sm-4" href="<?= site_url('requisition/set-ready/' . $requisition['id']) ?>"
			   data-request-title="<?= $requisition['request_title'] ?> (<?= $requisition['no_requisition'] ?>)">
				<i class="mdi mdi-checkbox-marked-outline mr-2 d-none d-sm-inline-block"></i> Set Ready Selection
			</a>
        <?php endif; ?>
    </div>
</div>

<form action="#" method="post" id="form-selection">
    <?= _csrf() ?>
    <?= _method('put') ?>
</form>

<?php if ($requisition['status'] == RequisitionModel::STATUS_READY): ?>
    <?php $this->load->view('selection/_modal_select_item') ?>
    <?php $this->load->view('partials/modals/_confirm') ?>
<?php else: ?>
	<?php $this->load->view('selection/_modal_ready_selection') ?>
<?php endif; ?>
