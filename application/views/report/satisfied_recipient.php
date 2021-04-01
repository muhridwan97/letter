<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Satisfied Recipient <?= get_url_param('year', date('Y')) ?></h4>
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
                <th>Week</th>
                <th>Date Range</th>
				<th data-toggle="tooltip" data-title="Approved - Last Done">Incomplete Request</th>
				<th data-toggle="tooltip" data-title="Approved - In Process (PO Created) + Confirmed - Last Done">Staff In Process</th>
				<th data-toggle="tooltip" data-title="In Process (PO Created) - Ask Confirmation / First Done">Vendor In Process</th>
				<th data-toggle="tooltip" data-title="Ask Confirmation - Confirmed">Requester In Process</th>
                <th>Approved Request</th>
                <th>User Rate</th>
                <th>Completed PO</th>
                <th class="text-md-right">Total Price</th>
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
						<?php if (empty($report['uncompleted_requisition'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('requisition?incomplete_request_at_year=' . $report['year'] . '&incomplete_request_at_week=' . $report['week'] . '&incomplete_request_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['uncompleted_requisition']) ?>
							</a>
						<?php endif; ?>
					</td>
                    <td>
						<?php if (empty($report['staff_proceed_requisition'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('requisition?staff_proceed_at_year=' . $report['year'] . '&staff_proceed_at_week=' . $report['week'] . '&staff_proceed_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['staff_proceed_requisition']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['vendor_proceed_requisition'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('requisition?vendor_proceed_at_year=' . $report['year'] . '&vendor_proceed_at_week=' . $report['week'] . '&vendor_proceed_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['vendor_proceed_requisition']) ?>
							</a>
						<?php endif; ?>
					</td>
					<td>
						<?php if (empty($report['requester_proceed_requisition'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('requisition?requester_proceed_at_year=' . $report['year'] . '&requester_proceed_at_week=' . $report['week'] . '&requester_proceed_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['requester_proceed_requisition']) ?>
							</a>
						<?php endif; ?>
					</td>
                    <td>
						<?php if (empty($report['approved_requisition'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('requisition?approved_at_year=' . $report['year'] . '&approved_at_week=' . $report['week'] . '&approved_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= numerical($report['approved_requisition']) ?>
							</a>
						<?php endif; ?>
					</td>
                    <td class="text-nowrap"><i class="mdi mdi-star-outline mr-1"></i><?= numerical($report['average_user_rating']) ?></td>
                    <td>
						<?php if (empty($report['completed_purchase_order'])): ?>
							0
						<?php else: ?>
							<a href="<?= site_url('report/satisfied-detail?status=COMPLETED&completed_at_year=' . $report['year'] . '&completed_at_week=' . $report['week'] . '&completed_at_year_week=' . $report['year_week']) ?>" target="_blank">
								<?= $report['completed_purchase_order'] ?>
							</a>
						<?php endif; ?>
					</td>
					<td class="text-md-right text-nowrap">Rp. <?= numerical($report['total_price']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($reports)): ?>
                <tr>
                    <td colspan="10">No satisfied purchase data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('report/_modal_filter_satisfied') ?>

<div class="card">
	<div class="card-body">
		<h4 class="card-title">Satisfaction Chart <?= get_url_param('year', date('Y')) ?></h4>
		<div>
			<canvas id="satisfaction-chart" height="100"></canvas>
		</div>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script defer>
	const datasetOption = {
		label: "Label",
		fill: false,
		lineTension: 0.1,
		backgroundColor: "rgba(0,0,0,0.2)",
		borderColor: "rgba(0,0,0,1)",
		borderCapStyle: 'butt',
		borderDash: [],
		borderDashOffset: 0.0,
		borderJoinStyle: 'miter',
		pointBorderColor: "rgba(0,0,0,1)",
		pointBackgroundColor: "#fff",
		pointBorderWidth: 1,
		pointHoverRadius: 6,
		pointHoverBackgroundColor: "rgba(0,0,0,1)",
		pointHoverBorderColor: "rgba(220,220,220,1)",
		pointHoverBorderWidth: 2,
		pointRadius: 2,
		pointHitRadius: 50,
		data: [],
		spanGaps: false,
	};
	const data = {
		labels: <?= json_encode(array_column(array_reverse($reports), 'week')) ?>,
		datasets: [
			{
				...datasetOption,
				label: "Approved Requisition",
				backgroundColor: "rgba(255,112,112,0.2)",
				borderColor: "rgb(255,112,112)",
				pointBorderColor: "rgba(255,112,112,1)",
				pointHoverBackgroundColor: "rgba(255,112,112,1)",
				data: <?= json_encode(array_column(array_reverse($reports), 'approved_requisition')) ?>,
				hidden: true,
			}, {
				...datasetOption,
				label: "Purchase Order",
				backgroundColor: "rgba(75,192,192,0.4)",
				borderColor: "rgba(75,192,192,1)",
				pointBorderColor: "rgba(75,192,192,1)",
				pointHoverBackgroundColor: "rgba(75,192,192,1)",
				data: <?= json_encode(array_column(array_reverse($reports), 'completed_purchase_order')) ?>,
				hidden: true,
			}, {
				...datasetOption,
				label: "User Rating",
				backgroundColor: "rgba(179,181,198,0.2)",
				borderColor: "rgba(179,181,198,1)",
				pointBorderColor: "rgba(179,181,198,1)",
				pointHoverBackgroundColor: "rgba(179,181,198,1)",
				data: <?= json_encode(array_column(array_reverse($reports), 'average_user_rating')) ?>,
				hidden: false,
			}, {
				...datasetOption,
				label: "Uncompleted Request",
				backgroundColor: "rgba(179,181,98,0.2)",
				borderColor: "rgba(179,181,98,1)",
				pointBorderColor: "rgba(179,181,98,1)",
				pointHoverBackgroundColor: "rgba(179,181,98,1)",
				data: <?= json_encode(array_column(array_reverse($reports), 'uncompleted_requisition')) ?>,
				hidden: true,
			}, {
				...datasetOption,
				label: 'Staff Proceed',
				backgroundColor: 'rgba(125,48,193,0.2)',
				borderColor: 'rgba(125,48,193,1)',
				pointBorderColor: 'rgba(125,48,193,1)',
				pointHoverBackgroundColor: 'rgba(125,48,193,1)',
				data: <?= json_encode(array_column(array_reverse($reports), 'staff_proceed_requisition')) ?>,
				hidden: true,
			}, {
				...datasetOption,
				label: 'Vendor Proceed',
				backgroundColor: 'rgba(183,35,158,0.2)',
				borderColor: 'rgba(183,35,158,1)',
				pointBorderColor: 'rgba(183,35,158,1)',
				pointHoverBackgroundColor: 'rgba(183,35,158,1)',
				data: <?= json_encode(array_column(array_reverse($reports), 'vendor_proceed_requisition')) ?>,
				hidden: true,
			}, {
				...datasetOption,
				label: 'Requester Handover',
				backgroundColor: 'rgba(23,143,25,0.2)',
				borderColor: 'rgb(23,143,25)',
				pointBorderColor: 'rgb(23,143,25)',
				pointHoverBackgroundColor: 'rgb(23,143,25)',
				data: <?= json_encode(array_column(array_reverse($reports), 'requester_proceed_requisition')) ?>,
				hidden: true,
			}
		]
	};
	const ctx = document.getElementById("satisfaction-chart");
	const chart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: {
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			scales: {
				yAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Total'
					},
					ticks: {
						min: 0
					}
				}],
				xAxes: [{
					scaleLabel: {
						display: true,
						labelString: 'Week'
					}
				}]
			}
		}
	});
</script>
