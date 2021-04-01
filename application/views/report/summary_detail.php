<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">
                Purchase Summary <?= format_date($year . '-' . $month . '-1', 'F') ?> <?= $year ?>
            </h4>
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
                <th>No Purchase</th>
                <th>Date</th>
                <th>Vendor</th>
                <th class="text-md-right">Tax</th>
                <th class="text-md-right">Misc</th>
                <th class="text-md-right">Delivery</th>
                <th class="text-md-right">Total Item Price</th>
                <th class="text-md-right">Total Price</th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0 ?>
            <?php $no = ($reports['current_page'] - 1) * $reports['per_page'] ?>
            <?php foreach ($reports['data'] as $report): ?>
                <?php $total += $report['total_price'] ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td>
                        <a href="<?= site_url('purchasing/order/print-order/' . $report['id']) ?>">
                            <?= $report['no_purchase'] ?>
                        </a>
                    </td>
                    <td><?= $report['date'] ?></td>
                    <td><?= $report['vendor'] ?></td>
                    <td class="text-md-right">Rp. <?= numerical($report['tax']) ?></td>
                    <td class="text-md-right">Rp. <?= numerical($report['miscellaneous_expense']) ?></td>
                    <td class="text-md-right">Rp. <?= numerical($report['delivery_cost']) ?></td>
                    <td class="text-md-right">Rp. <?= numerical($report['total']) ?></td>
                    <td class="text-md-right">Rp. <?= numerical($report['total_price']) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th colspan="8">Total</th>
                <th class="text-md-right">Rp. <?= numerical($total) ?></th>
            </tr>
            </tbody>
        </table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $reports]) ?>
    </div>
</div>