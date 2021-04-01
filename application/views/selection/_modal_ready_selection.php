<div class="modal fade" id="modal-ready-selection" role="dialog" aria-labelledby="modal-ready-selection" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post">
                <?= _csrf() ?>
                <?= _method('put') ?>
                <div class="modal-header">
                    <h5 class="modal-title">Ready for Vendor Selection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        Put selection recommendation as decision maker of <strong id="requisition-title"></strong>
                        and notify the selector to start picking the offers?
                    </p>
                    <div class="form-group mb-0">
                        <label for="note">Selection Note</label>
                        <textarea class="form-control" id="note" name="note" required maxlength="2000"
                                  placeholder="Purchasing suggestion info about vendor list"></textarea>
                        <span class="form-text">
                            This field should be contains <strong>Decision maker opinion</strong> about offered vendor.
                            <br>
                            <span class="text-danger">
                                Your email will be captured as person who responsible for the recommendation.
                            </span>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success" data-toggle="one-touch">
                        Ready for Selection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
