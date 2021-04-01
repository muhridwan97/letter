<div class="modal fade" id="modal-select-item" aria-labelledby="modalFormSelectItem">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" id="form-select-item" method="post">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilter">Select Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" class="form-control bg-light" id="item_name" readonly
                               placeholder="Item or service with specification" autocomplete="off">
                    </div>
                    <div class="form-row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="quantity">Select Quantity</label>
                                <input type="number" class="form-control" name="quantity_select" id="quantity_select"
                                       placeholder="Quantity" required min="0" step="any">
                                <span class="form-text">Set to 0 to remove selection of item</span>
                                <?= form_error('quantity'); ?>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="unit">From</label>
                                <input type="text" class="form-control bg-light" id="unit" readonly
                                       placeholder="Unit item" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                        CLOSE
                    </button>
                    <button type="submit" class="btn btn-sm btn-info" id="btn-save-select">
                        SELECT ITEM
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>