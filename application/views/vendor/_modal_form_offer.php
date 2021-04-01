<div class="modal fade" id="modal-form-offer" aria-labelledby="modalFormItem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" id="form-filter">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilter">Requisition Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control" name="item_name" id="item_name"
                               placeholder="Item or service with specification" required maxlength="100">
                        <?= form_error('item_name'); ?>
                    </div>
                    <div class="form-group">
                        <label for="brand">Brand</label>
                        <input type="text" class="form-control" name="brand" id="brand"
                               placeholder="Brand title" maxlength="30">
                        <?= form_error('brand'); ?>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control currency" name="price" id="price"
                                       placeholder="Price per item" maxlength="30" required>
                                <?= form_error('price'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <input type="text" class="form-control currency" name="discount" id="discount"
                                       placeholder="Discount per item" maxlength="30">
                                <?= form_error('discount'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price_date">Price Date</label>
                                <input type="text" class="form-control datepicker" name="price_date" id="price_date"
                                       placeholder="Price active since" maxlength="30" required>
                                <?= form_error('price_date'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="price_until">Price Until</label>
                                <input type="text" class="form-control datepicker" name="price_until" id="price_until"
                                       placeholder="Price date relevant until" maxlength="30">
                                <?= form_error('price_until'); ?>
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
                        SAVE ITEM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>