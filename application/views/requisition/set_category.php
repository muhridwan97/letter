<form action="<?= site_url('requisition/update_category/'.$requisition['id']) ?>" method="POST" id= "form-set-category">
     <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body form-plaintext">
            <h4 class="card-title">Requisition</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $requisition['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="request_title">
                                <?= $requisition['request_title'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm-9">
                            <?php
                            $statusLabels = [
                                RequisitionModel::STATUS_PENDING => 'secondary',
                                RequisitionModel::STATUS_REJECTED => 'danger',
                                RequisitionModel::STATUS_CANCELLED => 'danger',
                                RequisitionModel::STATUS_APPROVED => 'success',
                                RequisitionModel::STATUS_LISTED => 'dark',
                                RequisitionModel::STATUS_ASK_SELECTION => 'info',
                                RequisitionModel::STATUS_READY => 'success',
                                RequisitionModel::STATUS_SELECTED => 'primary',
                                RequisitionModel::STATUS_IN_PROCESS => 'warning',
                                RequisitionModel::STATUS_DONE => 'success',
                            ];
                            ?>
                            <p class="form-control-plaintext" id="status">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
                                    <?= $requisition['status'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="deadline">Deadline</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline">
                                <?= format_date($requisition['deadline'], 'd F Y') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="deadline_remark">Remark</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline_remark">
                                <?= if_empty($requisition['deadline_remark'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="urgency">Urgency</label>
                        <div class="col-sm-9">
                            <?php
                            $statusLabels = [
                                'LOW' => 'success',
                                'NORMAL' => 'primary',
                                'HIGH' => 'warning',
                                'VERY HIGH' => 'danger',
                            ]
                            ?>
                            <p class="form-control-plaintext" id="urgency">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['urgency'], 'secondary') ?>">
                                    <?= $requisition['urgency'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="approved_type">Type</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="approved_type">
                                <span class="badge badge-<?= $requisition['approved_type']=='INTERNAL'?'success':'warning' ?>">
                                    <?= if_empty($requisition['approved_type'],"NOTHING") ?>
                                </span>
							</p>
						</div>
					</div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="attachment">Attachment</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="attachment">
                                <?php if (empty($requisition['document'])) : ?>
                                    -
                                <?php else : ?>
                                    <a href="<?= base_url('uploads/' . $requisition['document']) ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="requester">
                                <?= if_empty($requisition['employee_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($requisition['description'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($requisition['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($requisition['creator_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
			<h4 class="card-title">Category</h4>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="category">Set Category</label>
						<select class="font-control select2" data-placeholder="Select category" name="category" id="category" required style="width: 100%">
							<option value="">-- Select Category --</option>
							<?php foreach ($categories as $category): ?>
								<option value="<?= $category['id'] ?>"<?= set_select('category', $category['id'], $category['id'] == $requisition['id_category']) ?>>
									<?= $category['category'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('category') ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="item_category">Set Item Category</label>
						<select class="font-control select2" data-placeholder="Select item category" name="item_category" id="item_category" required style="width: 100%">
							<option value="">-- Select Item Category --</option>
							<?php foreach ($itemCategories as $itemCategory): ?>
								<option value="<?= $itemCategory['id'] ?>"<?= set_select('item_category', $itemCategory['id'], $itemCategory['id'] == $requisition['id_item_category']) ?>>
									<?= $itemCategory['item_name'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('category') ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group mb-0">
						<label for="rate_type">Rate Type</label>
						<select class="font-control select2" data-placeholder="Select rate type" name="rate_type" id="rate_type" required style="width: 100%">
							<option value="">-- Select Rate Type --</option>
							<option value="<?= RequisitionModel::RATE_TYPE_FIXED ?>"<?= set_select('rate_type', RequisitionModel::RATE_TYPE_FIXED, RequisitionModel::RATE_TYPE_FIXED == $requisition['rate_type']) ?>>
								<?= RequisitionModel::RATE_TYPE_FIXED ?> (Fix quantity and PO)
							</option>
							<option value="<?= RequisitionModel::RATE_TYPE_ADJUSTABLE ?>"<?= set_select('rate_type', RequisitionModel::RATE_TYPE_ADJUSTABLE, RequisitionModel::RATE_TYPE_ADJUSTABLE == $requisition['rate_type']) ?>>
								<?= RequisitionModel::RATE_TYPE_ADJUSTABLE ?> (Quantity updated after job completed)
							</option>
						</select>
						<?= form_error('rate_type') ?>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group mb-0">
						<label for="request_type">Request Type</label>
						<select class="font-control select2" data-placeholder="Select request type" name="request_type" id="request_type" required style="width: 100%">
							<option value="">-- Select Request Type --</option>
							<option value="<?= RequisitionModel::REQUEST_TYPE_ROUTINE ?>"<?= set_select('request_type', RequisitionModel::REQUEST_TYPE_ROUTINE, RequisitionModel::REQUEST_TYPE_ROUTINE == $requisition['request_type']) ?>>
								<?= RequisitionModel::REQUEST_TYPE_ROUTINE ?>
							</option>
							<option value="<?= RequisitionModel::REQUEST_TYPE_URGENT ?>"<?= set_select('request_type', RequisitionModel::REQUEST_TYPE_URGENT, RequisitionModel::REQUEST_TYPE_URGENT == $requisition['request_type']) ?>>
								<?= RequisitionModel::REQUEST_TYPE_URGENT ?>
							</option>
							<option value="<?= RequisitionModel::REQUEST_TYPE_PROJECT ?>"<?= set_select('request_type', RequisitionModel::REQUEST_TYPE_PROJECT, RequisitionModel::REQUEST_TYPE_PROJECT == $requisition['request_type']) ?>>
								<?= RequisitionModel::REQUEST_TYPE_PROJECT ?>
							</option>
						</select>
						<?= form_error('request_type') ?>
					</div>
				</div>
			</div>

        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Set Item Categories</h4>
            <table class="table responsive item-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($requisitionItems as $index => $item) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= if_empty($item['item_name'], '-') ?></td>
                            <td><?= if_empty($item['brand'], '-') ?></td>
                            <td><?= if_empty($item['type'], '-') ?></td>
                            <td><?= if_empty($item['quantity'], '-') ?></td>
                            <td><?= if_empty($item['unit'], '-') ?></td>
                            <td><?= if_empty($item['description'], '-') ?></td>
                            <td><?= if_empty(format_date($item['created_at'], 'd F Y H:i'), '-') ?></td>
                        </tr>
                        <input type="hidden" name="items[<?= $index ?>][item_id]" value="<?= $item['id'] ?>">
                    <?php endforeach; ?>
                    <?php if (empty($requisitionItems)) : ?>
                        <tr>
                            <td colspan="8">No requisition items</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between" id = "view-validate">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if (AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)) : ?>
                <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save Data</button>
            <?php endif; ?>
        </div>
    </div>
</form>
