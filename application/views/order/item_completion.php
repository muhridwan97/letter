<form action="<?= site_url('purchasing/order/save-item-completion/' . $purchaseOrder['id']) ?>" method="POST" enctype="multipart/form-data" id= "form-item-completion">
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
                        <label class="col-sm-3 col-form-label" for="deadline">Deadline</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline">
                                <?= format_date($requisition['deadline'], 'd F Y') ?>
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
                        <label class="col-sm-3 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="requester">
                                <?= if_empty($requisition['employee_name'], '-') ?>
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
                </div>
            </div>
        </div>
    </div>

	<div class="card grid-margin">
		<div class="card-body form-plaintext">
			<h4 class="card-title">Vendor Offer</h4>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="vendor">Vendor</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="vendor">
								<?= $purchaseOffer['vendor'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="lead_time">Lead Time</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="lead_time">
								<?= $purchaseOffer['lead_time'] ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="no_purchase">Draft PO</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="no_purchase">
								<?= $purchaseOrder['no_purchase'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="selected_total_item_quantity">Item Total</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="selected_total_item_quantity">
								<?= $purchaseOffer['selected_total_item_quantity'] ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card grid-margin">
		<div class="card-body">
			<h4 class="card-title">Order Attachment</h4>
			<div class="form-group">
				<label for="document">Document</label>
				<div class="input-group">
					<input type="text" class="form-control file-upload-info" value="<?= $purchaseOrder['document'] ?>" disabled placeholder="Upload file">
					<div class="input-group-append">
						<?php if(!empty($purchaseOrder['document'])): ?>
							<a href="<?= asset_url($purchaseOrder['document']) ?>" class="btn btn-primary px-3" type="button">
								<i class="mdi mdi-download"></i>
							</a>
						<?php endif; ?>
						<button class="file-upload-browse btn btn-info btn-simple-upload" type="button">
							Upload
						</button>
					</div>
				</div>
				<input type="file" id="document" name="document" class="file-upload-default"
						<?= empty($purchaseOrder['document']) ? 'required' : '' ?> data-max-size="3000000">
				<?= form_error('document') ?>
			</div>
		</div>
	</div>

	<div class="card grid-margin">
		<div class="card-body">
			<h4 class="card-title">Completion Items</h4>
			<table class="table responsive">
				<thead>
				<tr>
					<th style="width: 40px">No</th>
					<th>Item Name</th>
					<th>Brand</th>
					<th>Unit</th>
					<th>Request Qty</th>
					<th>Selected Qty (Vendor)</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($purchaseOfferItems as $index => $item) : ?>
					<tr>
						<td><?= $index + 1 ?></td>
						<td><?= if_empty($item['item_name'], '-') ?></td>
						<td><?= if_empty($item['brand'], '-') ?></td>
						<td><?= if_empty($item['unit'], '-') ?></td>
						<td>
							<input type="hidden" name="items[<?= $index ?>][purchase_offer_item_id]" value="<?=  $item['id'] ?>">
							<input type="number" class="form-control needed-quantity" placeholder="Requisition" aria-label="Requisition quantity" required
								   name="items[<?= $index ?>][quantity_needed]" value="<?=  $item['quantity_needed'] ?>" step="any" min="0">
						</td>
						<td>
							<input type="number" class="form-control selected-quantity" placeholder="Selected" aria-label="Selected quantity" required
								   name="items[<?= $index ?>][quantity_selected]" value="<?=  $item['quantity_selected'] ?>" step="any" min="0">
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between" id = "view-validate">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save Data</button>
        </div>
    </div>
</form>
