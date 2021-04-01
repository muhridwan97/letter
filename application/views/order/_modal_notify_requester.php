<div class="modal fade" id="modal-notify-requester" tabindex="-1" role="dialog" aria-labelledby="modal-notify-requester" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" id="form-notify-requester">
                <?= _csrf() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCompleteOrder">Notify Requester</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead mb-1">Send an information to <strong id="requester-name"></strong>
                        about completing his/her order via email
                        <strong id="requester-email"></strong>?
                    </p>
                    <p class="text-muted">
                        Make sure the requisition items have delivered
                    </p>
                    <div class="form-group mb-0">
                        <label for="message" class="control-label">Additional Message</label>
                        <textarea class="form-control" id="message" name="message" rows="2" required
                                  maxlength="500" placeholder="Additional message"></textarea>
                        <p class="form-text mb-0">Additional info completing order, included in email content.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch">Notify Requester</button>
                </div>
            </form>
        </div>
    </div>
</div>