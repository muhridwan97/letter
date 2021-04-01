<div class="modal fade" id="modal-complete-order" tabindex="-1" role="dialog" aria-labelledby="modal-complete-order" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" id="form-complete-order">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCompleteOrder">Completing Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead mb-1">Are you sure want set complete requisition <br>
                        <strong id="request-title"></strong>?
                    </p>
                    <p class="text-muted">
                        Make sure the requisition items have delivered
                    </p>

                    <div class="form-group">
                        <label for="nominal" class="control-label">Give Rating</label>
                        <div>
                            <div class="form-check form-check-inline mt-2 mb-0">
                                <label class="form-check-label mb-0">
                                    <input type="radio" class="form-check-input" name="rating" id="rate_poor" value="1" required> Poor
                                </label>
                            </div>
                            <div class="form-check form-check-inline mt-2 mb-0">
                                <label class="form-check-label mb-0">
                                    <input type="radio" class="form-check-input" name="rating" id="rate_bad" value="2"> Bad
                                </label>
                            </div>
                            <div class="form-check form-check-inline mt-2 mb-0">
                                <label class="form-check-label mb-0">
                                    <input type="radio" class="form-check-input" name="rating" id="rate_fair" value="3"> Fair
                                </label>
                            </div>
                            <div class="form-check form-check-inline mt-2 mb-0">
                                <label class="form-check-label mb-0">
                                    <input type="radio" class="form-check-input" name="rating" id="rate_good" value="4"> Good
                                </label>
                            </div>
                            <div class="form-check form-check-inline mt-2 mb-0">
                                <label class="form-check-label mb-0">
                                    <input type="radio" class="form-check-input" name="rating" id="rate_very_good" value="5"> Very Good
                                </label>
                            </div>
                        </div>
						<p class="form-text text-danger mt-2 mb-0">
							<strong><i class="mdi mdi-information-outline"></i> Note:</strong>
							Kesesuaian dengan order, ketepatan waktu barang / jasa, HSE vendor, komunikasi,
							solusi jika ada kendala.
						</p>
                    </div>

                    <div class="form-group mb-0">
                        <label for="remark" class="control-label">Purchasing Review</label>
                        <textarea class="form-control" id="remark" name="remark" rows="2"
                                  maxlength="500" placeholder="Comment and remark about this purchasing"></textarea>
                        <p class="form-text text-info mb-0">
                            <strong><i class="mdi mdi-information-outline"></i> Note:</strong>
                            suitable and match with the order, delivery in time, vendor HSE, communication and feedback,
                            giving solution and regular troubleshooting.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary" data-toggle="one-touch">Complete</button>
                </div>
            </form>
        </div>
    </div>
</div>
