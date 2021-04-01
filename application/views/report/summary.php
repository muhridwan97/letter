<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Purchase Summary</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-history">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Year</th>
                <th>Month</th>
                <th>Total Item</th>
                <th>Total Transaction</th>
                <th>Total Item Prices</th>
                <th>Total All Prices</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = ($reports['current_page'] - 1) * $reports['per_page'] ?>
            <?php foreach ($reports['data'] as $report): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $report['year'] ?></td>
                    <td><?= format_date($report['year'] . '-' . $report['month'] . '-1', 'F') ?></td>
                    <td><?= numerical($report['total_item']) ?></td>
                    <td><?= numerical($report['total_transaction']) ?></td>
                    <td>Rp. <?= numerical($report['total']) ?></td>
                    <td>Rp. <?= numerical($report['total_price']) ?></td>
                    <td class="text-md-right">
                        <a class="btn btn-sm btn-primary" href="<?= site_url('report/summary-detail/' . $report['year'] . '/' . $report['month']) ?>">
                            Detail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($reports['data'])): ?>
                <tr>
                    <td colspan="8">No purchase data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $reports]) ?>
    </div>
</div>