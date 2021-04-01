<div class="modal fade" id="modal-confirm-order" tabindex="-1" role="dialog" aria-labelledby="modal-confirm-order" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" id="form-confirm-order">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmOrder">Confirm Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead mb-1">
                        Are you sure want to confirm request <strong id="request-title"></strong>
                        from order <strong id="no-purchase"></strong>?
                    </p>
                    <p class="text-muted">
                        Make sure the items / services have delivered to you
                    </p>

                    <div class="form-group">
                        <label for="received_date">Received Date (Revise)</label>
                        <input type="text" class="form-control datepicker" id="received_date" name="received_date" required maxlength="50"
                               placeholder="Item or services is received" value="<?= date('d/m/Y') ?>" autocomplete="off">
                        <p class="form-text mb-0">Revise the date if necessary</p>
                    </div>
                    <div class="form-group">
                        <label for="receiving_note" class="control-label">Receiving Note</label>
                        <textarea class="form-control" id="receiving_note" name="receiving_note" rows="2" required
                                  maxlength="500" placeholder="Receiving goods or services note"></textarea>
                        <p class="form-text mb-0">The order will be proceed to purchasing</p>
                    </div>
                    <div class="form-group">
                        <label for="nominal" class="control-label">User Rating (Items / Services)</label>
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
                        <label for="rating_reason" class="control-label">Rating Reason</label>
                        <textarea class="form-control" id="rating_reason" name="rating_reason" rows="2" required
                                  maxlength="500" placeholder="Confirmation and rating description"></textarea>
                        <p class="form-text text-info mb-0">
                            <strong><i class="mdi mdi-information-outline"></i> Note:</strong>
                            suitable and match with the order, delivery in time, vendor HSE, communication and feedback,
                            giving solution and regular troubleshooting.
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
