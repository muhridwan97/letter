<div class="modal fade" id="modal-send" aria-labelledby="modalSend">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= site_url('master/vendor/send') ?>" method="POST" id="form-send">
                <?= _csrf() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSend">Send Create Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                               value="" placeholder="Email vendor">
                        <?= form_error('email'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                        CLOSE
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        APPLY SEND
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
