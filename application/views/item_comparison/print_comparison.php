<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Requisition</title>
	<link rel="stylesheet" href="<?= base_url(get_asset('vendors.css')) ?>">
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
<body onload="window.print();">
<div class="title-wrapper clearfix" style="margin-bottom: 20px">
    <p style="margin-bottom: 0; font-size: 20px"><strong>Purchase Offer Comparison</strong></p>
    <p><?= $this->config->item('app_name') ?> - Generated at <?= format_date('now', 'd F Y - H:i') ?></p>
</div>

<p style="font-size: 18px">Requisition Data</p>
<table style="width: 100%; margin-bottom: 20px">
    <tr>
        <td style="width: 130px"><strong>No Requisition</strong></td>
        <td><?= $requisition['no_requisition'] ?></td>
        <td style="width: 130px"><strong>Requester</strong></td>
        <td><?= $requisition['employee_name'] ?> &nbsp; (SPV: <?= if_empty($requisition['supervisor_name'], 'No SPV') ?>)</td>
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


<p style="font-size: 18px">Requisition Item Data</p>
<table class="table table-bordered table-condensed" style="font-size: 80%">
    <thead>
    <tr>
        <th>No</th>
        <th>Item</th>
        <?php foreach ($purchaseOffers as $offer): ?>
            <th>
                <?= $offer['vendor'] ?> &nbsp;
                <span class="text-muted">
                    (Rating: <?= numerical($offer['vendor_data']['average_rating'], 1) ?> / <?= $offer['vendor_data']['total_selected'] ?>X selected)
                </span>
                &nbsp; / &nbsp; TOP &nbsp;
                <span class="text-muted">
                    <?= $offer['top'] ?> Days
                </span>
            </th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>

    <?php $no = 1; ?>
    <?php foreach ($requisitionItems as $itemRequest): ?>

        <tr>
            <td><?= $no++; ?></td>
            <td>
                <span class="d-block"><?= $itemRequest['item_name'] . ' ' . if_empty($itemRequest['brand'], '', '', '', true) ?></span>
                <small class="text-muted"><?= if_empty($itemRequest['description']) ?></small>
            </td>
            <?php foreach ($purchaseOffers as $offer): ?>
                <?php $hasItem = false; ?>
                <?php foreach ($offer['items'] as $item): ?>
                    <?php if ($item['id_requisition_item'] == $itemRequest['id']): ?>
                        <?php $hasItem = true; ?>
                        <td>Rp. <?= numerical($item['price']).' / '. $item['unit'] ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasItem): ?>
                    <td>-</td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>

    <?php endforeach; ?>
    <!-- add comparison manual -->
    <?php foreach ($comparisonItems as $item): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= if_empty($item['comparison_item'], '-') ?></td>
            <?php foreach ($purchaseOffers as $offer): ?>
                <?php $hasItem = false; ?>
                <?php foreach ($offer['comparison_items'] as $comp): ?>
                    <?php if($comp['comparison_item'] == $item['comparison_item']): ?>
                        <?php $hasItem = true; ?>
                        <td><?= if_empty($comp['description'], '-') ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (!$hasItem): ?>
                    <td>-</td>
                <?php endif; ?>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>

    <tr>
        <th colspan="2">SUB TOTAL</th>
        <?php foreach ($purchaseOffers as $offer): ?>
            <td >Rp <?= numerical($offer['extended_total']) ?></td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <th colspan="2">OFFER DISCOUNT</th>
        <?php foreach ($purchaseOffers as $offer): ?>
            <td>(Rp <?= numerical($offer['offer_discount']) ?>)</td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <th colspan="2">TAX</th>
        <?php foreach ($purchaseOffers as $offer): ?>
            <td>
                <?php if ($offer['vendor_data']['is_pkp']): ?>
                    Rp. <?= numerical(floor(10 / 100 * $offer['total_price'])) ?> (10%)
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        <?php endforeach; ?>
    </tr>
    <tr>
        <th colspan="2">TOTAL</th>
        <?php foreach ($purchaseOffers as $offer): ?>
            <?php
            if ($offer['vendor_data']['is_pkp']) {
                $tax = floor(10 / 100 * $offer['total_price']);
            } else {
                $tax = 0;
            }
            ?>
            <th>
                Rp <?= numerical($offer['total_price'] + $tax) ?>
            </th>
        <?php endforeach; ?>
    </tr>

    </tbody>
</table>

<div class="my-4">
    <p style="font-size: 18px" class="mb-1">Admin Recommendation</p>
    <p class="text-success">
        <strong><?= if_empty($requisition['purchasing_note'], 'No recommendation from Purchasing') ?></strong>
    </p>
</div>

</body>
</html>
