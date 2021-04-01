<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Control Data</h4>
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
                    <th>No Requisition</th>
                    <th>Request Title</th>
                    <th>Request Date</th>
                    <th>Requisition Status</th>
                    <th>Last Approved At</th>
                    <th>Last Notify to Vendor</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Supervisor Name</th>
                    <th>Category</th>
                    <th>Item Category</th>
                    <th>Vendor</th>
                    <th>Contact Person</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Offer Date</th>
                    <th>Offer Status</th>
                    <th>Lead Time</th>
                    <th>Last Submit to Manager</th>
                    <th>Last Vendor Selected At</th>
                    <th>No Purchase</th>
                    <th>Purchase Date</th>
                    <th>Tax</th>
                    <th>Tax Percentage</th>
                    <th>Misc</th>
                    <th>Delivery Cost</th>
                    <th>Total Item Type</th>
                    <th>Total Item Qty</th>
                    <th>Total Item Price</th>
                    <th>Total Price</th>
                    <th>Rating</th>
                    <th>Completed At</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = ($reports['current_page'] - 1) * $reports['per_page'] ?>
                <?php foreach ($reports['data'] as $report): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td><?= $report['no_requisition'] ?></td>
                        <td><?= $report['request_title'] ?></td>
                        <td><?= format_date($report['request_date'], 'd F Y') ?></td>
                        <td><?= $report['requisition_status'] ?></td>
                        <td><?= if_empty(format_date($report['last_approved_at'], 'd F Y H:i'), '-') ?></td>
                        <td><?= if_empty(format_date($report['last_notify_vendor_at'], 'd F Y H:i'), '-') ?></td>
                        <td><?= $report['employee_name'] ?></td>
                        <td><?= $report['department'] ?></td>
                        <td><?= if_empty($report['supervisor_name'], '-') ?></td>
                        <td><?= if_empty($report['category'], '-') ?></td>
                        <td><?= if_empty($report['item_category'], '-') ?></td>
                        <td><?= if_empty($report['vendor'], '-') ?></td>
                        <td><?= if_empty($report['contact_person'], '-') ?></td>
                        <td><?= if_empty($report['contact'], '-') ?></td>
                        <td><?= if_empty($report['email'], '-') ?></td>
                        <td><?= if_empty(format_date($report['offer_date'], 'd F Y'), '-') ?></td>
                        <td><?= if_empty($report['offer_status'], '-') ?></td>
                        <td><?= if_empty($report['lead_time'], '-') ?></td>
                        <td><?= if_empty(format_date($report['last_submit_to_manager_at'], 'd F Y H:i'), '-') ?></td>
                        <td><?= if_empty(format_date($report['last_vendor_selected_at'], 'd F Y H:i'), '-') ?></td>
                        <td><?= if_empty($report['no_purchase'], '-') ?></td>
                        <td><?= if_empty(format_date($report['purchase_date'], 'd F Y'), '-') ?></td>
                        <td>Rp. <?= numerical($report['tax']) ?></td>
                        <td><?= numerical($report['tax_percentage']) ?>%</td>
                        <td>Rp. <?= numerical($report['miscellaneous_expense']) ?></td>
                        <td>Rp. <?= numerical($report['delivery_cost']) ?></td>
                        <td><?= numerical($report['total_item_type']) ?></td>
                        <td><?= numerical($report['total_item_quantity']) ?></td>
                        <td>Rp. <?= numerical($report['total']) ?></td>
                        <td>Rp. <?= numerical($report['total_price']) ?></td>
                        <td><?= if_empty($report['rating'], '-') ?></td>
                        <td><?= if_empty(format_date($report['completed_at'], 'd F Y H:i'), '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($reports['data'])): ?>
                    <tr>
                        <td colspan="32">No data available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $this->load->view('partials/_pagination', ['pagination' => $reports]) ?>
    </div>
</div>

<?php $this->load->view('report/_modal_filter_control') ?>
