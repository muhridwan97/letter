<div class="modal fade" id="modal-requisition-approved" tabindex="-1" role="dialog" aria-labelledby="Set approved type Requisition" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" method="post">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Approve Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type">Set Approved Type</label>
                        <select class="custom-select type" name="type" id="type" required>
                            <option value="">-- Select Type --</option>
                            <option value="INTERNAL"> <?= "INTERNAL" ?></option>
                            <option value="EXTERNAL"> <?= "EXTERNAL" ?></option>
                        </select>
                    </div>
					<div class="form-group modal-customer" style="display:none;">
						<label for="customer">Customer Name</label> <br>
						<select class="form-control select2 customer" name="customer[]" id="customer" multiple data-placeholder="Select Customer" style="width: 100%;">
							<?php foreach ($customers as $customer): ?>
								<option value="<?= $customer['id'] ?>"<?= set_select('category', $customer['id']) ?>>
									<?= $customer['name'] ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group modal-internal" style="display:none;">
						<label for="customer-internal">Internal Name</label> <br>
						<input type="text" id="customer-internal" value= "Transcon Indonesia" class="form-control" readonly>
					</div>
					<div class="form-group">
						<label for="message">Message</label> <br>
                        <textarea name="message" id="message" placeholder="Input Message" class="form-control"></textarea>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
