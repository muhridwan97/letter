<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Requisition</title>
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
    <div style="display: inline-block; margin-right: 20px">
        <img src="<?= FCPATH . 'assets/dist/img/layouts/company_logo.png' ?>" style="margin-bottom: 15px">
    </div>
    <div style="display: inline-block;">
        <h2 style="margin-bottom: 0"><?= get_setting('company_name') ?></h2>
        <p>
            <?= get_setting('company_address') ?>
            <br><?= get_setting('company_contact') ?>
        </p>
    </div>
</div>
 -->

<h5 class="text-center" style="margin-top: 0"><strong>REQUISITION FORM</strong></h5>

<div style="margin-bottom: 20px">
    <table style="width: 100%; border: none; font-size: 14px;">
        <tr>
            <td>
                <strong>
                    <?= if_empty($requisition['category'], 'No category') ?>
                </strong>
            </td>
            <td>
                <strong>Urgency Rate</strong> :
                <?php $urgencies = [
                    'LOW' => 1,
                    'NORMAL' => 2,
                    'HIGH' => 3,
                    'VERY HIGH' => 4,
                ] ?>
                <?= $urgencies[$requisition['urgency']] ?> / 4 &nbsp;
                (<?= $requisition['urgency'] ?>)
            </td>
            <td>
                <strong>Date</strong> :
                <?= format_date($requisition['created_at'], 'd F Y') ?>
            </td>
        </tr>
        <tr>
            <td><?= $requisition['request_title'] ?></td>
            <td>
                <strong>No Request</strong> :
                <?= $requisition['no_requisition'] ?>
            </td>
            <td>
                <strong>Deadline</strong> :
                <?= format_date($requisition['deadline'], 'd F Y') ?>
            </td>
        </tr>
    </table>
</div>

<table class="table table-bordered table-sm">
    <thead>
    <tr>
        <th class="text-center">No</th>
        <th>Item</th>
        <th>Brand</th>
        <th>Type</th>
        <th>Quantity</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    <?php $no = 1; ?>
    <?php foreach ($requisitionItems as $itemData): ?>

        <tr>
            <td class="text-center"><?php echo $no++; ?></td>
            <td><?= $itemData['item_name'] ?></td>
            <td><?= if_empty($itemData['brand'], '-') ?></td>
            <td><?= if_empty($itemData['type'], '-') ?></td>
            <td><?= numerical($itemData['quantity']) ?> <?= $itemData['unit'] ?></td>
            <td><?= if_empty($itemData['description'], '-') ?></td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>

<table style="width: 100%; margin-top: 20px; margin-bottom: 40px; font-size: 14px">
    <tr>
        <td class="text-center text-nowrap" width="25%">
            <p><strong>Requester</strong></p>
            <br>
            (&nbsp;<?= $requisition['employee_name'] ?>&nbsp;)
        </td>
        <td class="text-center text-nowrap" width="25%">
            <p><strong>SPV / Manager Dept.</strong></p>
            <br>
            (&nbsp;<?= if_empty($requisition['supervisor_name'], 'No Supervisor') ?>&nbsp;)
        </td>
        <td class="text-center text-nowrap" width="25%">
            <p><strong>Purchasing</strong></p>
            <br>
            (&nbsp;<?= get_setting('purchasing_admin', 'No Purchasing') ?>&nbsp;)
        </td>
        <td class="text-center text-nowrap" width="25%">
            <p><strong>Purchasing Manager</strong></p>
            <br>
            (&nbsp;<?= get_setting('purchasing_supervisor', 'No manager') ?>&nbsp;)
        </td>
    </tr>
</table>

<p>
    <strong>Description :</strong><br>
    <?= if_empty($requisition['description'], '-') ?>
</p>

<p class="small">
    <em>
        Requisition is valid without a signature and company seal if it is received from our official<br>
        department email, ie <?= env('MAIL_FROM_ADDRESS') ?> / <?= get_setting('email_support') ?>
    </em>
</p>

</body>
</html>
