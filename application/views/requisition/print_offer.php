<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Requisition</title>
	<link rel="stylesheet" href="<?= base_url(get_asset('app.css')) ?>">
    <style>
        @page {
            margin: 30px 30px;
        }

        body {
            margin: 10px;
            background: none;
        }
    </style>
</head>
<body>

<div style="margin-bottom: 10px">
    <p style="margin-bottom: 0; font-size: 18px"><strong>Requisition Offers</strong></p>
    <p style="font-size: 12px"><?= $this->config->item('app_name') ?> - Generated at <?= format_date('now', 'd F Y - H:i') ?></p>
</div>

<p style="font-size: 16px">Requisition Data</p>
<table style="width: 100%; margin-bottom: 20px; font-size: 13px">
    <tr>
        <td style="width: 130px"><strong>No Requisition</strong></td>
        <td><?= $requisition['no_requisition'] ?></td>
        <td style="width: 130px"><strong>Requester</strong></td>
        <td><?= $requisition['employee_name'] ?></td>
    </tr>
    <tr>
        <td style="width: 130px"><strong>Request</strong></td>
        <td><?= $requisition['request_title'] ?></td>
        <td style="width: 130px"><strong>Urgency</strong></td>
        <td><?= $requisition['urgency'] ?></td>
    </tr>
    <tr>
        <td style="width: 130px"><strong>Deadline</strong></td>
        <td><?= format_date($requisition['deadline'], 'd F Y') ?></td>
        <td style="width: 130px"><strong>Category</strong></td>
        <td><?= $requisition['category'] ?></td>
    </tr>
</table>

<p style="font-size: 16px">Request Items</p>
<table class="table table-sm mb-3">
    <thead>
    <tr>
        <th>No</th>
        <th>Item</th>
        <th>Brand</th>
        <th>Quantity</th>
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
            <td><?= if_empty($item['quantity'], '-') ?></td>
            <td><?= if_empty($item['unit'], '-') ?></td>
            <td><?= if_empty($item['description'], '-') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p style="font-size: 16px" class="mb-0">Admin Recommendation</p>
<p class="text-success">
    <strong>
        <?= if_empty($requisition['purchasing_note'], 'No recommendation from Purchasing') ?>
    </strong>
</p>

<div>
    <p style="font-size: 16px" class="mb-0">Vendor Offers</p>
    <span class="float-right">
        <strong class="text-success"><?= count(array_filter($purchaseOffers, function($offer) {
                return in_array($offer['status'], [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_COMPLETED]);
            })) ?> / <?= count($purchaseOffers) ?>
            offers</strong> selected
    </span>
</div>
<p class="text-muted" style="line-height: 1">Consider old transaction and rating</p>

<table class="table table-sm">
    <thead>
    <tr>
        <th>No</th>
        <th>Vendor</th>
        <th>Rating</th>
        <th>Lead Time</th>
        <th>TOP</th>
        <th>Selected</th>
        <th>Contact</th>
    </tr>
    </thead>

    <?php $no = 1;
    foreach ($purchaseOffers as $offer): ?>

        <tr class="<?= $offer['status'] == PurchaseOfferModel::STATUS_SELECTED ? 'success' : '' ?>">
            <td><?= $no++ ?></td>
            <td>
                <a href="<?= site_url('purchasing/offer/view/' . $offer['id']) ?>">
                    <?= $offer['vendor'] ?>
                </a>
            </td>
            <td><?= numerical($offer['vendor_data']['average_rating'], 0) ?></td>
            <td>
                <span class="d-block"><?= format_date($offer['lead_time'], 'd F Y') ?></span>
                <small class="text-muted">
                    <?php $differenceDay = difference_date($offer['lead_time'], $requisition['deadline']); ?>
                    <?php if ($differenceDay == 0): ?>
                        In Time
                    <?php elseif ($differenceDay < 0): ?>
                        <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> late
                    <?php else: ?>
                        <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> early
                    <?php endif; ?>
                </small>
            </td>
            <td><?= numerical($offer['top']) ?> Days</td>
            <td><?= $offer['vendor_data']['total_selected'] ?> X</td>
            <td><?= $offer['vendor_data']['contact'] ?></td>
        </tr>
        <tr>
            <td colspan="7">
                <?php $selectedStatus = [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_COMPLETED] ?>
                <div>
                    <div>
                        <span class="mt-1 pl-2 d-inline-block">Detail Items :</span>
                        <?php if (in_array($offer['status'], $selectedStatus)): ?>
                            <span class="badge badge-success float-right"><?= $offer['status'] ?></span>
                        <?php else: ?>
                            <span class="float-right badge <?= $offer['status'] == PurchaseOfferModel::STATUS_UNSELECTED  ? 'badge-danger' : 'badge-info' ?>">
                                <?= $offer['status'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="pl-2 pt-1">
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Brand</th>
                                <th>Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Unit Disc</th>
                                <th class="text-right">Total Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($offer['items'] as $item): ?>
                                <tr class="<?= in_array($offer['status'], $selectedStatus) && !empty($item['quantity_selected']) ? ' table-success' : '' ?>">
                                    <td><?= $item['item_name'] ?></td>
                                    <td><?= if_empty($item['brand'], '-') ?></td>
                                    <td><?= numerical($item['quantity']) ?></td>
                                    <td class="text-right">
                                        Rp. <?= numerical($item['price']) ?>
                                    </td>
                                    <td class="text-right">
                                        Rp. <?= numerical($item['discount']) ?>
                                    </td>
                                    <td class="text-right">
                                        Rp. <?= numerical($item['total']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" rowspan="4">
                                    <p><strong>Notes: </strong>
                                        <?= if_empty($offer['description'], '-') ?>
                                    </p>
                                </td>
                                <td class="text-right"><strong>Sub Total</strong></td>
                                <td class="text-right">
                                    <strong>Rp. <?= numerical($offer['extended_total']) ?></strong>
                                </td>
                            </tr>
                            <tr class="row-no-header">
                                <td class="text-right"><strong>Offer Discount</strong></td>
                                <td class="text-right">
                                    <strong>(Rp. <?= numerical($offer['offer_discount']) ?>)</strong>
                                </td>
                            </tr>
                            <tr>
                                <?php
                                if($offer['vendor_data']['is_pkp']){
                                    $taxAmount = floor(10 / 100 * $offer['total_price']);
                                    $taxPercent = 10;
                                } else {
                                    $taxAmount = 0;
                                    $taxPercent = 0;
                                }
                                ?>
                                <td class="text-right"><strong>Tax <?= $taxPercent ?>%</strong></td>
                                <td class="text-right">
                                    <strong>Rp. <?= numerical($taxAmount) ?></strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-right"><strong>Total Price</strong></td>
                                <td class="text-right">
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

</body>
</html>
