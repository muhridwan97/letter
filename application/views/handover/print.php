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
<div class="text-center" style="display: inline-block;  margin-left: 48px">
	<img src="<?= FCPATH .'assets/src/images/layouts/header-tci-iso-aeo.png' ?>" height="82">
</div>
<!--
<div style="margin-left: 60px">
    <div style="display: inline-block; margin-right: 10px">
        <img src="<?= FCPATH . 'assets/dist/img/layouts/company_logo.png' ?>" style="margin-bottom: 10px">
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
<div class="text-center">
    <p style="font-size: 18px; line-height: 1; letter-spacing: 2px; margin-bottom: 0"><strong>HANDOVER ORDER</strong></p>
    <p style="font-size: 14px; margin-top: 0; margin-bottom: 10px">Number: HO-<?= $order['no_purchase'] ?></p>
</div>

<p style="margin-bottom: 5px; line-height: 1.3; font-size: 14px">
    Berikut ini detail atas order Barang / Jasa sebagai berikut:
</p>

<table style="font-size: 14px; margin-bottom: 10px; width: 100%">
    <tbody>
    <tr>
        <th>No. Request</th>
        <th>:</th>
        <th><?= $order['no_requisition'] ?></th>
    </tr>
    <tr>
        <th>Request</th>
        <th>:</th>
        <th><?= $order['request_title'] ?></th>
    </tr>
    </tbody>
</table>

<?php if(!empty($uncoveredItems)): ?>
    <p style="margin-bottom: 5px; font-size: 14px">Atas order tersebut sudah terpenuhi semua kecuali dibawah ini :</p>
    <table class="table table-sm" style="margin-bottom: 10px">
        <thead>
        <tr class="font-weight-bold">
            <th class="text-center" style="width: 20px">No</th>
            <th>Item Name</th>
            <th style="width: 50px">Needed</th>
            <th style="width: 50px">Acquired</th>
            <th style="width: 35px">Less</th>
        </tr>
        </thead>
        <tbody>
        <?php $totalPrice = 0; ?>
        <?php $no = 1; ?>
        <?php foreach ($uncoveredItems as $index => $item): ?>
            <tr>
                <td class="text-center"><?= $index + 1 ?></td>
                <td><?= $item['item_name'] ?></td>
                <td><?= numerical(if_empty($item['quantity_needed'], 0)) ?></td>
                <td><?= numerical(if_empty($item['quantity_acquired'], 0)) ?></td>
                <td><?= numerical(if_empty($item['quantity_less'], 0)) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<p style="margin-bottom: 5px; font-size: 14px; text-align: justify">
    Atas barang itu kami <strong><?= $order['purchasing_name'] ?></strong> dari bagian Purchasing telah menyerahkan barang /
    jasa secara langsung maupun lewat vendor di tanggal <strong><?= if_empty(format_date($order['received_date'], 'd F Y'), '_________', '<u>', '</u>') ?></strong>.
    Atas barang / jasa tersebut catatan dari kami adalah sebagai berikut :
</p>
<p class="font-weight-bold" style="margin-bottom: 15px; font-size: 14px; text-align: justify">
    <?= nl2br(if_empty($order['handover_note'], '________________________________________________________________________________________', '<u>', '</u>')) ?>
</p>

<p style="margin-bottom: 5px; font-size: 14px; text-align: justify">
    Dengan Dokumen Serah Terima Barang / Jasa ini diisi oleh peminta ataupun di generate by sistem karena melewati waktu
    tunggu maka saya <strong><?= $order['employee_name'] ?></strong> betul sudah menerima barang / jasa tersebut dan bersedia bertanggung jawab
    atas barang / jasa tersebut sesuai dengan seharusnya serta catatan tambahan dari Purchasing tersebut diatas dan
    menerima sanksi sanksi yang diperlukan sesuai aturan perusahaan (apabila digunakan dan terjadi hal yang tidak
    sebagaimana mestinya) sampai kemudian diserahterimakan kepada orang lain (bila ada) dengan dokumen tertulis yang
    sah. Atas barang / jasa tersebut diatas menurut saya diterima
    <strong>
        <?= $order['received_date'] == $order['received_date_user'] ? 'sudah sesuai dengan tanggal diatas' : 'tidak sesuai (' . if_empty(format_date($order['received_date_user'], 'd F Y'), '_________', '<u>', '</u>') . ')' ?>
    </strong>.
    Adapun atas penerimaan barang tersebut saya memberikan catatan sebagai berikut :
</p>
<p class="font-weight-bold" style="margin-bottom: 15px; font-size: 14px; text-align: justify">
    <?= nl2br(if_empty($order['receiving_note'], '________________________________________________________________________________________', '<u>', '</u>')) ?>
</p>

<p style="margin-bottom: 5px; font-size: 14px; text-align: justify">
    Dan atas pelayanan sesuai kriteria pelayanan maka saya memberikan nilai <strong><?= if_empty($order['rating_user'], '-') ?> dari 5</strong> dengan alasan:
</p>
<p class="font-weight-bold" style="margin-bottom: 15px; font-size: 14px; text-align: justify">
    <?= nl2br(if_empty($order['rating_user_reason'], '________________________________________________________________________________________', '<u>', '</u>')) ?>
</p>

<p style="margin-bottom: 5px; font-size: 14px; text-align: justify">
    Demikian Serah Terima Barang / Jasa ini dilakukan sesuai data yang tersebut diatas yang mana sudah dianggap sah setelah terbit.
</p>

<br>
<br>

<p class="small">
	<em>
		This handover is valid without a signature and company seal if it is received from our official<br>
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
</script>
</body>
</html>
