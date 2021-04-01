<form action="<?= site_url('requisition/send-to-vendor/' . $requisition['id']) ?>" method="post" class="form-plaintext">
    <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Send To Vendor</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_requisition">No Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="no_requisition">
                                <?= $requisition['no_requisition'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="request_title">Request</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="request_title">
                                <?= $requisition['request_title'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="category">Category</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="category">
                                <?= if_empty($requisition['category'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="requester">
                                <?= if_empty($requisition['employee_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($requisition['description'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($requisition['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Vendor List</h4>
            <p class="text-muted">List of vendor by requisition item category</p>

            <table class="table responsive data-table" id="vendor-send-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Vendor Name</th>
                    <th>City</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Select</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; ?>
                <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <a href="<?= site_url('master/vendor/view/'. $vendor['id']) ?>" target="_blank">
                                <?= $vendor['vendor'] ?>
                            </a>
                        </td>
                        <td><?= $vendor['new_city'] ?></td>
                        <td><?= if_empty($vendor['contact'], '-') ?></td>
                        <td><?= if_empty($vendor['email'], '-') ?></td>
                        <td class="text-md-center">
                            <div class="form-check d-inline-block mt-0 mb-3">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" value="<?= $vendor['id'] ?>"
                                           name="vendors[]" id="vendor_<?= $vendor['id'] ?>" <?= set_checkbox('vendors[]', $vendor['id']) ?>>
									<i class="input-helper"></i>
                                </label>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Send To Vendor</h4>
            <div class="form-group">
                <div class="form-check">
                    <label class="form-check-label" for="send_email">
                        <input type="checkbox" class="form-check-input" value="1"
                               name="send_email" id="send_email" <?= set_checkbox('send_email', 1) ?>>
                        Send Email To Vendor
                    </label>
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="description">Additional Notes</label>
                <textarea class="form-control" id="description" name="description" maxlength="500" rows="3"
                          placeholder="Request note will be included to email"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch">
                Send To Vendor
            </button>
        </div>
    </div>
</form>
