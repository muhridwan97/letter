<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
			<div>
				<h4 class="card-title mb-0">Reorder Item <?= get_url_param('year_week') ?></h4>
				<p class="mb-0 text-success"><?= ucwords(str_replace(['-', '_'], ' ', get_url_param('type'))) ?></p>
			</div>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-md mt-3 responsive">
            <thead>
			<tr>
				<th rowspan="2" class="border-top">No</th>
				<th rowspan="2" class="border-top">Item</th>
				<th rowspan="2" class="border-top">Item Price</th>
				<th rowspan="2" class="border-top">From PO</th>
				<th rowspan="2" class="border-top">PO Created At</th>
				<?php if (empty(get_url_param('type')) || get_url_param('type') == 'all'): ?>
					<td colspan="3" class="font-weight-bold border-bottom text-md-center">
						Cheapest Before
					</td>
					<td colspan="3" class="font-weight-bold border-bottom text-md-center">
						Previous Latest
					</td>
				<?php else: ?>
					<td colspan="3" class="font-weight-bold border-bottom text-md-center">
						Compared <?= if_empty(ucwords(str_replace(['-', '_'], ' ', get_url_param('type'))), '', '(', ')') ?>
					</td>
				<?php endif; ?>
			</tr>
            <tr>
				<th>By Price</th>
				<th>By PO</th>
				<th>By Created At</th>
				<?php if (empty(get_url_param('type')) || get_url_param('type') == 'all'): ?>
					<th>By Price</th>
					<th>By PO</th>
					<th>By Created At</th>
				<?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $index => $report): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
					<td><?= $report['item_name'] ?></td>
					<td>Rp. <?= numerical($report['price']) ?></td>
					<td>
						<a href="<?= site_url('purchasing/order/view/' . $report['id']) ?>">
							<?= $report['no_purchase'] ?>
						</a>
					</td>
					<td><?= format_date($report['created_at'], 'Y-m-d H:i') ?></td>
					<?php if (empty(get_url_param('type')) || get_url_param('type') == 'all'): ?>
						<td class="table-warning">Rp. <?= numerical($report['cheapest_price']) ?></td>
						<td class="table-warning">
							<a href="<?= site_url('purchasing/order/view/' . $report['cheapest_order_id']) ?>">
								<?= $report['cheapest_order_no_purchase'] ?>
							</a>
						</td>
						<td class="table-warning"><?= format_date($report['cheapest_order_created_at'], 'Y-m-d H:i') ?></td>
						<td class="table-warning">Rp. <?= numerical($report['latest_price']) ?></td>
						<td class="table-warning">
							<a href="<?= site_url('purchasing/order/view/' . $report['latest_order_id']) ?>">
								<?= $report['latest_order_no_purchase'] ?>
							</a>
						</td>
						<td class="table-warning"><?= format_date($report['latest_order_created_at'], 'Y-m-d H:i') ?></td>
					<?php else: ?>
						<td class="table-warning">Rp. <?= numerical($report['comparator_price']) ?></td>
						<td class="table-warning">
							<a href="<?= site_url('purchasing/order/view/' . $report['comparator_order_id']) ?>">
								<?= $report['comparator_no_purchase'] ?>
							</a>
						</td>
						<td class="table-warning"><?= format_date($report['comparator_created_at'], 'Y-m-d H:i') ?></td>
					<?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($reports)): ?>
                <tr>
                    <td colspan="8">No data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
