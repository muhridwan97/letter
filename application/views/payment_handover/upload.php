<form action="<?= site_url('purchasing/payment_handover/upload') ?>" method="POST" id="form-upload-handover" enctype="multipart/form-data">
    <?= _csrf() ?>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Upload Payment Handover</h4>
            <?= form_error('items[]') ?>
            <div class="form-group">
                <label for="no_handover">No Payment Handover</label>
                <input type="text" class="form-control" id="no_handover" name="no_handover" readonly maxlength="100"
                        value="<?= set_value('no_handover',$paymentHandover['no_handover']) ?>" placeholder="No Payment Handover">
                <?= form_error('no_handover') ?>
                <input type="hidden" name="id_handover" value="<?= $paymentHandover['id'] ?>">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="handover_date">Handover Date</label>
                        <input type="text" class="form-control datepicker" id="handover_date" name="handover_date" required maxlength="50" autocomplete="off"
                               value="<?= set_value('handover_date') ?>" placeholder="Handover Date">
                        <?= form_error('handover_date') ?>
                        <span class="form-text">Input according to the date on the attachment</span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="document">Attachment</label>
                        <input type="file" id="document" name="document" class="file-upload-default" data-max-size="3000000" required>
                        <div class="input-group">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload file">
                            <div class="input-group-append">
                                <button class="file-upload-browse btn btn-info btn-simple-upload" type="button">
                                    Upload
                                </button>
                            </div>
                        </div>
                        <?= form_error('document') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchase Order</h4>
            <?= form_error('purchase_orders[]') ?>

            <table class="table responsive" id="table-purchase-order">
                <thead>
                <tr>
                    <th>No</th>
                    <th>No Purchase</th>
                    <th>Requisition</th>
                    <th>Selected Offer</th>
                    <th>No Invoice</th>
                    <th>Invoice Date</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($purchaseOrders as $index => $purchaseOrder): ?>
                    <tr>
                        <td><?= $no++ ?><input type="hidden" class="form-control" name="purchase_orders[<?= $index ?>][id_po]" value="<?= $purchaseOrder['id_purchase_order'] ?>"></td>
                        <td><?= if_empty($purchaseOrder['no_purchase'], '-') ?></td>
                        <td><?= if_empty($purchaseOrder['request_title'], '-') ?></td>
                        <td><?= if_empty($purchaseOrder['vendor'], '-') ?></td>
                        <td><input type="text" class="form-control" name="purchase_orders[<?= $index ?>][vendor_invoice]" placeholder="No invoice" value="<?= set_value('purchase_orders[<?= $index ?>][vendor_invoice]', $purchaseOrder['vendor_invoice']) ?>" required></td>
                        <td><input type="text" class="form-control datepicker" name="purchase_orders[<?= $index ?>][invoice_date]" placeholder="Invoice date" value="<?= set_value('purchase_orders[<?= $index ?>][invoice_date]', format_date($purchaseOrder['invoice_date'],'d/m/Y')) ?>" required></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save</button>
        </div>
    </div>
</form>
