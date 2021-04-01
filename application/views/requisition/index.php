<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0"><?= $title ?></h4>
            <div>
                <a href="#modal-requisition-status" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-information-outline"></i>
                </a>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_CREATE)): ?>
                    <a href="<?= site_url('requisition/create') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive" id="table-requisition">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Requester</th>
                <th>No Request</th>
                <th>Request</th>
                <th class="text-nowrap">Created At</th>
                <th>Deadline</th>
                <th class="text-nowrap">Ref Remark</th>
                <th>Link</th>
                <th>Check</th>
                <th>Type / Status</th>
                <th style="width: 100px" class="text-sm-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $statusLabels = [
                RequisitionModel::STATUS_PENDING => 'secondary',
                RequisitionModel::STATUS_REJECTED => 'danger',
                RequisitionModel::STATUS_CANCELLED => 'danger',
                RequisitionModel::STATUS_APPROVED => 'secondary',
                RequisitionModel::STATUS_LISTED => 'dark',
                RequisitionModel::STATUS_ASK_SELECTION => 'info',
                RequisitionModel::STATUS_READY => 'light',
                RequisitionModel::STATUS_SELECTED => 'primary',
                RequisitionModel::STATUS_IN_PROCESS => 'warning',
                RequisitionModel::STATUS_DONE => 'success',
            ];
			$statusOfferLabels = [
				PurchaseOfferModel::STATUS_ON_REVIEW => 'warning',
				PurchaseOfferModel::STATUS_UNSELECTED => 'danger',
				PurchaseOfferModel::STATUS_SELECTED => 'primary',
				PurchaseOfferModel::STATUS_ORDERED => 'info',
				PurchaseOfferModel::STATUS_ASK_CONFIRMATION => 'warning',
				PurchaseOfferModel::STATUS_CONFIRMED => 'primary',
				PurchaseOfferModel::STATUS_UNCONFIRMED => 'danger',
				PurchaseOfferModel::STATUS_COMPLETED => 'success',
			];
			?>
            <?php $no = isset($requisitions) ? ($requisitions['current_page'] - 1) * $requisitions['per_page'] : 0 ?>
            <?php foreach ($requisitions['data'] as $requisition): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td><?= $requisition['employee_name'] ?></td>
                    <td><?= $requisition['no_requisition'] ?></td>
                    <td>
                        <span class="d-block">
                            <?= $requisition['request_title'] ?>
                        </span>
                        <span class="small text-<?= empty($requisition['category']) ? 'danger' : 'muted' ?>">
                            <?= if_empty($requisition['category'], 'Category is not set') ?>
                        </span>
                    </td>
                    <td data-toggle="tooltip" data-title="<?= format_date($requisition['created_at'], 'd F Y') ?>">
						<?= format_date($requisition['created_at'], 'd/m/y') ?>
					</td>
                    <td data-toggle="tooltip" data-title="<?= format_date($requisition['deadline'], 'd F Y') ?>">
						<?= format_date($requisition['deadline'], 'd/m/y') ?>
					</td>
                    <td><?= str_replace([','], ', ', if_empty($requisition['no_reference_remark'], '-')) ?></td>
                    <td>
                    <?php if($requisition['item_name']=='FORKLIFT'): ?>
                        <?php if(!empty($requisition['id_heep'])): ?>
                            <a href="<?= env('WAREHOUSE_URL').'/p/1/report/heavy-equipment?filter_heavy_equipment=1&type=EXTERNAL&heavy_equipment='.$requisition['id_heep'].'&date_from='.$requisition['heep_in'].'&date_to='.$requisition['heep_out'] ?>" class="btn btn-sm btn-success" target="_blank">
                                REPORT
                            </a>
                        <?php else:?>
							NOT USED
                        <?php endif;?>
                    <?php else:?>
                        -
                    <?php endif;?>
                    </td>
                    <td class="text-md-center">
                        <?php if($requisition['is_checked']==0): ?>
                            <span class="mdi mdi-close" style="font-size: 16px"></span>
                        <?php else: ?>
                            <span class="mdi mdi-check" style="font-size: 16px"></span>
                        <?php endif;?>
                    </td>
                    <td class="text-nowrap">
						<span class="badge badge-<?= empty($requisition['approved_type']) ? 'secondary' : ($requisition['approved_type'] == 'INTERNAL' ? 'success' : 'danger') ?>"
							  data-toggle="tooltip" data-title="<?= if_empty($requisition['approved_type'], 'Type not set yet') ?>">
							<?= if_empty(substr($requisition['approved_type'],0,2),"-") ?>
						</span>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
                            <?= $requisition['status'] ?>
                        </span>
						<?php if(!empty($requisition['selected_offer_status'])): ?>
							<br>
							<span class="mt-1 badge badge-<?= get_if_exist($statusOfferLabels, $requisition['selected_offer_status'], 'secondary') ?>" data-toggle="tooltip" data-title="Purchase offer status: <?= $requisition['selected_offer_status'] ?>">
								PO: <?= $requisition['selected_offer_status'] ?>
							</span>
						<?php endif; ?>
                    </td>
                    <td class="text-md-right">
                        <div class="dropdown row-requisition"
                             data-no-requisition="<?= $requisition['no_requisition'] ?>"
                             data-request-title="<?= $requisition['request_title'] ?>"
                             data-email="<?= $requisition['employee_email'] ?>"
                             data-status="<?= $requisition['status'] ?>">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VIEW)): ?>
                                    <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                        <i class="mdi mdi-eye-outline mr-2"></i> View
                                    </a>
                                    <?php if(!AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_ADMIN_VIEW) || AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
                                    <a class="dropdown-item" href="<?= site_url('requisition/print-requisition/' . $requisition['id']) ?>">
                                        <i class="mdi mdi-cloud-print-outline mr-2"></i> Print
                                    </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(!in_array($requisition['status'], ['PENDING', 'REJECTED', 'CANCELLED', 'APPROVED', 'IN PROCESS', 'DONE'])): ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_REVERT)): ?>
                                        <a class="dropdown-item btn-revert" href="<?= site_url('requisition/revert/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-reload mr-2"></i> Revert
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
                                    <?php if(in_array($requisition['status'], ['APPROVED'])): ?>
                                        <a class="dropdown-item" href="<?= site_url('requisition/set-category/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-square-edit-outline mr-2"></i> Set Category
                                        </a>
                                    <?php endif; ?>
                                    <?php if(in_array($requisition['status'], ['APPROVED', 'LISTED'])): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= site_url('requisition/notify-to-vendor/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-email-check-outline mr-2"></i> Notify Vendor
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(in_array($requisition['status'], ['PENDING', 'REJECTED']) || (in_array($requisition['status'], [RequisitionModel::STATUS_APPROVED]) && AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE) && !empty($requisition['category']))): ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_EDIT)): ?>
                                        <a class="dropdown-item" href="<?= site_url('requisition/edit/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-square-edit-outline mr-2"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_SET_CHECK) && $requisition['is_checked'] == 0): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item btn-validate" href="<?= site_url('requisition/set_checked/' . $requisition['id']) ?>" data-no-requisition="<?= $requisition['no_requisition'] ?>" data-validate="set-checked">
                                        <i class="mdi mdi-check mr-2"></i> Set Checked
                                    </a>
                                <?php endif; ?>
                                
                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VALIDATE) || $requisition['id_supervisor'] == $employeeId): ?>
                                    <?php if(in_array($requisition['status'], [RequisitionModel::STATUS_PENDING])): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-check mr-2"></i> Approve
                                        </a>
                                        <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-close mr-2"></i> Reject
                                        </a>
                                    <?php endif; ?>
                                    <?php if(in_array($requisition['status'], [RequisitionModel::STATUS_APPROVED]) && AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-close mr-2"></i> Reject
                                        </a>
                                        <a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-close mr-2"></i> Cancel
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_DELETE)): ?>
                                    <?php if(in_array($requisition['status'], [RequisitionModel::STATUS_PENDING, RequisitionModel::STATUS_REJECTED, RequisitionModel::STATUS_CANCELLED])): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                           data-id="<?= $requisition['id'] ?>" data-label="<?= $requisition['request_title'] ?>" data-title="Requisition"
                                           data-url="<?= site_url('requisition/delete/' . $requisition['id']) ?>">
                                            <i class="mdi mdi-trash-can-outline mr-2"></i> Delete
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($requisitions['data'])): ?>
                <tr>
                    <td colspan="7">No requisitions data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $requisitions]) ?>
    </div>
</div>

<?php $this->load->view('requisition/_modal_filter') ?>
<?php $this->load->view('requisition/_modal_info') ?>
<?php $this->load->view('partials/modals/_validate') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_REVERT)): ?>
    <?php $this->load->view('requisition/_modal_revert') ?>
<?php endif; ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
    <?php $this->load->view('requisition/_modal_set_category') ?>
<?php endif; ?>
