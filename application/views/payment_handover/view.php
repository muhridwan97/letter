<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Payment Handover</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="no_handover">No Handover</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="no_handover">
                                <?= $paymentHandover['no_handover'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="handover_date">Handover Date</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="handover_date">
                                <?= if_empty(format_date($paymentHandover['handover_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Status</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="status">
                                <?php if(empty($paymentHandover['document'])): ?>
                                <span class="badge badge-primary">
                                    PRINTED
                                </span>
                                <?php else: ?>
                                <span class="badge badge-success">
                                    UPLOADED
                                </span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="attachment">Attachment</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="attachment">
                                <?php if (empty($paymentHandover['document'])) : ?>
                                    -
                                <?php else : ?>
                                    <a href="<?= asset_url($paymentHandover['document']) ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchase Order</h4>
            <table class="table responsive" id="table-purchase-order">
                <thead>
                <tr>
                    <th>No</th>
                    <th>No Purchase</th>
                    <th>Requisition</th>
                    <th>Selected Offer</th>
                    <th>Vendor Invoice</th>
                    <th>Invoice Date</th>
                    <th>Requister</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($purchaseOrders as $purchaseOrder): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                        <a href="<?= site_url('purchasing/order/view/' . $purchaseOrder['id_purchase_order']) ?>" target="_blank">
                            <?= $purchaseOrder['no_purchase'] ?>
                        </a></td>
                        <td><?= if_empty($purchaseOrder['request_title'], '-') ?></td>
                        <td><?= if_empty($purchaseOrder['vendor'], '-') ?></td>
                        <td><?= if_empty($purchaseOrder['vendor_invoice'], '-') ?></td>
                        <td><?= if_empty(format_date($purchaseOrder['invoice_date'], 'd F Y'), '-') ?></td>
                        <td><?= if_empty($purchaseOrder['employee_name'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <!-- <a href="<?= site_url('purchasing/handover/print-handover/' . $paymentHandover['id']) ?>" class="btn btn-primary">Print Handover</a> -->
        </div>
    </div>
</form>