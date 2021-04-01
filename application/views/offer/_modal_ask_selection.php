<div class="modal fade" id="modal-ask-selection" role="dialog" aria-labelledby="modal-ask-selection" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Asking for Vendor Selection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Are you sure close vendor offers for requisition <strong id="requisition-title"></strong>
                        and notify Manager to <strong>start making selection</strong> of the quotations?
                    </p>
                    <div class="form-group">
                        <label for="decision_maker">Decision Maker Email</label>
						<select class="form-control select2" name="decision_maker" id="decision_maker" data-placeholder="Select decision maker" data-top-manager="<?= get_setting('top_manager') ?>" style="width: 100%" required>
							<option value="">-- Select decision maker --</option>
							<?php foreach ($quotationManageUsers as $manager): ?>
								<option value="<?= $manager['id'] ?>">
									<?= $manager['name'] ?> (<?= $manager['email'] ?>)
								</option>
							<?php endforeach; ?>
						</select>
                        <span class="form-text">Vendor offers will be sent to user has permission quotation-manage.</span>
                        <span class="form-text text-danger" id="decision-maker-error" style="display: none"></span>
                    </div>
                    <div class="form-group mb-0">
                        <label for="note">Admin Recommendation</label>
                        <textarea class="form-control" id="note" name="note" required minlength="20" maxlength="2000"
                                  placeholder="Purchasing recommendation info about vendor list"></textarea>
                        <span class="form-text">
                            This field should be contains Purchasing admin opinion about offered vendor, update latter after vendors apply their quotations.
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch" data-touch-message="Sending...">
                        Ask for Selection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
