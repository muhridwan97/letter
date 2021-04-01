<div class="modal fade" id="modal-form-item" aria-labelledby="modalFormItem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" id="form-equipment-item">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilter">Equipment Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="purchase_order">Purchase Order</label>
                        <select class="form-control select2" id="purchase_order" name="purchase_order" data-placeholder="Select equipment" required style="width: 100%">
                            <option value=""></option>
                            <?php foreach ($listPurchaseOrders as $purchaseOrder): ?>
                                <option value="<?= $purchaseOrder['id_purchase_order'] ?>" 
                                data-no-purchase="<?= $purchaseOrder['no_purchase'] ?>"
                                data-request-title="<?= $purchaseOrder['request_title'] ?>"
                                data-vendor="<?= $purchaseOrder['vendor'] ?>">
                                    <?= $purchaseOrder['no_purchase'] ?> (<?= $purchaseOrder['request_title'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">
                        CLOSE
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" id="btn-save">
                        SAVE ITEM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
