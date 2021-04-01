<div class="card grid-margin">
	<div class="card-body">
		<div class="d-flex align-items-center justify-content-between">
			<h4 class="card-title">Period Filters</h4>
			<button type="button" class="btn btn-success btn-sm" id="btn-add-period">Add Period</button>
		</div>
		<form action="<?= site_url(uri_string()) ?>" id="form-filter">
			<div class="mb-3">
				<table class="table table-sm" id="table-period-filter">
					<thead>
					<tr>
						<th class="text-center" style="width: 40px">No</th>
						<th>Period From</th>
						<th>Period To</th>
						<th style="width: 30px"></th>
					</tr>
					</thead>
					<tbody>
					<tr class="row-period">
						<td class="text-center label-order">1</td>
						<td>
							<input type="text" class="form-control datepicker" name="dates[0][from]" data-update-target="#period-to-1"
								   placeholder="Pick from date" aria-label="Date from" value="<?= get_if_exist(get_if_exist(get_url_param('dates', []), 0), 'from') ?>" required>
						</td>
						<td>
							<input type="text" class="form-control datepicker" name="dates[0][to]" id="period-to-1" data-min-date="<?= get_if_exist(get_if_exist(get_url_param('dates', []), 0), 'from') ?>"
								   placeholder="Pick to date" aria-label="Date to" value="<?= get_if_exist(get_if_exist(get_url_param('dates', []), 0), 'to') ?>" required>
						</td>
						<td class="text-center">
							<button class="btn btn-sm btn-outline-danger btn-delete-period" disabled style="pointer-events: none" type="button">
								<i class="mdi mdi-trash-can-outline"></i>
							</button>
						</td>
					</tr>
					<?php foreach (get_url_param('dates', []) as $index => $date): ?>
						<?php if ($index == 0) continue; ?>
						<tr class="row-period">
							<td class="text-center label-order"><?= $index + 1 ?></td>
							<td>
								<input type="text" class="form-control datepicker" name="dates[<?= $index ?>][from]" data-update-target="#period-to-<?= $index + 1 ?>"
									   placeholder="Pick from date" aria-label="Date from" value="<?= $date['from'] ?>" required>
							</td>
							<td>
								<input type="text" class="form-control datepicker" name="dates[<?= $index ?>][to]" id="period-to-<?= $index + 1 ?>" data-min-date="<?= $date['from'] ?>"
									   placeholder="Pick to date" aria-label="Date to" value="<?= $date['to'] ?>" required>
							</td>
							<td class="text-center">
								<button class="btn btn-sm btn-outline-danger btn-delete-period" type="button">
									<i class="mdi mdi-trash-can-outline"></i>
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<div class="d-flex align-items-center justify-content-between">
				<div class="dropdown row">
					<button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
						Template
					</button>
					<div class="dropdown-menu">
						<button type="button" class="dropdown-item" id="template-1-month">
							<i class="mdi mdi-calendar-outline mr-2"></i> 1 Month Group
						</button>
						<button type="button" class="dropdown-item" id="template-3-month">
							<i class="mdi mdi-calendar-range-outline mr-2"></i> 3 Month Group
						</button>
						<button type="button" class="dropdown-item" id="template-4-month">
							<i class="mdi mdi-calendar-range-outline mr-2"></i> 4 Month Group
						</button>
						<button type="button" class="dropdown-item" id="template-6-month">
							<i class="mdi mdi-calendar-check-outline mr-2"></i> 6 Month Group
						</button>
						<button type="button" class="dropdown-item" id="week">
							<i class="mdi mdi-calendar-range-outline mr-2"></i> Per Week
						</button>
					</div>
					<!-- <button type="button" class="btn btn-outline-info btn-sm" id="week">Week</button> -->
					<select class="col" name="year" id="year">
						<?php for ($y = date('Y'); $y >= 2018; $y--): ?>
							<option value="<?= $y ?>" <?= get_url_param('year') == $y ? 'selected' : '' ?>>
								<?= $y ?>
							</option>
						<?php endfor; ?>
					</select>
				</div>
				<div>
					<a href="<?= site_url(uri_string()) ?>" class="btn btn-sm btn-secondary">
						RESET
					</a>
					<button type="submit" class="btn btn-primary btn-sm">Apply Filter</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Category Period</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=category-period" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-striped table-md mt-3 responsive" id="table-category-period-report">
            <thead>
            <tr class="border-top">
                <th rowspan="2" class="align-middle">No</th>
                <th rowspan="2" class="align-middle">Date Range</th>
                <th colspan="2" class="text-center">Biggest</th>
                <th colspan="2" class="text-center">Times</th>
            </tr>
			<tr>
				<th>Category</th>
				<th>Amount</th>
				<th>Category</th>
				<th>Total</th>
			</tr>
            </thead>
            <tbody>
			<?php foreach ($periodReports as $index => $periodReport): ?>
				<tr>
					<td><?= $index + 1 ?></td>
					<td><?= $periodReport['date_from'] ?> - <?= $periodReport['date_to'] ?></td>
					<td><?= if_empty($periodReport['category_biggest'], '-') ?></td>
					<td>
						<?php if(empty($periodReport['order'])): ?>
							Rp. 0
						<?php else: ?>
							<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $periodReport['date_from'] . '&date_to=' . $periodReport['date_to'] . '&category=' . $periodReport['id_category_biggest']) ?>">
								Rp. <?= numerical($periodReport['amount']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td><?= if_empty($periodReport['category_times'], '-') ?></td>
					<td>
						<?php if(empty($periodReport['order'])): ?>
							0x
						<?php else: ?>
							<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $periodReport['date_from'] . '&date_to=' . $periodReport['date_to'] . '&category=' . $periodReport['id_category_times']) ?>">
								<?= numerical($periodReport['order']) ?>x
							</a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
            <?php if(empty($periodReports)): ?>
                <tr>
                    <td colspan="6">Set filter period of date range</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Category Purchase Period</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=purchase-period" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
		<div class="table-responsive">
			<table class="table table-hover table-md mt-3 position-relative responsive text-nowrap" id="table-category-purchase-report">
				<thead>
				<tr class="border-top">
					<th class="text-md-center d-inline-block" style="width: 80px">
						<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm p-0 mr-2 position-absolute btn-outline-primary d-none d-md-inline-block btn-toggle-expand-all state-collapse"
								style="left: 3px" data-target="toggle-category-purchase">
							<i class="mdi mdi-plus" style="margin: 0"></i>
						</button>
						No
					</th>
					<th>Category</th>
					<?php foreach (get_url_param('dates', []) as $date): ?>
						<th><?= $date['from'] ?> - <?= $date['to'] ?></th>
					<?php endforeach; ?>
				</tr>
				</thead>
				<tbody>
				<?php $no = 1 ?>
				<?php foreach ($categoryPurchasePeriods as $categoryId => $categoryPurchasePeriod): ?>
					<tr>
						<td class="text-md-center">
							<?php if (!empty($categoryPurchasePeriod['item_categories'])): ?>
								<button type="button" class="btn btn-sm p-0 mr-2 position-absolute btn-outline-primary d-none d-md-inline-block toggle-category-purchase btn-toggle-expand state-collapse"
										style="left: 3px" data-target="category-purchase-<?= $categoryId ?>" data-id="<?= $categoryId ?>">
									<i class="mdi mdi-plus" style="margin: 0"></i>
								</button>
							<?php endif; ?>
							<?= $no++ ?>
						</td>
						<td><?= $categoryPurchasePeriod['category'] ?></td>
						<?php foreach (get_url_param('dates') as $date): ?>
							<td>
								<?php if(empty($categoryPurchasePeriod[$date['from'] . ' - ' . $date['to']])): ?>
									Rp. 0
								<?php else: ?>
									<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to'] . '&category=' . $categoryId) ?>">
										Rp. <?= numerical($categoryPurchasePeriod[$date['from'] . ' - ' . $date['to']]) ?>
									</a>
								<?php endif; ?>
								<?php foreach ($periodReports as $periodReport): ?>
									<?php if($date['from'] == $periodReport['date_from'] && $date['to'] == $periodReport['date_to'] && $categoryId == $periodReport['id_category_biggest']): ?>
										<span class="badge badge-success text-small ml-1 p-1">MAX</span>
									<?php endif; ?>
								<?php endforeach; ?>
							</td>
						<?php endforeach; ?>
					</tr>
					<?php $innerNo = 1 ?>
					<?php foreach ($categoryPurchasePeriod['item_categories'] as $itemCategoryId => $itemCategory): ?>
						<tr class="table-secondary" data-parent="category-purchase-<?= $categoryId ?>" style="display: none">
							<td class="d-none d-md-table-cell"><span class="d-md-none"><?= $innerNo++ ?></span></td>
							<td><span class="pl-2"><?= $itemCategory['item_category'] ?></span></td>
							<?php foreach (get_url_param('dates', []) as $date): ?>
								<td>
									<?php if(empty($itemCategory[$date['from'] . ' - ' . $date['to']])): ?>
										Rp. 0
									<?php else: ?>
										<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to'] . '&category=' . $categoryId . '&item_category=' . $itemCategoryId) ?>">
											Rp. <?= numerical($itemCategory[$date['from'] . ' - ' . $date['to']]) ?>
										</a>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach ?>
				<?php endforeach; ?>
				<?php if(empty($categoryPurchasePeriods)): ?>
					<tr>
						<td colspan="<?= 2 + count(get_url_param('dates', [])) ?>">Set filter period of date range</td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
				<tr class="table-success">
					<td></td>
					<td>TOTAL</td>
					<?php foreach (get_url_param('dates', []) as $date): ?>
						<td>
							<?php $total = array_sum(array_column($categoryPurchasePeriods, $date['from'] . ' - ' . $date['to'])) ?>
							<?php if(empty($total)): ?>
								Rp. 0
							<?php else: ?>
								<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to']) ?>">
									Rp. <?= numerical($total) ?>
								</a>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				</tfoot>
			</table>
		</div>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Category Order Period</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=order-period" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
		<div class="table-responsive">
			<table class="table table-hover table-md mt-3 position-relative responsive text-nowrap" id="table-category-purchase-report">
				<thead>
				<tr class="border-top">
					<th class="text-md-center d-inline-block" style="width: 80px">
						<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm p-0 mr-2 position-absolute btn-outline-primary d-none d-md-inline-block btn-toggle-expand-all state-collapse"
								style="left: 3px" data-target="toggle-category-order">
							<i class="mdi mdi-plus" style="margin: 0"></i>
						</button>
						No
					</th>
					<th>Category</th>
					<?php foreach (get_url_param('dates', []) as $date): ?>
						<th><?= $date['from'] ?> - <?= $date['to'] ?></th>
					<?php endforeach; ?>
				</tr>
				</thead>
				<tbody>
				<?php $no = 1 ?>
				<?php foreach ($categoryOrderPeriods as $categoryId => $categoryOrderPeriod): ?>
					<tr>
						<td class="text-md-center">
							<?php if (!empty($categoryOrderPeriod['item_categories'])): ?>
								<button type="button" class="btn btn-sm p-0 mr-2 position-absolute btn-outline-primary d-none d-md-inline-block toggle-category-order btn-toggle-expand state-collapse"
										style="left: 3px" data-target="category-order-<?= $categoryId ?>" data-id="category-order-<?= $categoryId ?>">
									<i class="mdi mdi-plus" style="margin: 0"></i>
								</button>
							<?php endif; ?>
							<?= $no++ ?>
						</td>
						<td><?= $categoryOrderPeriod['category'] ?></td>
						<?php foreach (get_url_param('dates') as $date): ?>
							<td>
								<?php if(empty($categoryOrderPeriod[$date['from'] . ' - ' . $date['to']])): ?>
									0x
								<?php else: ?>
									<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to'] . '&category=' . $categoryId) ?>">
										<?= numerical($categoryOrderPeriod[$date['from'] . ' - ' . $date['to']]) ?>x
									</a>
								<?php endif; ?>
								<?php foreach ($periodReports as $periodReport): ?>
									<?php if($date['from'] == $periodReport['date_from'] && $date['to'] == $periodReport['date_to'] && $categoryId == $periodReport['id_category_times']): ?>
										<span class="badge badge-success text-small ml-1 p-1">MAX</span>
									<?php endif; ?>
								<?php endforeach; ?>
							</td>
						<?php endforeach; ?>
					</tr>
					<?php $innerNo = 1 ?>
					<?php foreach ($categoryOrderPeriod['item_categories'] as $itemCategoryId => $itemCategory): ?>
						<tr class="table-secondary" data-parent="category-order-<?= $categoryId ?>" style="display: none">
							<td class="d-none d-md-table-cell"><span class="d-md-none"><?= $innerNo++ ?></span></td>
							<td><span class="pl-2"><?= $itemCategory['item_category'] ?></span></td>
							<?php foreach (get_url_param('dates', []) as $date): ?>
								<td>
									<?php if(empty($itemCategory[$date['from'] . ' - ' . $date['to']])): ?>
										0x
									<?php else: ?>
										<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to'] . '&category=' . $categoryId . '&item_category=' . $itemCategoryId) ?>">
											<?= numerical($itemCategory[$date['from'] . ' - ' . $date['to']]) ?>x
										</a>
									<?php endif; ?>
								</td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach ?>
				<?php endforeach; ?>
				<?php if(empty($categoryOrderPeriods)): ?>
					<tr>
						<td colspan="<?= 2 + count(get_url_param('dates', [])) ?>">Set filter period of date range</td>
					</tr>
				<?php endif; ?>
				</tbody>
				<tfoot>
				<tr class="table-success">
					<td></td>
					<td>TOTAL</td>
					<?php foreach (get_url_param('dates', []) as $date): ?>
						<td>
							<?php $total = array_sum(array_column($categoryOrderPeriods, $date['from'] . ' - ' . $date['to'])) ?>
							<?php if(empty($total)): ?>
								0x
							<?php else: ?>
								<a href="<?= site_url('report/satisfied-detail?date_type=purchase_offers.updated_at&date_from=' . $date['from'] . '&date_to=' . $date['to']) ?>">
									<?= numerical($total) ?>x
								</a>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
				</tfoot>
			</table>
		</div>
    </div>
</div>
