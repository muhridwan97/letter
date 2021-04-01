<form action="<?= site_url('purchasing/vendor-completion/save-order/' . $purchaseOrder['id'] . '?token=' . $this->input->get('token')) ?>" method="POST" enctype="multipart/form-data" id= "form-item-completion">
     <?= _csrf() ?>

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

	<?php if($purchaseOrder['status'] == PurchaseOfferModel::STATUS_SELECTED): ?>
		<div class="alert alert-warning">
			<h5 class="mb-1">Upload Work Sheet</h5>
			<p class="mb-0">
				We need your confirmation for detail of your work such as <strong>Job Order Document</strong>,
				<strong>invoices</strong> or <strong>Rent Duration (Heavy equipment leasing sheet)</strong>
			</p>
		</div>
	<?php else: ?>
		<div class="alert alert-success">
			<h5 class="mb-1">Document Uploaded</h5>
			<p class="mb-0">
				Vendor completion is uploaded and completed
			</p>
		</div>
	<?php endif; ?>

	<div class="card grid-margin">
		<div class="card-body">
			<h4 class="card-title">Order Attachment</h4>
			<div class="form-group">
				<label for="document">Upload Document</label>
				<?php if($purchaseOrder['status'] == PurchaseOfferModel::STATUS_SELECTED): ?>
					<div class="input-group">
						<input type="text" class="form-control file-upload-info" value="<?= $purchaseOrder['document'] ?>" disabled placeholder="Upload file">
						<div class="input-group-append">
							<button class="file-upload-browse btn btn-info btn-simple-upload" type="button">
								Upload
							</button>
						</div>
					</div>
					<input type="file" id="document" name="document" class="file-upload-default"
							<?= empty($purchaseOrder['document']) ? 'required' : '' ?> data-max-size="3000000">
					<span class="form-text">Upload your worksheet or invoice for job completion</span>
					<?= form_error('document') ?>
				<?php else: ?>
					<p class="form-control mb-0">
						<?php if(empty($purchaseOrder['document'])): ?>
							-
						<?php else: ?>
							Download
							<a href="<?= asset_url($purchaseOrder['document']) ?>">
								<?= $purchaseOrder['document'] ?>
							</a>
						<?php endif; ?>
					</p>
				<?php endif; ?>
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
				</tr>
				</thead>
				<tbody>
				<?php foreach ($purchaseOfferItems as $index => $item) : ?>
					<tr>
						<td><?= $index + 1 ?></td>
						<td><?= if_empty($item['item_name'], '-') ?></td>
						<td><?= if_empty($item['brand'], '-') ?></td>
						<td><?= if_empty($item['unit'], '-') ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

	<?php if($purchaseOrder['status'] == PurchaseOfferModel::STATUS_SELECTED): ?>
		<div class="card grid-margin">
			<div class="card-body text-right">
				<button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Upload Data</button>
			</div>
		</div>
	<?php endif; ?>
</form>
