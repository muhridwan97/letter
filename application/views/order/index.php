<div class="card grid-margin">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between">
            <h4 class="card-title mb-0">Incoming PO</h4>
            <div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=incoming" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
            </div>
        </div>

        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Requisition</th>
                <th class="text-nowrap">Selected Offer</th>
                <th class="text-nowrap">Rate Type</th>
                <th>Status</th>
                <th class="text-nowrap">Item Qty</th>
                <th>Total Price</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
			<?php
			$statusLabels = [
				PurchaseOfferModel::STATUS_SELECTED => 'primary',
				PurchaseOfferModel::STATUS_UPLOADED => 'danger',
				PurchaseOfferModel::STATUS_IN_PROCESS => 'success',
				PurchaseOfferModel::STATUS_ORDERED => 'info',
				PurchaseOfferModel::STATUS_ASK_CONFIRMATION => 'warning',
				PurchaseOfferModel::STATUS_CONFIRMED => 'primary',
				PurchaseOfferModel::STATUS_UNCONFIRMED => 'danger',
			]
			?>
            <?php $no = 1 ?>
            <?php foreach ($activeRequisitions as $requisition): ?>
                <tr>
                    <td class="text-md-center"><?= $no++ ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                            <?= $requisition['request_title'] ?>
                        </a>
						<?= if_empty($requisition['no_purchase'], '', '<br><span class="text-danger font-weight-bold">', '</span>') ?>
                    </td>
                    <td>
                        <a href="<?= site_url('purchasing/offer/view/' . $requisition['id_purchase_offer']) ?>">
                            <?= $requisition['vendor'] ?>
                        </a>
                    </td>
					<td>
						<span class="badge badge-<?= $requisition['rate_type'] == RequisitionModel::RATE_TYPE_ADJUSTABLE ? 'danger' : 'success' ?>">
							<?= $requisition['rate_type'] ?>
						</span>
					</td>
					<td>
						<span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
							<?= $requisition['status'] == PurchaseOfferModel::STATUS_ASK_CONFIRMATION ? 'ASK' : $requisition['status'] ?>
						</span>
					</td>
                    <td class="text-nowrap"><?= numerical($requisition['total_item_quantity']) ?> items</td>
                    <td class="text-nowrap">Rp. <?= numerical($requisition['total_price']) ?></td>
                    <td class="text-md-right row-requisition"
                        data-request-title="<?= $requisition['request_title'] ?>"
                        data-id-purchase-offer="<?= $requisition['id_purchase_offer'] ?>"
                        data-offer-discount="<?= $requisition['offer_discount'] ?>"
                        data-offer-discount-goods="<?= $requisition['offer_discount_goods'] ?>"
                        data-offer-discount-services="<?= $requisition['offer_discount_services'] ?>"
                        data-total-price-goods="<?= $requisition['total_price_goods'] ?>"
                        data-total-price-services="<?= $requisition['total_price_services'] ?>"
                        data-total-price-before-offer-discount="<?= $requisition['total_price_before_offer_discount'] ?>"
                        data-total-price="<?= $requisition['total_price'] ?>"
                        data-preview-url="<?= site_url('purchasing/order/preview/' . $requisition['id_purchase_offer']) ?>"
                        data-vendor="<?= $requisition['vendor'] ?>"
                        data-is-pkp="<?= $requisition['is_pkp'] ?>">
						<?php if ($requisition['rate_type'] == RequisitionModel::RATE_TYPE_ADJUSTABLE): ?>
							<?php if(empty($requisition['id_purchase_order'])): ?>
								<div class="btn-group">
									<a href="<?= site_url('purchasing/order/draft/' . $requisition['id_purchase_offer']) ?>" class="btn btn-sm btn-danger px-2 btn-draft">
										Draft
									</a>
									<button type="button" class="btn btn-sm btn-danger px-2 dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
										<span class="sr-only">Toggle Dropdown</span>
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VIEW)): ?>
											<a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
												<i class="mdi mdi-eye-outline mr-2"></i> Requisition
											</a>
										<?php endif; ?>
										<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_VIEW)): ?>
											<a class="dropdown-item" href="<?= site_url('purchasing/offer/view/' . $requisition['id_purchase_offer']) ?>">
												<i class="mdi mdi-eye-outline mr-2"></i> View Offer
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php else: ?>
								<div class="dropdown">
									<button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
										Action
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item" href="<?= site_url('purchasing/order/view/' . $requisition['id_purchase_order']) ?>">
											<i class="mdi mdi-eye-outline mr-2"></i> View
										</a>
										<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_EDIT)): ?>
											<?php if(in_array($requisition['status'], [PurchaseOfferModel::STATUS_SELECTED, PurchaseOfferModel::STATUS_UPLOADED, PurchaseOfferModel::STATUS_IN_PROCESS])): ?>
												<a class="dropdown-item" href="<?= site_url('purchasing/order/item-completion/' . $requisition['id_purchase_order']) ?>">
													<i class="mdi mdi-square-edit-outline mr-2"></i> Data Completion
												</a>
											<?php endif; ?>
											<?php if(!empty($requisition['document']) && in_array($requisition['status'], [PurchaseOfferModel::STATUS_IN_PROCESS, PurchaseOfferModel::STATUS_UNCONFIRMED])): ?>
												<a class="dropdown-item btn-ask-confirmation" href="<?= site_url('purchasing/order/ask-confirmation/' . $requisition['id_purchase_order']) ?>">
													<i class="mdi mdi-help-circle-outline mr-2"></i> Ask Confirmation
												</a>
											<?php endif; ?>
										<?php endif; ?>
										<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_CREATE)): ?>
											<?php if($requisition['status'] == PurchaseOfferModel::STATUS_CONFIRMED): ?>
												<a class="dropdown-item btn-order" href="<?= site_url('purchasing/order/publish/' . $requisition['id_purchase_order']) ?>">
													<i class="mdi mdi-file-outline mr-2"></i> Publish PO
												</a>
											<?php endif; ?>
										<?php endif; ?>
										<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_DELETE)): ?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
											   data-id="<?= $requisition['id'] ?>" data-label="<?= $requisition['no_purchase'] ?>" data-title="Purchase Order"
											   data-url="<?= site_url('purchasing/order/delete/' . $requisition['id_purchase_order']) ?>">
												<i class="mdi mdi-trash-can-outline mr-2"></i> Cancel Draft
											</a>
										<?php endif; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php else: ?>
							<div class="btn-group">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_CREATE)): ?>
									<a href="<?= site_url('purchasing/order/save/' . $requisition['id_purchase_offer']) ?>" class="btn btn-sm btn-primary px-2 btn-order">
										Order
									</a>
								<?php endif; ?>
								<button type="button" class="btn btn-sm btn-primary px-2 dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<div class="dropdown-menu dropdown-menu-right">
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VIEW)): ?>
										<a class="dropdown-item" href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
											<i class="mdi mdi-eye-outline mr-2"></i> Requisition
										</a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_VIEW)): ?>
										<a class="dropdown-item" href="<?= site_url('purchasing/offer/view/' . $requisition['id_purchase_offer']) ?>">
											<i class="mdi mdi-eye-outline mr-2"></i> View Offer
										</a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_CREATE)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?= site_url('purchasing/order/preview/' . $requisition['id_purchase_offer']) ?>">
											<i class="mdi mdi-file-search-outline mr-2"></i> Print Preview PO
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($activeRequisitions)): ?>
                <tr>
                    <td colspan="8" class="text-center">All order has been proceed</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="card-title mb-0">Purchase Orders</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=po" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
            </div>
        </div>
        <table class="table table-hover table-sm responsive" id="table-order">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 20px">No</th>
                <th class="text-nowrap">No Purchase</th>
                <th>Requisition</th>
                <th class="text-nowrap">Selected Offer</th>
                <th class="type-numeric">TOP</th>
                <th class="type-date">Delivery</th>
                <th class="type-date">Date</th>
                <th class="type-currency">Total</th>
                <th class="type-status">Status</th>
                <th class="type-document-status">Document</th>
                <th style="width: 70px" class="type-action text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($purchaseOrders)): ?>
                <?php
                $statusLabels = [
                    PurchaseOfferModel::STATUS_ORDERED => 'info',
                    PurchaseOfferModel::STATUS_ASK_CONFIRMATION => 'warning',
                    PurchaseOfferModel::STATUS_CONFIRMED => 'primary',
                    PurchaseOfferModel::STATUS_UNCONFIRMED => 'danger',
                    PurchaseOfferModel::STATUS_COMPLETED => 'success',
                ]
                ?>
                <?php $no = isset($purchaseOrders) ? ($purchaseOrders['current_page'] - 1) * $purchaseOrders['per_page'] : 0 ?>
                <?php foreach ($purchaseOrders['data'] as $order): ?>
                    <tr>
                        <td class="text-md-center"><?= ++$no ?></td>
                        <td><?= $order['no_purchase'] ?></td>
                        <td style="width: 130px">
                            <a href="<?= site_url('requisition/view/' . $order['id_requisition']) ?>">
                                <?= $order['request_title'] ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= site_url('purchasing/offer/view/' . $order['id_purchase_offer']) ?>">
                                <?= $order['vendor'] ?>
                            </a>
                        </td>
                        <td><?= numerical($order['top']) ?></td>
                        <td><?= if_empty(format_date($order['lead_time'], 'd F Y'), '-') ?></td>
                        <td><?= format_date($order['date'], 'd F Y') ?></td>
                        <td>Rp. <?= numerical($order['total_price']) ?></td>
                        <td>
                            <span class="badge badge-<?= get_if_exist($statusLabels, $order['status'], 'secondary') ?>">
                                <?= $order['status'] ?>
                            </span>
                        </td>
                        <td class="text-md-right">
                            <div class="dropdown row-order"
                                data-email="<?= $order['employee_email'] ?>"
                                data-requester="<?= $order['employee_name'] ?>"
                                data-request-title="<?= $order['request_title'] ?>">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_VIEW)): ?>
										<a class="dropdown-item" href="<?= site_url('purchasing/order/view/' . $order['id']) ?>">
											<i class="mdi mdi-eye-outline mr-2"></i> View
										</a>
                                        <a class="dropdown-item" href="<?= site_url('purchasing/order/print-order/' . $order['id']) ?>">
                                            <i class="mdi mdi-cloud-print-outline mr-2"></i> Print <?= $order['document_status'] == PurchaseOrderModel::STATUS_DRAFT ? 'Draft' : 'Order' ?>
                                        </a>
										<a class="dropdown-item btn-print-handover" href="<?= site_url('purchasing/handover/print-handover/' . $order['id']) ?>">
											<i class="mdi mdi-cloud-print-outline mr-2"></i> Print Handover
										</a>
                                        <a class="dropdown-item btn-view-handover" href="<?= site_url('purchasing/handover/view/' . $order['id']) ?>">
                                            <i class="mdi mdi-eye-outline mr-2"></i> View Handover
                                        </a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_EDIT)): ?>
										<?php if(in_array($order['status'], [PurchaseOfferModel::STATUS_ORDERED, PurchaseOfferModel::STATUS_UNCONFIRMED])): ?>
											<a class="dropdown-item btn-ask-confirmation" href="<?= site_url('purchasing/order/ask-confirmation/' . $order['id']) ?>">
												<i class="mdi mdi-help-circle-outline mr-2"></i> Ask Confirmation
											</a>
										<?php endif; ?>
                                        <?php if($order['status'] == PurchaseOfferModel::STATUS_CONFIRMED): ?>
                                            <a class="dropdown-item btn-complete-order" href="<?= site_url('purchasing/order/complete/' . $order['id']) ?>">
                                                <i class="mdi mdi-check-circle-outline mr-2"></i> Completing Order
                                            </a>
                                        <?php endif; ?>
                                        <a class="dropdown-item btn-notify-requester" href="<?= site_url('purchasing/order/notify/' . $order['id']) ?>">
                                            <i class="mdi mdi-email-check-outline mr-2"></i> Notify Requester
                                        </a>
                                    <?php endif; ?>
                                    <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_DELETE)): ?>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                                           data-id="<?= $order['id'] ?>" data-label="<?= $order['no_purchase'] ?>" data-title="Purchase Order"
                                           data-url="<?= site_url('purchasing/order/delete/' . $order['id']) ?>">
                                            <i class="mdi mdi-trash-can-outline mr-2"></i> Cancel Order
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($purchaseOrders['data'])): ?>
                    <tr>
                        <td colspan="11">No orders data available</td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <?php if(!empty($purchaseOrders)): ?>
            <?php $this->load->view('partials/_pagination', ['pagination' => $purchaseOrders]) ?>
        <?php endif; ?>
    </div>
</div>


<script id="control-order-template" type="text/x-custom-template">
    <div class="dropdown row-order"
         data-email="{{employee_email}}"
         data-requester="{{employee_name}}"
         data-request-title="{{request_title}}"
         data-vendor="{{vendor}}"
         data-receiving-note="{{receiving_note}}"
         data-no-purchase="{{no_purchase}}">
        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
            Action
        </button>
        <div class="dropdown-menu dropdown-menu-right">
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_VIEW)): ?>
                <a class="dropdown-item" href="<?= site_url('purchasing/order/view/{{id}}') ?>">
                    <i class="mdi mdi-eye-outline mr-2"></i> View
                </a>
                <a class="dropdown-item" href="<?= site_url('purchasing/order/print-order/{{id}}') ?>">
                    <i class="mdi mdi-cloud-print-outline mr-2"></i> Print <span class="label-order">Order</span>
                </a>
                <a class="dropdown-item btn-print-handover" href="<?= site_url('purchasing/handover/print-handover/{{id}}') ?>">
                    <i class="mdi mdi-cloud-print-outline mr-2"></i> Print Handover
                </a>
                <a class="dropdown-item btn-view-handover" href="<?= site_url('purchasing/handover/view/{{id}}') ?>">
                    <i class="mdi mdi-eye-outline mr-2"></i> View Handover
                </a>
                <div class="dropdown-divider btn-view-handover"></div>
            <?php endif; ?>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_EDIT)): ?>
                <a class="dropdown-item btn-ask-confirmation" href="<?= site_url('purchasing/order/ask-confirmation/{{id}}') ?>">
                    <i class="mdi mdi-help-circle-outline mr-2"></i> Ask Confirmation
                </a>
                <a class="dropdown-item btn-complete-order" href="<?= site_url('purchasing/order/complete/{{id}}') ?>">
                    <i class="mdi mdi-check-circle-outline mr-2"></i> Completing Order
                </a>
                <a class="dropdown-item btn-notify-requester" href="<?= site_url('purchasing/order/notify/{{id}}') ?>">
                    <i class="mdi mdi-email-check-outline mr-2"></i> Notify Requester
                </a>
            <?php endif; ?>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_DELETE)): ?>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
                   data-id="{{id}}" data-label="{{no_purchase}}" data-title="Purchase Order"
                   data-url="<?= site_url('purchasing/order/delete/{{id}}') ?>">
					<i class="mdi mdi-trash-can-outline mr-2"></i> Cancel <span class="label-order">Order</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</script>

<?php $this->load->view('order/_modal_filter') ?>
<?php $this->load->view('order/_modal_draft') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_CREATE)): ?>
    <?php $this->load->view('order/_modal_order') ?>
<?php endif; ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_EDIT)): ?>
    <?php $this->load->view('order/_modal_ask_confirmation') ?>
    <?php $this->load->view('order/_modal_complete_order') ?>
    <?php $this->load->view('order/_modal_notify_requester') ?>
<?php endif; ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
