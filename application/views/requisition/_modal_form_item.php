<div class="modal fade" id="modal-form-item" aria-labelledby="modalFormItem">
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
						<select class="form-control select2" name="item" id="item" data-placeholder="Select item" style="width: 100%" required>
							<option value="">-- Select Item --</option>
							<?php foreach ($items as $item): ?>
								<option value="<?= $item['id'] ?>" data-item-name="<?= $item['item_name'] ?>" data-unit="<?= $item['unit'] ?>" data-brand="<?= $item['brand'] ?>" data-specification="<?= $item['specification'] ?>">
									<?= $item['item_name'] ?> - <?= if_empty($item['specification'], if_empty($item['brand'], $item['unit'])) ?>
								</option>
							<?php endforeach; ?>
						</select>
						<input type="text" class="form-control" name="item_name" id="item_name" style="display: none"
							   placeholder="Item or service with specification" required maxlength="100">
						<span class="form-text">If item name is not listed above, ask Purchasing to create Master Item in advance.</span>
                        <span class="form-text">Or you can click <a href="#" nama="refresh" id="refresh">here</a> to refresh</span>
                    </div>
					<div class="form-group">
						<label for="specification">Specification</label>
						<input type="text" class="form-control" name="specification" id="specification"
							   placeholder="Item specification" style="pointer-events: none">
					</div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control" name="brand" id="brand"
                                       placeholder="Brand title" style="pointer-events: none" maxlength="50">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select class="custom-select" name="type" id="type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="GOODS">GOODS</option>
                                    <option value="SERVICES">SERVICES</option>
                                    <option value="LEASE">LEASE</option>
                                </select>
                                <?= form_error('type'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" class="form-control" name="quantity" id="quantity"
                                       placeholder="Quantity" required min="0" step="any">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="unit">Unit</label>
                                <input type="text" class="form-control" name="unit" id="unit"
                                       placeholder="Unit" required maxlength="30" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3"
                                  placeholder="Note or detail about the item" maxlength="500"></textarea>
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
