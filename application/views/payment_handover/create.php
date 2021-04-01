<form action="<?= site_url('purchasing/payment_handover/save') ?>" method="POST" id="form-payment-handover">
    <?= _csrf() ?>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">New Payment Handover</h4>
            <?= form_error('items[]') ?>

            <div class="form-group">
                <label for="purchase_order">Purchase Order</label>
                <select class="form-control select2" name="purchase_orders[]" id="purchase_order" data-placeholder="Select purchase order" multiple>
                    <?php foreach ($purchaseOrders as $purchaseOrder): ?>
                        <option value="<?= $purchaseOrder['id_purchase_order'] ?>" <?= set_select('purchase_orders[]', $purchaseOrder['id_purchase_order'] ) ?>>
                            <?= $purchaseOrder['no_purchase'] ?> ( <?= $purchaseOrder['request_title']?> )
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="form-text">Multiple purchase order city field allowed.</p>
                <?= form_error('purchase_orders[]') ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save</button>
        </div>
    </div>
</form>
