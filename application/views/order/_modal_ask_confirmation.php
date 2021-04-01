<div class="modal fade" id="modal-ask-confirmation" tabindex="-1" role="dialog" aria-labelledby="modal-ask-confirmation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" id="form-ask-confirmation">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAskConfirmation">Ask Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead mb-1">
                        Ask requester <strong id="requester"></strong> to confirm receiving of items / services
                        <strong id="request-title"></strong> from order <strong id="no-purchase"></strong>?
                    </p>
                    <p class="text-primary" id="receiving-note">
                        Make sure the items / services have delivered to requester
                    </p>

                    <div class="form-group">
                        <label for="received_date">Received Date</label>
                        <input type="text" class="form-control datepicker" id="received_date" name="received_date" required maxlength="50"
                               placeholder="Item or services is received" value="<?= date('d/m/Y') ?>" autocomplete="off">
                    </div>

                    <div class="form-group mb-0">
                        <label for="handover_note" class="control-label">Handover Note</label>
                        <textarea class="form-control" id="handover_note" name="handover_note" rows="4" required
                                  maxlength="1000" minlength="10" placeholder="Handover description and information"></textarea>
                        <p class="form-text mb-0">Item and maintenance note</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch">Ask Confirmation</button>
                </div>
            </form>
        </div>
    </div>
</div>
