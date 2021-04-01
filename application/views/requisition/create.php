<form action="<?= site_url('requisition/save') ?>" method="POST" id="form-requisition" enctype="multipart/form-data">
    <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Create New Request</h4>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
                <div class="form-group">
                    <label for="employee">Requester</label>
                    <select class="form-control select2" name="employee" id="employee" data-placeholder="Select employee" style="width: 100%" required>
                        <option value="">-- Select Requester --</option>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['id'] ?>"<?= set_select('employee', $employee['id']) ?>>
                                <?= $employee['name'] ?> (<?= if_empty($employee['email'], 'No user linked') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('employee') ?>
                </div>
            <?php else: ?>
                <input type="hidden" name="employee" id="employee" value="<?= UserModel::loginData('id_employee') ?>">
            <?php endif; ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="request_title">Request Title</label>
                        <input type="text" class="form-control" id="request_title" name="request_title" required maxlength="100"
                               value="<?= set_value('request_title') ?>" placeholder="Request summary title">
                        <?= form_error('request_title') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="deadline">Deadline</label>
                        <input type="text" class="form-control" id="deadline" name="deadline" required maxlength="50" autocomplete="off"
                               value="<?= set_value('deadline') ?>" placeholder="Expected acquired the request">
                        <?= form_error('deadline') ?>
                        <span class="form-text">Request limit date until <span class="formatted-deadline">(Pick a date)</span></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="deadline_remark">Deadline Remark</label>
                        <input type="text" class="form-control" id="deadline_remark" name="deadline_remark" required maxlength="100"
                               value="<?= set_value('deadline_remark') ?>" placeholder="Reason you demand the deadline">
                        <?= form_error('deadline_remark') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="urgency">Urgency</label>
                        <select class="custom-select" name="urgency" id="urgency" readonly required>
                            <option value="VERY HIGH"<?= set_select('urgency', 'VERY HIGH') ?>>VERY HIGH</option>
                            <option value="HIGH"<?= set_select('urgency', 'HIGH') ?>>HIGH</option>
                            <option value="NORMAL"<?= set_select('urgency', 'NORMAL') ?>>NORMAL</option>
                            <option value="LOW"<?= set_select('urgency', 'LOW') ?>>LOW</option>
                        </select>
                        <?= form_error('urgency') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="document">Attachment</label>
                        <input type="file" id="document" name="document" class="file-upload-default" data-max-size="3000000">
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
			<div class="form-group">
				<label for="no_reference_remark">No Reference Remark</label>
				<input type="text" class="form-control" id="no_reference_remark" name="no_reference_remark" maxlength="100"
					   value="<?= set_value('no_reference_remark') ?>" placeholder="No reference remark">
				<?= form_error('no_reference_remark') ?>
			</div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500" rows="5"
                          placeholder="Request note"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Request Items</h4>
            <?= form_error('items[]') ?>

            <table class="table" id="table-item">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $items = set_value('items', []); ?>
                <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="label-item"><?= $item['item_name'] ?></td>
                        <td class="label-brand"><?= $item['brand'] ?></td>
                        <td class="label-type"><?= $item['type'] ?></td>
                        <td class="label-quantity"><?= $item['quantity'] ?></td>
                        <td class="label-unit"><?= $item['unit'] ?></td>
                        <td class="label-description"><?= $item['description'] ?></td>
                        <td class="text-right">
                            <input type="hidden" name="items[<?= $index ?>][id]" id="id" value="">
                            <input type="hidden" name="items[<?= $index ?>][id_item]" id="id_item" value="<?= $item['id_item'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][item_name]" id="item_name" value="<?= $item['item_name'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][brand]" id="brand" value="<?= $item['brand'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][type]" id="type" value="<?= $item['type'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][quantity]" id="quantity" value="<?= $item['quantity'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][unit]" id="unit" value="<?= $item['unit'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][specification]" id="unit" value="<?= $item['specification'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][description]" id="description" value="<?= $item['description'] ?>">
                            <button class="btn btn-sm btn-outline-info btn-edit" type="button">
                                <i class="mdi mdi-square-edit-outline"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($items)): ?>
                    <tr class="row-placeholder">
                        <td colspan="8">No items available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-sm btn-info" id="btn-add-item" type="button">ADD ITEM</button>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="button" class="btn btn-success btn-set-approve-type" data-toggle="one-touch" data-touch-message="Saving...">Save Request</button>
        </div>
    </div>
</form>

<?php $this->load->view('requisition/_modal_form_item') ?>
<?php $this->load->view('requisition/_modal_requisition_approved') ?>
