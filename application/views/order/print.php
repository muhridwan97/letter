<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Purchase Order</title>
    <link rel="stylesheet" href="<?= base_url(get_asset('vendors.css')) ?>">
    <link rel="stylesheet" href="<?= base_url(get_asset('app.css')) ?>">
    <style>
        @page {
            margin: 40px;
        }

        body {
            margin: 10px;
            background: none;
        }
    </style>
</head>
<body>
<div class="text-center" style="display: inline-block;  margin-left: 48px">
	<img src="<?= FCPATH .'assets/src/images/layouts/header-tci-iso-aeo.png' ?>" height="82">
</div>
<!--
<div style="margin-left: 60px">
    <div style="display: inline-block; margin-right: 10px">
    	<img src="<?= FCPATH .'assets/src/images/layouts/company_logo.png' ?>" class="mt10 pull-left" height="82">
    </div>
    <div style="display: inline-block;">
        <p style="font-size: 28px; margin-bottom: 0; letter-spacing: 4px; line-height: 1.1">
            <strong><?= get_setting('company_name') ?></strong>
        </p>
        <p style="line-height: 1.1">
            <?= get_setting('company_address') ?>
            <br><?= get_setting('company_contact') ?>
        </p>
    </div>
</div>
 -->

<div class="text-center" style="margin-bottom: 5px;">
    <p style="font-size: 20px; line-height: 1; letter-spacing: 2px; margin-bottom: 0"><strong><?= !isset($order['id']) || (isset($order['draft']) && $order['draft'] || get_if_exist($order, 'document_status') == PurchaseOrderModel::STATUS_DRAFT) ? '<span class="text-danger">DRAFT</span> ' : '' ?>PURCHASE ORDER</strong></p>
    <p style="font-size: 16px; margin-top: 0; margin-bottom: 0">Number: <?= get_if_exist($order, 'no_purchase', 'DRAFT-NO') ?></p>
</div>

<p style="line-height: 1.3; margin-bottom: 0; font-size: 14px">
    Dear,<br>
    <?= $vendor['contact_person'] ?> (<?= if_empty($vendor['contact'], 'No contact') ?>)<br>
    <?= if_empty($vendor['email'], 'No email') ?><br>
    <strong><?php echo $vendor['vendor'] ?></strong><br>
    <?php echo $vendorLocations[0]['address'] ?><br>
    <?php echo $vendorLocations[0]['city'] ?><br>
</p>

<p style="margin: 20px 0; 10px">The following items are the request that we need:</p>

<table class="table table-bordered table-sm" style="margin-bottom: 10px">
    <thead>
    <tr>
        <th class="text-center">No</th>
        <th class="text-center">Item Name</th>
        <th class="text-center" colspan="2">Quantity</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center">Unit Discount</th>
        <th class="text-center">Extended Price</th>
    </tr>
    </thead>
    <tbody>

    <?php $totalPrice = 0; ?>
    <?php $no = 1; ?>
    <?php foreach ($purchaseOfferItems as $item): ?>
        <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td><?= $item['item_name'] . ' ' . if_empty($item['brand'], '', '', '', true) ?></td>
            <td class="text-center"><?= if_empty($item['quantity_selected'], 0) ?></td>
            <td class="text-center"><?= if_empty($item['unit'], '-') ?></td>
            <td class="text-right">Rp. <?= numerical(if_empty($item['price'], 0)) ?></td>
            <td class="text-right">Rp. <?= numerical(if_empty($item['discount'], 0)) ?></td>
            <td class="text-right">Rp. <?= numerical(if_empty($item['selected_total'], 0)) ?></td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <td colspan="4"></td>
        <td colspan="2">Sub Total</td>
        <td class="text-right">
            <strong>Rp. <?= numerical($purchaseOffer['selected_extended_total']) ?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">Offer Discount</td>
        <td class="text-right">
            <strong>(Rp. <?= numerical($purchaseOffer['offer_discount']) ?>)</strong>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">Rounding Amount</td>
        <td class="text-right">
            <?php if (($order['rounding_amount']) < 0): ?>
                <strong>(Rp. <?= numerical(abs($order['rounding_amount'])) ?>)</strong>
            <?php else: ?>
                <strong>Rp. <?= numerical($order['rounding_amount']) ?></strong>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">
            Tax <?= empty($order['tax_percentage']) || $order['tax_percentage'] == '0' ? '' : '(' . round($order['tax_percentage']) . '%)' ?>
        </td>
        <td class="text-right">
            <strong>Rp. <?= if_empty(numerical($order['tax']), 0) ?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">Delivery Cost</td>
        <td class="text-right">
            <strong>Rp. <?= if_empty(numerical($order['delivery_cost']), 0) ?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2"><?= ucwords(strtolower(if_empty($order['miscellaneous_label'], 'Miscellaneous'))) ?></td>
        <td class="text-right">
            <?php if (($order['miscellaneous_expense']) < 0): ?>
                <strong>(Rp. <?= numerical(abs($order['miscellaneous_expense'])) ?>)</strong>
            <?php else: ?>
                <strong>Rp. <?= numerical($order['miscellaneous_expense']) ?></strong>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">
            <?= ucwords(strtolower(if_empty($order['tax_basis_label'], 'Tax Basis'))) ?>
            <?= empty($order['tax_basis_percentage']) || $order['tax_basis_percentage'] == '0' ? '' : '(' . numerical($order['tax_basis_percentage']) . '%)' ?>
        </td>
        <td class="text-right">
            <strong>(Rp. <?= if_empty(numerical($order['tax_basis']), 0) ?>)</strong>
        </td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2">Total</td>
        <td class="text-right">
            <strong>Rp. <?= if_empty(numerical($order['total_price']), 0) ?></strong>
        </td>
    </tr>
    </tbody>
</table>

<p>
    <span style="margin-right: 70px; display: inline-block">
        <strong>TOP:</strong> <?= get_if_exist($order, 'top', '-') ?> Days
    </span>
    <span style="display: inline-block">
        <strong>Delivery Date:</strong> <?= if_empty(format_date(get_if_exist($order, 'lead_time', ''), 'd F Y'), '-') ?>
    </span>
</p>

<p>
    <strong>Order Note:</strong><br>
    <?php
    $orderNote = explode('||', trim(get_if_exist($order, 'description', '')));
    $totalNote = count($orderNote);
    for ($i = 0; $i < $totalNote; $i++) {
        if (!empty(trim($orderNote[$i]))) {
            if ($totalNote > 1) {
                echo ($i + 1) . '. ';
            }
            echo $orderNote[$i];
            if ($totalNote > 1) {
                echo '<br>';
            }
        }
    }
    if (empty($orderNote) || empty($orderNote[0])) {
        echo '-';
    }
    ?>
</p>


<p>
    <strong>
        Jakarta, <?= format_date(get_if_exist($order, 'date', 'now'), 'd F Y') ?>
    </strong>
</p>
Purchasing, <?= if_empty($order['purchasing_admin'], get_setting('purchasing_admin')) ?>
<br>
Acknowledged By, <?= if_empty($order['purchasing_supervisor'], get_setting('purchasing_supervisor')) ?>
<br>
<br>


<p class="small">
    <em>
        This purchase order is valid without a signature and company seal if it is received from our official<br>
        department email, ie <?= env('MAIL_FROM_ADDRESS') ?> / <?= get_setting('email_support') ?>
    </em>
</p>

<script type="text/php">
    $x = 280;
    $y = 810;
    $text = "{PAGE_NUM} of {PAGE_COUNT}";
    $font = $fontMetrics->get_font("helvetica", "bold");
    $size = 10;
    $color = array(.08, .08, .08);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    //stampmark
    $x = 20;
    $y = 810;
    $text = "<?= $emailDecode ?>";
    $font = $fontMetrics->get_font("helvetica", "italic");
    $size = 10;
    $color = array(.08, .08, .08);
    $word_space = 0.0;  //  default
    $char_space = 0.0;  //  default
    $angle = 0.0;   //  default
    $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
</script>
</body>
</html>
