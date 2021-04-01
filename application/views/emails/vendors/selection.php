<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <style type="text/css" rel="stylesheet" media="all">
        /* Media Queries */
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>

<?php

$style = [
    /* Layout ------------------------------ */

    'body' => 'margin: 0; padding: 0; width: 100%; background-color: #F2F4F6;',
    'email-wrapper' => 'width: 100%; margin: 0; padding: 0; background-color: #F2F4F6;',

    /* Masthead ----------------------- */

    'email-masthead' => 'padding: 25px 0; text-align: center;',
    'email-masthead_name' => 'font-size: 16px; font-weight: bold; color: #2F3133; text-decoration: none; text-shadow: 0 1px 0 white;',

    'email-body' => 'width: 100%; margin: 0; padding: 0; border-top: 1px solid #EDEFF2; border-bottom: 1px solid #EDEFF2; background-color: #FFF;',
    'email-body_inner' => 'width: auto; max-width: 800px; margin: 0 auto; padding: 0;',
    'email-body_cell' => 'padding: 35px;',

    'email-footer' => 'width: auto; max-width: 800px; margin: 0 auto; padding: 0; text-align: center;',
    'email-footer_cell' => 'color: #AEAEAE; padding: 35px; text-align: center;',

    /* Body ------------------------------ */

    'body_action' => 'width: 100%; margin: 30px auto; padding: 0; text-align: center;',
    'body_sub' => 'margin-top: 25px; padding-top: 25px; border-top: 1px solid #EDEFF2; width: 100%',

    /* Type ------------------------------ */

    'anchor' => 'color: #3869D4;',
    'header-1' => 'margin-top: 0; color: #2F3133; font-size: 19px; font-weight: bold; text-align: left;',
    'header-2' => 'margin-top: 0; color: #2F3133; font-size: 17px; font-weight: bold; text-align: left;',
    'paragraph' => 'margin-top: 0; color: #64686E; font-size: 16px; line-height: 1.5em;',
    'paragraph-sub' => 'margin-top: 0; color: #74787E; font-size: 12px; line-height: 1.5em;',
    'paragraph-center' => 'text-align: center;',

    'table' => 'margin-top: 0; color: #64686E; font-size: 16px; line-height: 1.5em; text-alight:left; border-collapse: collapse; border:1px solid #aaaaaa',

    /* Buttons ------------------------------ */

    'button' => 'display: block; display: inline-block; width: 200px; min-height: 20px; padding: 10px;
                 background-color: #3869D4; border-radius: 3px; color: #ffffff; font-size: 15px; line-height: 25px;
                 text-align: center; text-decoration: none; -webkit-text-size-adjust: none;',

    'button--green' => 'background-color: #22BC66;',
    'button--red' => 'background-color: #dc4d2f;',
    'button--blue' => 'background-color: #3869D4;',
];
?>

<?php $fontFamily = 'font-family: Arial, \'Helvetica Neue\', Helvetica, sans-serif;'; ?>

<body style="<?php echo $style['body'] ?>">
<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td style="<?php echo $style['email-wrapper'] ?>" align="center">
            <table width="100%" cellpadding="0" cellspacing="0">
                <!-- Logo -->
                <tr>
                    <td style="<?php echo $style['email-masthead'] ?>">
                        <a style="<?php echo $fontFamily ?> <?php echo $style['email-masthead_name'] ?>"
                           href="<?= site_url() ?>" target="_blank">
                            <?= get_setting('app_name', $this->config->item('app_name')) ?>
                        </a>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td style="<?php echo $style['email-body'] ?>" width="100%">
                        <table style="<?php echo $style['email-body_inner'] ?>" align="center" width="700"
                               cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="<?php echo $fontFamily ?> <?php echo $style['email-body_cell'] ?>">
                                    <!-- Greeting -->
                                    <h1 style="<?php echo $style['header-1'] ?>">Requisition</h1>

                                    <!-- Info -->
                                    <h3 style="<?php echo $style['paragraph'] ?>">
                                        Asking for vendor recommendation
                                    </h3>

                                    <p style="<?php echo $style['paragraph'] ?>">
                                        We recently receive a request of goods/services as following:
                                    </p>

                                    <table style="<?php echo $style['paragraph'] ?>" width="100%">
                                        <tr>
                                            <td>No Requisition</td>
                                            <td><b><?= $requisition['no_requisition'] ?></b></td>
                                        </tr>
                                        <tr style="width: 250px">
                                            <td>Requisition</td>
                                            <td><b><?= $requisition['request_title'] ?></b></td>
                                        </tr>
                                        <tr style="width: 250px">
                                            <td>Requester</td>
                                            <td><b><?= $requisition['employee_name'] ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Deadline</td>
                                            <td><b><?= format_date($requisition['deadline'], 'd F Y') ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Urgency</td>
                                            <td><b><?= $requisition['urgency'] ?></b></td>
                                        </tr>
                                    </table>

                                    <br>

                                    <table style="<?php echo $style['table'] ?>" width="100%">
                                        <tr style="border-bottom: 1px solid #aaaaaa">
                                            <th align="left" style="padding: 4px">No</th>
                                            <th align="left" style="padding: 4px">Item</th>
                                            <th align="left" style="padding: 4px">Brand</th>
                                            <th align="left" style="padding: 4px">Quantity</th>
                                        </tr>
                                        <?php $no = 1; ?>
                                        <?php foreach ($requisitionItems as $item): ?>
                                            <tr>
                                                <td style="padding: 4px"><?= $no++ ?></td>
                                                <td style="padding: 4px"><?= $item['item_name'] ?></td>
                                                <td style="padding: 4px"><?= if_empty($item['brand'], '-') ?></td>
                                                <td style="padding: 4px"><?= $item['quantity'] . ' ' . $item['unit'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>

                                    <br>

                                    <p style="<?php echo $style['paragraph'] ?>">
                                        Berikut kami lampirkan daftar pengajuan vendor keterangan terkait penawaran melalui <b>attachment (PDF)</b>, untuk informasi
                                        lebih lanjut silahkan hubungi bagian purchasing <b><?= get_setting('company_name'); ?></b> di <?= get_setting('email_support'); ?>.
                                    </p>

                                    <br>

                                    <h3 style="<?php echo $style['paragraph'] ?>">
                                        Vendor Offer List
                                    </h3>

                                    <table style="<?php echo $style['table'] ?>" width="100%">
                                        <thead>
                                        <tr style="border-bottom: 1px solid #aaaaaa">
                                            <th align="left" style="padding: 4px">No</th>
                                            <th align="left" style="padding: 4px">Vendor</th>
                                            <th align="left" style="padding: 4px">Rating</th>
                                            <th align="left" style="padding: 4px">Lead Time</th>
                                            <th align="left" style="padding: 4px">Deliver</th>
                                            <th align="left" style="padding: 4px">Selected</th>
                                            <th align="left" style="padding: 4px">Document</th>
                                        </tr>
                                        </thead>

                                        <?php $no = 1; ?>
                                        <?php foreach ($purchaseOffers as $purchaseOffer): ?>

                                            <tr style="border-bottom: 1px solid #aaaaaa">
                                                <td align="left" style="padding: 4px"><?= $no++ ?></td>
                                                <td align="left" style="padding: 4px"><?= $purchaseOffer['vendor'] ?></td>
                                                <td align="left" style="padding: 4px"><?= numerical($purchaseOffer['vendor_data']['average_rating'], 1) ?></td>
                                                <td align="left" style="padding: 4px"><?= format_date($purchaseOffer['lead_time'], 'd F Y') ?></td>
                                                <td align="left" style="padding: 4px">
                                                    <?php $differenceDay = difference_date($purchaseOffer['lead_time'], $requisition['deadline']); ?>
                                                    <?php if ($differenceDay == 0): ?>
                                                        In Time
                                                    <?php elseif ($differenceDay < 0): ?>
                                                        <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> late
                                                    <?php else: ?>
                                                        <?= abs($differenceDay) ?> day<?= (abs($differenceDay) > 1) ? 's' : '' ?> early
                                                    <?php endif; ?>
                                                </td>
                                                <td align="left" style="padding: 4px"><?= $purchaseOffer['vendor_data']['total_selected'] ?> X</td>
                                                <td align="left" style="padding: 4px">See Attachment</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #aaaaaa">
                                                <td></td>
                                                <td colspan="6">
                                                    <table style="<?php echo $style['table'] ?>; margin-bottom: 20px" width="100%">
                                                        <thead>
                                                        <tr style="border-bottom: 1px solid #aaaaaa">
                                                            <th align="left" style="padding: 4px">Item</th>
                                                            <th align="left" style="padding: 4px">Brand</th>
                                                            <th align="left" style="padding: 4px">Quantity</th>
                                                            <th align="right" style="padding: 4px">Unit Price</th>
                                                            <th align="right" style="padding: 4px">Unit Disc</th>
                                                            <th align="right" style="padding: 4px">Total Price</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($purchaseOffer['items'] as $item): ?>
                                                            <tr style="border-bottom: 1px solid #aaaaaa">
                                                                <td align="left" style="padding: 4px"><?= if_empty($item['item_name'], '-') ?></td>
                                                                <td align="left" style="padding: 4px"><?= if_empty($item['brand'], '-') ?></td>
                                                                <td align="right" style="padding: 4px"><?= numerical($item['quantity']) ?></td>
                                                                <td align="right" style="padding: 4px">
                                                                    Rp. <?= numerical($item['price']) ?>
                                                                </td><td align="right" style="padding: 4px">
                                                                    Rp. <?= numerical($item['discount']) ?>
                                                                </td>
                                                                <td align="right" style="padding: 4px">
                                                                    Rp. <?= numerical($item['total']) ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <tr>
                                                            <td colspan="4" rowspan="4" style="padding: 4px">
                                                                <p>
                                                                    <strong>Notes: </strong>
                                                                    <?= if_empty($purchaseOffer['description'], '-') ?>
                                                                </p>
                                                            </td>
                                                            <td align="right" style="padding: 4px">Sub Total</td>
                                                            <td align="right" style="padding: 4px">
                                                                Rp. <?= numerical($purchaseOffer['extended_total']) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><strong>Offer Discount</strong></td>
                                                            <td align="right">
                                                                <strong>(Rp. <?= numerical($purchaseOffer['offer_discount']) ?>)</strong>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            if($purchaseOffer['vendor_data']['is_pkp']){
                                                                $taxAmount = floor(10 / 100 * $purchaseOffer['total_price']);
                                                                $taxPercent = 10;
                                                            } else {
                                                                $taxAmount = 0;
                                                                $taxPercent = 0;
                                                            }
                                                            ?>
                                                            <td align="right" style="padding: 4px">Tax <?= $taxPercent ?>%</td>
                                                            <td align="right" style="padding: 4px">
                                                                Rp. <?= numerical($taxAmount) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" style="padding: 4px"><strong>Total Price</strong></td>
                                                            <td align="right" style="padding: 4px">
                                                                <strong>Rp. <?= numerical($purchaseOffer['total_price'] + $taxAmount) ?></strong>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    </table>

                                    <br>
                                    <p style="<?php echo $style['paragraph'] ?>">
                                        <b>Admin Recommendation:</b> <?= $note ?>
                                    </p>

                                    <br>

                                    <!-- Action Button -->
                                    <table style="<?php echo $style['body_action'] ?>" align="center" width="100%"
                                           cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center">
                                                <a href="<?= site_url('purchasing/vendor-selection/select/' . $requisition['id'] . '/' . $token . '?email=' . base64_encode($email)); ?>"
                                                   style="<?php echo $fontFamily ?> <?php echo $style['button'] ?> <?php echo $style['button--blue'] ?>" class="button">
                                                    REPLY DECISION
                                                </a>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Salutation -->
                                    <p style="<?php echo $style['paragraph'] ?>">
                                        Regards,<br>
                                        <b>Purchasing <?= get_setting('company_name'); ?></b>
                                    </p>

                                    <p>
                                        <b>DO NOT REPLY DECISION IN THIS EMAIL<br>USE [REPLY DECISION] BUTTON OR DIRECTLY REPLY TO PURCHASING EMAIL</b>
                                    </p>

                                    <!-- Sub Copy -->
                                    <table style="<?php echo $style['body_sub'] ?>">
                                        <tr>
                                            <td style="<?php echo $fontFamily ?>">
                                                <p style="<?php echo $style['paragraph-sub'] ?>">
                                                    This email was intended for
                                                    <a href="mailto:<?= $email ?>" style="<?php echo $style['anchor'] ?>">
                                                        <?= $email ?>
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td>
                        <table style="<?php echo $style['email-footer'] ?>" align="center" width="700" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="<?php echo $fontFamily ?> <?php echo $style['email-footer_cell'] ?>">
                                    <p style="<?php echo $style['paragraph-sub'] ?>">
                                        &copy; <?php echo date('Y') ?>
                                        <a style="<?php echo $style['anchor'] ?>" href="<?= site_url() ?>" target="_blank">
                                            <?= $this->config->item('app_name') ?>
                                        </a>.
                                        All rights reserved.
                                    </p>
                                    <small>
                                        <?= get_setting('company_name') ?>
                                        <?= get_setting('company_address') ?>
                                        <?= get_setting('company_contact') ?>
                                    </small>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>