<form action="<?= site_url('purchasing/payment_handover/update_po') ?>" method="POST" id="form-edit-po" enctype="multipart/form-data">
    <?= _csrf() ?>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit Payment Handover</h4>
            <div class="form-group">
                <label for="no_handover">No Payment Handover</label>
                <input type="text" class="form-control" id="no_handover" name="no_handover" readonly maxlength="100"
                        value="<?= set_value('no_handover',$paymentHandover['no_handover']) ?>" placeholder="No Payment Handover">
                <?= form_error('no_handover') ?>
                <input type="hidden" name="id_handover" value="<?= $paymentHandover['id'] ?>">
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
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($purchaseOrders as $index => $purchaseOrder): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="label-no-purchase">
                        <a href="<?= base_url('purchasing/order/view/'). $purchaseOrder['id_purchase_order'] ?>" target="_blank">
                        <?= if_empty($purchaseOrder['no_purchase'], '-') ?>
                        </a>                        
                        </td>
                        <td class="label-requisition"><?= if_empty($purchaseOrder['request_title'], '-') ?></td>
                        <td class="label-vendor"><?= if_empty($purchaseOrder['vendor'], '-') ?></td>
                        <td><input type="text" class="form-control" name="purchase_orders[<?= $index ?>][vendor_invoice]" placeholder="No invoice" value="<?= set_value('purchase_orders[<?= $index ?>][vendor_invoice]',$purchaseOrder['vendor_invoice']) ?>"></td>
                        <td><input type="text" class="form-control datepicker" name="purchase_orders[<?= $index ?>][invoice_date]" placeholder="Invoice date" value="<?= set_value('purchase_orders[<?= $index ?>][invoice_date]', format_date($purchaseOrder['invoice_date'],'d/m/Y')) ?>"></td>
                        <td class="text-right">
                            <input type="hidden" name="purchase_orders[<?= $index ?>][id_purchase_order]" id="id_purchase_order" value="<?= $purchaseOrder['id_purchase_order'] ?>">
                            <!-- <button class="btn btn-sm btn-outline-info btn-edit" type="button">
                                <i class="mdi mdi-square-edit-outline"></i>
                            </button> -->
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($purchaseOrders)): ?>
                    <tr class="row-placeholder">
                        <td colspan="5">No PO available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-sm btn-primary" id="btn-add-item" type="button">ADD Purchase Order</button>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save</button>
        </div>
    </div>
</form>
<?php $this->load->view('payment_handover/_modal_form_item') ?>