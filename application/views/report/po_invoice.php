<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">PO - Invoice</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <div class="scroll-wrapper">
            <table class="table table-hover mt-3 responsive text-nowrap">
                <thead>
                <tr>
                    <th class="text-md-center" style="width: 60px">No</th>
                    <th>Requisition No.</th>
                    <th>Requisition Date</th>
                    <th>Requisition Deadline Date</th>
                    <th>Vendor</th>
                    <th>PO No.</th>
                    <th>PO Date</th>
                    <th>Vendor Invoice No.</th>
                    <th>Vendor Invoice Date</th>
                    <th>Finance Receipt No.</th>
                    <th>Finance Receipt Date</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = ($reports['current_page'] - 1) * $reports['per_page'] ?>
                <?php foreach ($reports['data'] as $report): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td>
                            <a href="<?= site_url('purchasing/requisition/view/' . $report['requisition_id']) ?>" target="_blank">
                                <?= if_empty($report['no_requisition'],'-') ?>
                            </a>
                        </td>
                        <td><?= format_date($report['request_date'], 'd F Y') ?></td>
                        <td><?= format_date($report['requisition_deadline'], 'd F Y') ?></td>   
                        <td><?= if_empty($report['vendor'],'-') ?></td>
                        <td>
                            <a href="<?= site_url('purchasing/order/view/' . $report['purchase_order_id']) ?>" target="_blank">
								<?= if_empty($report['no_purchase'],'-') ?>
							</a>
                        </td>
                        <td><?= if_empty(format_date($report['purchase_date'], 'd F Y'), '-') ?></td>
                        <td><?= if_empty($report['vendor_invoice'], '-') ?></td>
                        <td><?= if_empty(format_date($report['vendor_invoice_date'], 'd F Y'), '-') ?></td>
                        <td>
                            <a href="<?= site_url('purchasing/payment-handover/view/' . $report['handover_id']) ?>" target="_blank">
								<?= if_empty($report['no_handover'],'-') ?>
							</a>
                        </td>
                        <td><?= if_empty(format_date($report['handover_date'], 'd F Y'), '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($reports['data'])): ?>
                    <tr>
                        <td colspan="11">No data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $reports]) ?>
    </div>
</div>

<?php $this->load->view('report/_modal_filter_po') ?>
