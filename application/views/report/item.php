<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                Purchase Item
            </h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>

		<div class="table-responsive">
			<table class="table table-hover mt-3 table-md responsive text-nowrap" id="table-history">
				<thead>
				<tr>
					<th class="text-md-center" style="width: 60px">No</th>
					<th>Request Title</th>
					<th>Request Date</th>
					<th>Item Name</th>
					<th>Quantity</th>
					<th class="text-md-right">Unit Price</th>
					<th>Vendor</th>
					<th>Offer Date</th>
					<th>No Purchase</th>
					<th>Purchase Date</th>
					<th>Type</th>
				</tr>
				</thead>
				<tbody>
				<?php $no = ($reports['current_page'] - 1) * $reports['per_page'] ?>
				<?php foreach ($reports['data'] as $report): ?>
					<tr>
						<td class="text-md-center"><?= ++$no ?></td>
						<td><?= $report['request_title'] ?></td>
						<td><?= $report['request_date'] ?></td>
						<td>
							<?php if($report['item_price_type'] == 'OFFER'): ?>
								<?= $report['item_name'] ?>
							<?php else: ?>
								<a href="<?= site_url('purchasing/offer/view/' . $report['id_purchase_offer']) ?>">
									<?= $report['item_name'] ?>
								</a>
							<?php endif; ?>
						</td>
						<td><?= numerical($report['quantity']) ?> <?= $report['unit'] ?></td>
						<td class="text-md-right">Rp. <?= numerical($report['unit_price']) ?></td>
						<td><?= $report['vendor'] ?></td>
						<td><?= $report['offer_date'] ?></td>
						<td>
							<?php if($report['item_price_type'] == 'OFFER'): ?>
								-
							<?php else: ?>
								<a href="<?= site_url('purchasing/order/print-order/' . $report['id_purchase_order']) ?>">
									<?= $report['no_purchase'] ?>
								</a>
							<?php endif; ?>
						</td>
						<td><?= $report['purchase_date'] ?></td>
						<td><?= $report['item_price_type'] ?></td>
					</tr>
				<?php endforeach; ?>
				<?php if (empty($reports['data'])): ?>
					<tr>
						<td colspan="7">No item data found</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $reports]) ?>
    </div>
</div>

<?php $this->load->view('report/_modal_filter_item') ?>
