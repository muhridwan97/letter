<div class="modal fade" id="modal-revert-requisition" tabindex="-1" role="dialog" aria-labelledby="Revert Requisition" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Revert Requisition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="post">
                    <?= _csrf() ?>
                    <?= _method('put') ?>
                    <p class="mb-3">
                        Requisition <strong id="requisition-title"></strong> (<strong id="requisition-description"></strong>)
                        will be reverted to status.
                    </p>
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th style="min-width: 110px">Revert To</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="approved" class="d-none">
                            <td><span class="label label-default">APPROVED</span></td>
                            <td>
                                <p class="mb-1"><strong>Allowed to edit requisition</strong></p>
                                <p>Effect: all vendor quotation will be deleted, PO will be deleted as well.</p>
                            </td>
                            <td><button class="btn btn-danger btn-sm" type="submit" name="status" value="APPROVED">REVERT</button></td>
                        </tr>
                        <tr id="listed" class="d-none">
                            <td><span class="label label-warning">LISTED</span></td>
                            <td>
                                <p class="mb-1"><strong>Allowed to add more vendor quotation</strong></p>
                                <p>
                                    Effect: Selected vendor will be reverted, PO will be deleted.
                                </p>
                            </td>
                            <td><button class="btn btn-danger btn-sm" type="submit" name="status" value="LISTED">REVERT</button></td>
                        </tr>
                        <tr id="ready" class="d-none">
                            <td><span class="label label-primary">READY</span></td>
                            <td>
                                <p class="mb-1"><strong>Cancel selected vendor</strong></p>
                                <p>
                                    Effect: Allowed to change or reselect another vendor but can't add more vendor,
                                    PO will be deleted as well.
                                </p>
                            </td>
                            <td><button class="btn btn-danger btn-sm" type="submit" name="status" value="READY">REVERT</button></td>
                        </tr>
                        <tr class="d-none">
                            <td><span class="label label-success">SELECTED</span></td>
                            <td>
                                <p class="mb-1"><strong>Cancel Purchase Order</strong></p>
                                <p>
                                    Effect: Remove purchase order, purchasing can recreate purchase order data
                                    (tax, delivery, resend PO)
                                </p>
                            </td>
                            <td><button class="btn btn-danger btn-sm" type="submit" name="status" value="SELECTED">REVERT</button></td>
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>