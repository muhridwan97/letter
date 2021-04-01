<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Requisition</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $requisition['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="request_title">
                                <?= $requisition['request_title'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="category">Category</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="category">
                                <?= if_empty($requisition['category'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="item-category">Item Category</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="item-category">
                                <?= if_empty($requisition['item_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Status</label>
                        <div class="col-sm-8">
                            <?php
                            $statusLabels = [
                                RequisitionModel::STATUS_PENDING => 'secondary',
                                RequisitionModel::STATUS_REJECTED => 'danger',
                                RequisitionModel::STATUS_CANCELLED => 'danger',
                                RequisitionModel::STATUS_APPROVED => 'success',
                                RequisitionModel::STATUS_LISTED => 'dark',
                                RequisitionModel::STATUS_ASK_SELECTION => 'info',
                                RequisitionModel::STATUS_READY => 'success',
                                RequisitionModel::STATUS_SELECTED => 'primary',
                                RequisitionModel::STATUS_IN_PROCESS => 'warning',
                                RequisitionModel::STATUS_DONE => 'success',
                            ];
                            ?>
                            <p class="form-control-plaintext" id="status">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
                                    <?= $requisition['status'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="deadline">Deadline</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="deadline">
                                <?= format_date($requisition['deadline'], 'd F Y') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="deadline_remark">Remark</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="deadline_remark">
                                <?= if_empty($requisition['deadline_remark'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="urgency">Urgency</label>
                        <div class="col-sm-8">
                            <?php
                            $statusLabels = [
                                'LOW' => 'success',
                                'NORMAL' => 'primary',
                                'HIGH' => 'warning',
                                'VERY HIGH' => 'danger',
                            ]
                            ?>
                            <p class="form-control-plaintext" id="urgency">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['urgency'], 'secondary') ?>">
                                    <?= $requisition['urgency'] ?>
                                </span>
                            </p>
                        </div>
                    </div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label" for="no_reference_remark">No Ref Remark</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext" id="no_reference_remark">
								<?= if_empty($requisition['no_reference_remark'], '-') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label" for="rate_type">Rate Type</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext" id="rate_type">
								<?= if_empty($requisition['rate_type'], '-') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label" for="request_type">Request Type</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext" id="request_type">
								<?= if_empty($requisition['request_type'], '-') ?>
							</p>
						</div>
					</div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="approved_type">Type</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="approved_type">
                                <span class="badge badge-<?= empty($requisition['approved_type']) ? 'secondary' : ($requisition['approved_type'] == 'INTERNAL' ? 'success' : 'danger') ?>">
                                    <?= if_empty($requisition['approved_type'],"NOT SET") ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label" for="link_report">Link Report</label>
						<div class="col-sm-8">
							<?php if($requisition['item_name']=='FORKLIFT'): ?>
								<?php if(!empty($heep)): ?>
									<a href="<?= env('WAREHOUSE_URL').'/p/1/report/heavy-equipment?filter_heavy_equipment=1&type=EXTERNAL&heavy_equipment='.$heep['id'].'&date_from='.$heep['date_from'].'&date_to='.$heep['date_to'] ?>" class="btn btn-sm btn-success">
										CLICK HERE
									</a>
								<?php else:?>
									<p class="form-control-plaintext" id="link_report">
										NOT USED
									</p>
								<?php endif;?>
							<?php else:?>
								<p class="form-control-plaintext">
									Not Available
								</p>
							<?php endif;?>
						</div>
					</div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="attachment">Attachment</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="attachment">
                                <?php if (empty($requisition['document'])) : ?>
                                    -
                                <?php else : ?>
                                    <a href="<?= asset_url($requisition['document']) ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="requester">
                                <?= if_empty($requisition['employee_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="description">Description</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($requisition['description'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($requisition['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($requisition['creator_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($requisition['updated_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="is_checked">Is Checked</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="is_checked">
                                <span class="badge badge-<?= $requisition['is_checked']=='1'?'success':'warning' ?>">
                                    <?= $requisition['is_checked']=='1' ? 'CHECKED' : 'NOT CHECKED' ?>
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="check_note">Check Note</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="check_note">
                                <?= if_empty($requisition['check_note'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="checked_by">Checked By</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="checked_by">
                                <?= if_empty($requisition['checker_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="checked_at">Checked At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="checked_at">
                                <?= if_empty(format_date($requisition['checked_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Request Items</h4>
            <table class="table responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($requisitionItems as $item) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
								<?= if_empty($item['item_name'], '-') ?>
								<?= if_empty($item['specification'], '', '<br><small class="text-muted">', '</small>') ?>
							</td>
                            <td><?= if_empty($item['brand'], '-') ?></td>
                            <td><?= if_empty($item['type'], '-') ?></td>
                            <td><?= if_empty($item['quantity'], '-') ?></td>
                            <td><?= if_empty($item['unit'], '-') ?></td>
                            <td><?= if_empty($item['description'], '-') ?></td>
                            <td><?= if_empty(format_date($item['created_at'], 'd F Y H:i'), '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($requisitionItems)) : ?>
                        <tr>
                            <td colspan="8">No requisition items</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Status History</h4>
            <table class="table responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Data</th>
                        <th>Created At</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($requisitionStatuses as $status) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= if_empty($status['status'], '-') ?></td>
                            <td><?= if_empty($status['description'], '-') ?></td>
                            <td>
                                <?php if (!empty($status['data']) && key_exists('requisition', $status['data'])) : ?>
                                    <a href="<?= site_url('requisition/history/' . $status['id']) ?>">
                                        View
                                    </a>
                                <?php elseif (!empty($status['data']) && key_exists('email', $status['data'])) : ?>
                                    <span>
                                        <?= $status['data']['email'] ?>
                                    </span>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= if_empty(format_date($status['created_at'], 'd F Y H:i'), '-') ?></td>
                            <td><?= if_empty($status['creator_name'], '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($requisitionStatuses)) : ?>
                        <tr>
                            <td colspan="6">No status available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Notified Vendors</h4>
            <table class="table responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Vendor</th>
                        <th>Email</th>
                        <th>Sent At</th>
                        <th>Sent By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($requisitionEmails as $requisitionEmail) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= if_empty($requisitionEmail['vendor'], '-') ?></td>
                            <td><?= if_empty($requisitionEmail['email'], '-') ?></td>
                            <td><?= if_empty(format_date($requisitionEmail['sent_at'], 'd F Y H:i'), '-') ?></td>
                            <td><?= if_empty($requisitionEmail['sender_name'], '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($requisitionEmails)) : ?>
                        <tr>
                            <td colspan="5">No vendor notified</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Purchase Offers</h4>
                <a href="<?= site_url('purchasing/selection/comparison/' . $requisition['id']) ?>" class="btn btn-sm btn-info">
                    <i class="mdi mdi-select-compare mr-2"></i> Comparison
                </a>
            </div>

            <table class="table mt-3 responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Vendor Offer</th>
                        <th class="text-nowrap">Lead Time</th>
                        <th>Status</th>
                        <th class="text-nowrap">Created At</th>
                        <th class="text-md-right text-nowrap">Offer Price</th>
                        <th class="text-md-right text-nowrap">Selected Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statusLabels = [
                        PurchaseOfferModel::STATUS_ON_REVIEW => 'warning',
                        PurchaseOfferModel::STATUS_UNSELECTED => 'danger',
                        PurchaseOfferModel::STATUS_SELECTED => 'primary',
                        PurchaseOfferModel::STATUS_ORDERED => 'info',
                        PurchaseOfferModel::STATUS_COMPLETED => 'success',
                    ]
                    ?>
                    <?php $no = 1 ?>
                    <?php $totalDiscount = 0 ?>
                    <?php $selectedTotalPrice = 0 ?>
                    <?php foreach ($purchaseOffers as $offer) : ?>
                        <?php if (!in_array($offer['status'], [PurchaseOfferModel::STATUS_ON_REVIEW, PurchaseOfferModel::STATUS_UNSELECTED])) : ?>
                            <?php $totalDiscount += $offer['offer_discount']; ?>
                            <?php $selectedTotalPrice += $offer['selected_total_price']; ?>
                        <?php endif; ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <a href="<?= site_url('purchasing/offer/view/' . $offer['id']) ?>">
                                    <?= $offer['vendor'] ?>
                                </a>
                            </td>
                            <td><?= format_date($offer['lead_time'], 'd F Y') ?></td>
                            <td>
                                <span class="badge badge-<?= get_if_exist($statusLabels, $offer['status'], 'secondary') ?>">
                                    <?= $offer['status'] ?>
                                </span>
                            </td>
                            <td><?= format_date($offer['created_at'], 'd F Y H:i') ?></td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($offer['total_price']) ?></td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($offer['selected_total_price']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="6">Total price</th>
                        <th class="text-md-right text-nowrap">Rp. <?= numerical($selectedTotalPrice) ?></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="card-title mb-0">Purchase Items</h4>
            </div>
            <table class="table responsive">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="text-nowrap">Vendor Offer</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th class="text-md-right text-nowrap">Unit Price</th>
                        <th class="text-md-right text-nowrap">Ext Price</th>
                        <th class="text-md-right text-nowrap">Ext Discount</th>
                        <th class="text-md-right text-nowrap">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1 ?>
                    <?php $selectedTotalPrice = 0 ?>
                    <?php foreach ($selectedOfferItems as $item) : ?>
                        <?php $selectedTotalPrice += $item['selected_total'] ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <a href="<?= site_url('purchasing/offer/view/' . $item['id_purchase_offer']) ?>">
                                    <?= $item['vendor'] ?>
                                </a>
                            </td>
                            <td>
                                <p class="mb-0"><?= if_empty($item['item_name'], '-') ?></p>
                                <small class="text-muted"><?= $item['brand'] ?></small>
                            </td>
                            <td>
                                <?= if_empty(numerical($item['quantity_selected']), '0') ?>
                                <?= if_empty($item['unit'], '-') ?>
                            </td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($item['price']) ?></td>
                            <td class="text-md-right text-nowrap">Rp. <?= numerical($item['selected_extended_price']) ?></td>
                            <td class="text-md-right text-nowrap">(Rp. <?= numerical($item['selected_extended_discount']) ?>)</td>
                            <td class="text-md-right text-nowrap"><strong>Rp. <?= numerical($item['selected_total']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <th colspan="7">Sub Total</th>
                        <th class="text-md-right text-nowrap">Rp. <?= numerical($selectedTotalPrice) ?></th>
                    </tr>
                    <tr>
                        <th colspan="7">Total Offer Discount</th>
                        <th class="text-md-right text-nowrap">(Rp. <?= numerical($totalDiscount) ?>)</th>
                    </tr>
                    <tr>
                        <th colspan="7">Total price</th>
                        <th class="text-md-right text-nowrap">Rp. <?= numerical($selectedTotalPrice -  $totalDiscount) ?></th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin" id="form-requisition">
        <div class="card-body d-flex justify-content-between" id = "view-validate">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if (AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VALIDATE) || $requisition['id_supervisor'] == UserModel::loginData('id_employee')) : ?>
                <?php if (in_array($requisition['status'], [RequisitionModel::STATUS_PENDING])) : ?>
                    <a class="btn btn-success btn-set-approve-type" href="<?= site_url('requisition/approve/' . $requisition['id']) ?>" data-no-requisition="<?= $requisition['no_requisition'] ?>">
                        <i class="mdi mdi-check mr-2"></i> Approve
                    </a>
                    <a href="<?= site_url('requisition/reject/' . $requisition['id']) ?>" class="btn btn-danger btn-validate" data-validate="reject" data-request-title="<?=$requisition['request_title']?>" data-email="<?=$requisition['employee_email']?>">
                        <i class="mdi mdi-close mr-2"></i> Reject
                    </a>
                <?php endif; ?>
                <?php if(in_array($requisition['status'], [RequisitionModel::STATUS_APPROVED]) && AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE)): ?>
                    <a href="<?= site_url('requisition/reject/' . $requisition['id']) ?>" class="btn btn-danger btn-validate" data-validate="reject" data-request-title="<?=$requisition['request_title']?>" data-email="<?=$requisition['employee_email']?>">
                        <i class="mdi mdi-close mr-2"></i> Reject
                    </a>
                    <a class="btn btn-warning btn-validate" data-validate="cancel" href="<?= site_url('requisition/cancel/' . $requisition['id']) ?>" data-request-title="<?=$requisition['request_title']?>" data-email="<?=$requisition['employee_email']?>">
                        <i class="mdi mdi-close mr-2"></i> Cancel
                    </a>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (in_array($requisition['status'], ['PENDING', 'REJECTED']) || (in_array($requisition['status'], [RequisitionModel::STATUS_APPROVED]) && AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_MANAGE) && !empty($requisition['category']))) : ?>
                <?php if (AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_EDIT)) : ?>
                    <a href="<?= site_url('requisition/edit/' . $requisition['id']) ?>" class="btn btn-primary">
                        Edit Requisition
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php $this->load->view('partials/modals/_validate') ?>
<?php $this->load->view('requisition/_modal_set_approved_type') ?>
