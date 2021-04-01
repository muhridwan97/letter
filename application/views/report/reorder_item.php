<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Reorder Item <?= get_url_param('year', date('Y')) ?></h4>
            <div>
				<a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
					<i class="mdi mdi-filter-variant mr-0"></i>
				</a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-md mt-3 responsive">
            <thead>
			<tr>
				<th rowspan="2" class="border-top">Week</th>
				<th rowspan="2" class="border-top">Date Range</th>
				<th rowspan="2" class="border-top">Reorder (x)</th>
				<td colspan="3" class="font-weight-bold border-bottom text-md-center">Cheapest Before</td>
				<td colspan="3" class="font-weight-bold border-bottom text-md-center">Previous Latest</td>
			</tr>
            <tr>
				<th>&lt; Price</th>
				<th>&equals; Price</th>
				<th>&gt; Price</th>
				<th>&lt; Price</th>
				<th>&equals; Price</th>
				<th>&gt; Price</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $report): ?>
                <tr>
                    <td><?= $report['week'] ?></td>
					<td class="text-nowrap">
						<?php $weekDate = get_week_date_range_sql_mode_2($report['week'], $report['year']); ?>
						(<?= $weekDate['week_start'] ?> &nbsp; - &nbsp; <?= ($weekDate['week_end']) ?>)
					</td>
					<td>
						<?php if (empty($report['reorder_item'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=all&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['reorder_item']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['cheaper_than_before'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=cheaper-than-before&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['cheaper_than_before']) ?>
							</a>
						<?php endif; ?>
					</td>
                    <td>
						<?php if (empty($report['same_as_before'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=same-as-before&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['same_as_before']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['more_expensive_than_before'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=expensive-than-before&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['more_expensive_than_before']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['cheaper_than_latest'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=cheaper-than-latest&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['cheaper_than_latest']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['same_as_latest'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=same-as-latest&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['same_as_latest']) ?>
							</a>
						<?php endif; ?>
					</td>
                    <td>
						<?php if (empty($report['more_expensive_than_latest'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/reorder-item-detail?type=expensive-than-latest&year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['more_expensive_than_latest']) ?>
							</a>
						<?php endif; ?>
					</td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($reports)): ?>
                <tr>
                    <td colspan="8">No reorder data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('report/_modal_filter_reorder_item') ?>
