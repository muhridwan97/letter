<div class="modal fade" id="modal-draft" tabindex="-1" role="dialog" aria-labelledby="modal-draft" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="#" method="post" id="form-draft">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Draft Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead mb-1">
						Are you sure want to create <strong>draft PO</strong> and
						<strong>wait job completion</strong> for published data <span class="draft-label"></span>?
					</p>
					<p class="text-danger">This process will send email to vendor for work completion.</p>
                    <div class="form-group mb-2">
                        <label for="message">Message</label>
                        <textarea class="form-control" name="message" id="message" rows="3" placeholder="Draft message"></textarea>
                        <span class="form-text">This message may be included to the vendor email <a href="#" class="validate-email"></a></span>
                    </div>
					<div class="form-check">
						<label class="form-check-label" for="send_email">
							<input type="checkbox" class="form-check-input" value="1" name="send_email" id="send_email" <?= set_checkbox('send_email', 1, true) ?>>
							Email To Vendor (Request data completion)
						</label>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">CLOSE</button>
                    <a href="#" data-url="<?= site_url('purchasing/order/preview-draft-email') ?>" class="btn btn-sm btn-success btn-preview-email" target="_blank">
                        PREVIEW EMAIL
                    </a>
                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="one-touch">
                        CREATE DRAFT PO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
