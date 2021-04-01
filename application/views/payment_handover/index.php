<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0"><?= $title ?></h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <a href="<?= base_url() ?>purchasing/payment-handover/create" class="btn btn-sm btn-success">
                    <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                </a>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-handover">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 80px">
					<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block btn-toggle-expand-all state-collapse"
							style="left: 30px" data-target="toggle-cost-offer">
						<i class="mdi mdi-plus"></i>
					</button>
					<span class="ml-md-3">No</span>
				</th>
                <th class="text-nowrap">No Handover</th>
                <th class="text-nowrap">Handover Date</th>
                <th>Status</th>
                <th style="width: 120px" class="text-sm-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $this->load->helper('text');
            ?>
            <?php $no = isset($handovers) ? ($handovers['current_page'] - 1) * $handovers['per_page'] : 0 ?>
            <?php foreach ($handovers['data'] as $handover): ?>
                <tr>
                    <td class="text-md-center">
						<?php if (!empty($handover['details'])): ?>
							<button type="button" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block toggle-cost-offer btn-toggle-expand state-collapse"
									style="left: 30px" data-target="<?= $handover['id'] ?>" data-id="<?= $handover['id'] ?>">
								<i class="mdi mdi-plus"></i>
							</button>
						<?php endif; ?>
						<span class="ml-md-3"><?= ++$no ?></span>
					</td>
                    <td><?= if_empty($handover['no_handover'],'-') ?></td>
                    <td><?= format_date($handover['handover_date'], 'd/m/Y') ?></td>
                    <td>
                        <?php if(empty($handover['document'])): ?>
                        <span class="badge badge-primary">
                            PRINTED
                        </span>
                        <?php else: ?>
                        <span class="badge badge-success">
                            UPLOADED
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="text-md-right">
                        <div class="dropdown row-handover"
                             data-no-handover="<?= $handover['no_handover'] ?>"
                             data-handover-date="<?= format_date($handover['handover_date'], 'd/m/Y') ?>">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?= site_url('purchasing/payment-handover/view/' . $handover['id']) ?>">
                                    <i class="mdi mdi-eye-outline mr-2"></i> View
                                </a>
                                <?php if(empty($handover['document'])): ?>
                                <a class="dropdown-item" href="<?= site_url('purchasing/payment-handover/edit-po/' . $handover['id']) ?>">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                </a>
                                <?php else: ?>
                                <a class="dropdown-item" href="<?= site_url('purchasing/payment-handover/edit/' . $handover['id']) ?>">
                                    <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                </a>
                                <?php endif; ?>
                                <a class="dropdown-item" href="<?= site_url('purchasing/payment-handover/print-handover/' . $handover['id']) ?>" target="_blank">
                                    <i class="mdi mdi-cloud-print-outline mr-2"></i> Print Handover
                                </a>
                                <?php if(empty($handover['document'])): ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= site_url('purchasing/payment-handover/upload-handover/' . $handover['id']) ?>">
                                    <i class="mdi mdi-cloud-upload-outline mr-2"></i> Upload Handover
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php foreach ($handover['details'] as $indexDetail => $handoverDetail): ?>
					<?php if ($indexDetail == 0): ?>
						<tr class="ml-4 ml-md-0 text-primary row-header row-header-<?= $handover['id'] ?>" data-parent="<?= $handover['id'] ?>" style="display: none; background-color: rgba(0, 0, 0, 0.04);">
							<th class="d-none d-md-table-cell"><span class="d-inline-block d-md-none">No</span></th>
							<th>No Purchase</th>
							<th>Requisition</th>
							<th>Selected Offer</th>
							<th>Requester</th>
						</tr>
					<?php endif; ?>
					<tr class="ml-4 ml-md-0 text-muted" data-header="row-header-<?= $handover['id'] ?>" data-parent="<?= $handover['id'] ?>" style="display: none">
						<td class="pl-md-2"><span class="d-md-none"><?= $indexDetail + 1 ?></span></td>
						<td>
                        <a href="<?= site_url('purchasing/order/view/' . $handoverDetail['id_purchase_order']) ?>" target="_blank">
                            <?= $handoverDetail['no_purchase'] ?>
                        </a></td>
						<td><?= $handoverDetail['request_title'] ?></td>
						<td><?= $handoverDetail['vendor'] ?></td>
						<td><?= if_empty($handoverDetail['employee_name'], '-') ?></td>
					</tr>
				<?php endforeach; ?>
            <?php endforeach; ?>
            <?php if(empty($handovers['data'])): ?>
                <tr>
                    <td colspan="6">No handover data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $handovers]) ?>
    </div>
</div>

<?php $this->load->view('handover/_modal_filter') ?>
<?php $this->load->view('handover/_modal_confirm_order') ?>
<?php $this->load->view('partials/modals/_validate') ?>
