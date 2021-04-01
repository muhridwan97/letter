<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title">Satisfied Recipient <?= get_url_param('year') ?></h4>
			<a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
				<i class="mdi mdi-file-download-outline mr-0"></i>
			</a>
        </div>
		<div class="table-responsive">
			<table class="table table-hover table-md mt-3 responsive text-nowrap">
				<thead>
				<tr>
					<th>No</th>
					<th>No Purchase</th>
					<th>Date</th>
					<th>Item</th>
					<th>Type</th>
					<th>Requester</th>
					<th>Department</th>
					<th>Branch</th>
					<th>Selected Vendor</th>
					<th>Value</th>
					<th class="text-md-center">Rating User</th>
					<th class="text-md-center">Rating</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($purchaseOrders as $index => $purchaseOrder): ?>
					<tr>
						<td><?= $index + 1 ?></td>
						<td>
							<a href="<?= site_url('purchasing/order/view/' . $purchaseOrder['id']) ?>">
								<?= $purchaseOrder['no_purchase'] ?>
							</a>
						</td>
						<td><?= $purchaseOrder['date'] ?></td>
						<td><?= $purchaseOrder['request_title'] ?></td>
						<td>-</td>
						<td><?= $purchaseOrder['employee_name'] ?></td>
						<td><?= $purchaseOrder['department'] ?></td>
						<td><?= $purchaseOrder['location'] ?></td>
						<td><?= $purchaseOrder['vendor'] ?></td>
						<td>Rp. <?= numerical($purchaseOrder['total_price']) ?></td>
						<td class="text-md-center">
							<i class="mdi mdi-star-outline mr-1"></i><?= if_empty($purchaseOrder['rating_user'], 0) ?>
						</td>
						<td class="text-md-center">
							<i class="mdi mdi-star-outline mr-1"></i><?= if_empty($purchaseOrder['rating'], 0) ?>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php if(empty($purchaseOrders)): ?>
					<tr>
						<td colspan="11">No purchase data available</td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="9">Average Rating</td>
					<td>Rp. <?= numerical(array_sum(array_column($purchaseOrders, 'total_price'))) ?></td>
					<td class="text-md-center">
						<i class="mdi mdi-star-outline mr-1"></i>
						<?= numerical(array_sum(array_column($purchaseOrders, 'rating_user')) / if_empty(count($purchaseOrders), 1)) ?>
					</td>
					<td class="text-md-center">
						<i class="mdi mdi-star-outline mr-1"></i>
						<?= numerical(array_sum(array_column($purchaseOrders, 'rating')) / if_empty(count($purchaseOrders), 1)) ?>
					</td>
				</tr>
				</tfoot>
			</table>
		</div>
    </div>
</div>
