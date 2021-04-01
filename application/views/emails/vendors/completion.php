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
    'email-body_inner' => 'width: auto; max-width: 700px; margin: 0 auto; padding: 0;',
    'email-body_cell' => 'padding: 35px;',

    'email-footer' => 'width: auto; max-width: 700px; margin: 0 auto; padding: 0; text-align: center;',
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
                                    <h1 style="<?php echo $style['header-1'] ?>">Completing Order Detail</h1>

                                    <!-- Info -->
                                    <h3 style="<?php echo $style['paragraph'] ?>">
                                        Hi, <?= $purchaseOffer['vendor']; ?>
                                    </h3>

                                    <p style="<?php echo $style['paragraph'] ?>">
                                        We recently order goods/service from your company as follow:
                                    </p>

                                    <table style="<?php echo $style['paragraph'] ?>" width="100%">
                                        <tr style="width: 250px">
                                            <td>Request</td>
                                            <td><b><?= $purchaseOffer['request_title'] ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>No Requisition</td>
                                            <td><b><?= $purchaseOffer['no_requisition'] ?></b></td>
                                        </tr>
                                        <tr>
                                            <td>Draft Order</td>
                                            <td><b><?= isset($purchaseOrder) ? $purchaseOrder['no_purchase'] : 'Draft' ?></b></td>
                                        </tr>
                                    </table>

                                    <br>

                                    <table style="<?php echo $style['table'] ?>" width="100%">
                                        <tr style="border-bottom: 1px solid #aaaaaa">
                                            <th align="left" style="padding: 5px">No</th>
                                            <th align="left" style="padding: 5px">Item</th>
                                            <th align="left" style="padding: 5px">Brand</th>
                                            <th align="left" style="padding: 5px">Type</th>
                                            <th align="left" style="padding: 5px">Qty</th>
                                            <th align="left" style="padding: 5px">Unit</th>
                                        </tr>
                                        <?php $no = 1; foreach ($purchaseOfferItems as $item): ?>
                                            <tr>
                                                <td style="padding: 5px"><?= $no++ ?></td>
                                                <td style="padding: 5px"><?= $item['item_name'] ?></td>
                                                <td style="padding: 5px"><?= $item['brand'] ?></td>
                                                <td style="padding: 5px"><?= $item['type'] ?></td>
                                                <td style="padding: 5px"><?= $item['quantity_selected'] ?></td>
                                                <td style="padding: 5px"><?= $item['unit'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>

                                    <br>

                                    <p style="<?php echo $style['paragraph'] ?>">
                                        <span style="color: maroon">
											We need your confirmation for detail of your work such as <b>Job Order Document</b>
											or <b>Rent Duration (Heavy equipment leasing sheet)</b>.
										</span> Use button below to proceed.
                                    </p>

									<br>

									<h3 style="<?= $style['paragraph'] ?>">
										Notes:<br>
										<?= if_empty($message, '-') ?>
									</h3>

                                    <!-- Action Button -->
                                    <table style="<?php echo $style['body_action'] ?>" align="center" width="100%"
                                           cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center">
												<?php if(isset($purchaseOrder)): ?>
													<a href="<?= site_url('purchasing/vendor-completion/order/' . $purchaseOrder['id'] . '?token=' . $token); ?>"
													   style="<?php echo $fontFamily ?> <?php echo $style['button'] ?> <?php echo $style['button--blue'] ?>"
													   class="button" target="_blank">
														UPLOAD DOC
													</a>
												<?php else: ?>
													<a href="#"
													   style="<?php echo $fontFamily ?> <?php echo $style['button'] ?> <?php echo $style['button--blue'] ?>"
													   class="button">
														UPLOAD DOC
													</a>
												<?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>

									<p style="<?php echo $style['paragraph'] ?>">
										For further information please contact our purchasing at <?= get_setting('email_support'); ?>.
									</p>

                                    <!-- Salutation -->
                                    <p style="<?php echo $style['paragraph'] ?>">
                                        Regards,<br>
                                        <b>Purchasing <?= get_setting('company_name'); ?></b>
                                    </p>

                                    <p><b>USE BUTTON <span style="color: maroon">[UPLOAD DOC]</span> TO CONFIRM<br>(DO NOT REPLY DIRECTLY IN THIS EMAIL SENDER)</b></p>

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
