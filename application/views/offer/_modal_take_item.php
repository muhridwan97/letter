<div class="modal fade" id="modal-take-item" aria-labelledby="modalFormItem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" id="form-take-item">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilter">Take Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" name="item_name" id="item_name" readonly
                               placeholder="Item or service with specification" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity"
                               placeholder="Quantity" required min="0.1" step="any">
                        <?= form_error('quantity'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price">Unit Price</label>
                                <input type="text" class="form-control currency" name="price" id="price"
                                       placeholder="Price Per Item" required maxlength="30" autocomplete="off">
                                <?= form_error('price'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="discount">Unit Discount</label>
                                <input type="text" class="form-control currency" name="discount" id="discount"
                                       placeholder="Discount per item" maxlength="30" autocomplete="off">
                                <?= form_error('price'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"
                                  placeholder="Note or detail about the item" maxlength="500"></textarea>
                        <?= form_error('description'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">
                        CLOSE
                    </button>
                    <button type="submit" class="btn btn-sm btn-info" id="btn-save">
                        TAKE ITEM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>