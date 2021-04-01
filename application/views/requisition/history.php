<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Requisition Status</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="status">
                                <?= $requisitionStatus['status'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= $requisitionStatus['description'] ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($requisitionStatus['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($requisitionStatus['creator_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Requisition History</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="no_requisition">Number</label>
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
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">Status</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="status">
                                <?= if_empty($requisition['status'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="deadline">Deadline</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline">
                                <?= format_date($requisition['deadline'], 'd F Y') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="deadline_remark">Remark</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="deadline_remark">
                                <?= if_empty($requisition['deadline_remark'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="attachment">Attachment</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="attachment">
                                <?php if(empty($requisition['document'])): ?>
                                    -
                                <?php else: ?>
                                    <a href="<?= base_url('uploads/' . $requisition['document']) ?>"></a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
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
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_by">Created By</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_by">
                                <?= if_empty($requisition['creator_name'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($requisition['updated_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Request Items History</h4>
            <div class="table-responsive">
                <table class="table" id="table-category">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Brand</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Description</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1 ?>
                    <?php foreach ($requisitionItems as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= if_empty($item['item_name'], '-') ?></td>
                            <td><?= if_empty($item['brand'], '-') ?></td>
                            <td><?= if_empty($item['type'], '-') ?></td>
                            <td><?= if_empty($item['quantity'], '-') ?></td>
                            <td><?= if_empty($item['unit'], '-') ?></td>
                            <td><?= if_empty($item['description'], '-') ?></td>
                            <td><?= if_empty(format_date($item['created_at'], 'd F Y H:i'), '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
        </div>
    </div>
</form>