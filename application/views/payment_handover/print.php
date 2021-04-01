<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Handover Order</title>
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

<div style="margin-left: 60px">
    <div style="display: inline-block; margin-right: 10px">
        <img src="<?= FCPATH . 'assets/dist/img/layouts/company_logo.png' ?>" style="margin-bottom: 10px">
    </div>
    <div style="display: inline-block;">
        <p style="margin-left: 60px;font-size: 28px; margin-bottom: 0; letter-spacing: 4px; line-height: 1.1">
            <strong>TANDA TERIMA</strong>
        </p>
        <p style="font-size: 28px; margin-bottom: 0; letter-spacing: 4px; line-height: 1.1">
            <strong>DOKUMENT PEMBELIAN</strong>
        </p>
        <p style="margin-left: 70px;font-size: 28px; margin-bottom: 0; letter-spacing: 4px; line-height: 1.1">
            <strong>NO : <?= $paymentHandover['no_handover'] ?></strong>
        </p>
    </div>
</div>


<p style="margin-top: 20px;margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Telah diterima set lengkap dokumen pengajuan pembayaran pada tanggal...........
</p>
<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    PO No :
</p>
<?php foreach ($purchaseOrders as $key => $purchaseOrder): ?>
    <p style="margin-left: 15px; font-size: 14px"><?= ++$key ?>. &nbsp;<?= $purchaseOrder['no_purchase'] ?></p>
    <?php endforeach; ?>

<table style="margin-left:50px;font-size: 14px; margin-top: 40px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th>Purchasing</th>
        <th>Finance</th>
    </tr>
    </tbody>
</table>

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
</script>
</body>
</html>
