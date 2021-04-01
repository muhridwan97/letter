<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchase Order</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_purchase">No Purchase</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="no_purchase">
                                <?= $purchaseOrder['no_purchase'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="request_title">
                                <a href="<?= site_url('requisition/view/' . $purchaseOrder['id_requisition']) ?>">
                                    <?= $purchaseOrder['request_title'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="vendor">Vendor Offer</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="vendor">
                                <a href="<?= site_url('purchasing/offer/view/' . $purchaseOrder['id_purchase_offer']) ?>">
                                    <?= $purchaseOrder['vendor'] ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm-9">
							<p class="form-control-plaintext" id="status">
								<?= $purchaseOrder['status'] ?>
							</p>
						</div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Doc Status</label>
                        <div class="col-sm-9">
							<p class="form-control-plaintext" id="status">
								<?= $purchaseOrder['document_status'] ?>
							</p>
						</div>
                    </div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="attachment">Attachment</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="attachment">
								<?php if(empty($purchaseOrder['document'])): ?>
									-
								<?php else: ?>
									<a href="<?= asset_url($purchaseOrder['document']) ?>">
										Download
									</a>
								<?php endif; ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="payment_handover">Payment Handover</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="payment_handover">
								<?php if(empty($purchaseOrder['payment_handover_doc'])): ?>
									-
								<?php else: ?>
									<a href="<?= asset_url($purchaseOrder['payment_handover_doc']) ?>">
										Download
									</a>
								<?php endif; ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="vendor_invoice">Vendor Invoice</label>
                        <div class="col-sm-9">
							<p class="form-control-plaintext" id="vendor_invoice">
								<?= if_empty($purchaseOrder['vendor_invoice'],'No Invoice') ?>
							</p>
						</div>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-3 col-form-label" for="date">Date</label>
						<div class="col-sm-9">
							<p class="form-control-plaintext" id="date">
								<?= format_date($purchaseOrder['date'], 'd F Y') ?>
							</p>
						</div>
					</div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="purchasing_admin">Admin</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="purchasing_admin">
                                <?= $purchaseOrder['purchasing_admin'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="purchasing_supervisor">Admin SPV</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="purchasing_supervisor">
                                <?= $purchaseOrder['purchasing_supervisor'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($purchaseOrder['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($purchaseOrder['purchasing_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($purchaseOrder['updated_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
					<div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="invoice_date">Invoice Date</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="invoice_date">
                                <?= if_empty(format_date($purchaseOrder['invoice_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="card grid-margin">
		<div class="card-body">
			<h4 class="card-title">Order Details</h4>
			<table class="table responsive">
				<thead>
				<tr>
					<th>No</th>
					<th>Item</th>
					<th>Quantity</th>
					<th class="text-md-right text-nowrap">Unit Price</th>
					<th class="text-md-right text-nowrap">Unit Discount</th>
					<th class="text-md-right text-nowrap">Ext Price</th>
					<th class="text-md-right text-nowrap">Ext Discount</th>
					<th class="text-md-right text-nowrap">Total Price</th>
				</tr>
				</thead>
				<tbody>
				<?php $no = 1 ?>
				<?php foreach ($purchaseOfferItems as $item): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td>
							<p class="mb-0"><?= if_empty($item['item_name'], '-') ?></p>
							<small class="text-muted"><?= $item['brand'] ?></small>
							<small class="text-muted"><?= $item['specification'] ?></small>
							<small class="text-muted"><?= if_empty($item['description'], '', '(', ')') ?></small>
						</td>
						<td>
							<?= if_empty(numerical($item['quantity_selected']), '0') ?>
							<?= if_empty($item['unit'], '-') ?>
						</td>
						<td class="text-md-right text-nowrap">Rp. <?= numerical($item['price']) ?></td>
						<td class="text-md-right text-nowrap">(Rp. <?= numerical($item['discount']) ?>)</td>
						<td class="text-md-right text-nowrap">Rp. <?= numerical($item['selected_extended_price']) ?></td>
						<td class="text-md-right text-nowrap">(Rp. <?= numerical($item['selected_extended_discount']) ?>)</td>
						<td class="text-md-right text-nowrap"><strong>Rp. <?= numerical($item['selected_total']) ?></strong></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<th colspan="7">Sub Total</th>
					<th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOffer['selected_extended_total']) ?></th>
				</tr>
				<tr>
					<th colspan="7">Offer Discount</th>
					<th class="text-md-right text-nowrap">(Rp. <?= numerical($purchaseOffer['offer_discount']) ?>)</th>
				</tr>
				<tr>
					<th colspan="7">Rounding Amount</th>
					<th class="text-md-right text-nowrap">
						<?php if (($purchaseOrder['rounding_amount']) < 0): ?>
							<strong>(Rp. <?= numerical(abs($purchaseOrder['rounding_amount'])) ?>)</strong>
						<?php else: ?>
							<strong>Rp. <?= numerical($purchaseOrder['rounding_amount']) ?></strong>
						<?php endif; ?>
					</th>
				</tr>
				<tr>
					<th colspan="7">Tax <?= empty($purchaseOrder['tax_percentage']) || $purchaseOrder['tax_percentage'] == '0' ? '' : '(' . round($purchaseOrder['tax_percentage']) . '%)' ?></th>
					<th class="text-md-right text-nowrap">Rp. <?= if_empty(numerical($purchaseOrder['tax']), 0) ?></th>
				</tr>
				<tr>
					<th colspan="7">Delivery Cost</th>
					<th class="text-md-right text-nowrap">Rp. <?= numerical($purchaseOrder['delivery_cost']) ?></th>
				</tr>
				<tr>
					<th colspan="7"><?= ucwords(strtolower(if_empty($purchaseOrder['miscellaneous_label'], 'Miscellaneous'))) ?></th>
					<th class="text-md-right text-nowrap">
						<?php if (($purchaseOrder['miscellaneous_expense']) < 0): ?>
							<strong>(Rp. <?= numerical(abs($purchaseOrder['miscellaneous_expense'])) ?>)</strong>
						<?php else: ?>
							<strong>Rp. <?= numerical($purchaseOrder['miscellaneous_expense']) ?></strong>
						<?php endif; ?>
					</th>
				</tr>
				<tr>
					<th colspan="7">
						<?= ucwords(strtolower(if_empty($purchaseOrder['tax_basis_label'], 'Tax Basis'))) ?>
						<?= empty($purchaseOrder['tax_basis_percentage']) || $purchaseOrder['tax_basis_percentage'] == '0' ? '' : '(' . numerical($purchaseOrder['tax_basis_percentage']) . '%)' ?>
					</th>
					<th class="text-md-right text-nowrap">(Rp. <?= numerical($purchaseOrder['tax_basis']) ?>)</th>
				</tr>
				<tr class="table-success">
					<th colspan="7">Total Price</th>
					<th class="text-md-right text-nowrap">Rp. <?= if_empty(numerical($purchaseOrder['total_price']), 0) ?></th>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<a href="<?= site_url('purchasing/order/print-order/' . $purchaseOrder['id']) ?>" class="btn btn-primary">
				Print <?= $purchaseOrder['document_status'] == PurchaseOrderModel::STATUS_DRAFT ? 'Draft' : 'Order' ?>
			</a>
        </div>
    </div>
</form>
