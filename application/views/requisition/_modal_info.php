<div class="modal fade" id="modal-requisition-status" tabindex="-1" role="dialog" aria-labelledby="Requisition Status" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Requisition Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <thead>
                    <tr>
                        <th style="min-width: 110px">Status</th>
                        <th>Description</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><span class="label label-default">PENDING</span></td>
                        <td>Requisition is reviewed by Supervisor or Purchasing</td>
                    </tr>
                    <tr>
                        <td><span class="label label-warning">REJECTED</span></td>
                        <td>Requisition is rejected by Supervisor or Purchasing</td>
                    </tr>
                    <tr>
                        <td><span class="label label-danger">CANCELLED</span></td>
                        <td>Requisition is retracted or cancelled to be requested</td>
                    </tr>
                    <tr>
                        <td><span class="label label-default">APPROVED</span></td>
                        <td>Requisition is approved and ready to proceed</td>
                    </tr>
                    <tr>
                        <td><span class="label label-warning">LISTED</span></td>
                        <td>Requisition is offered to vendor by email</td>
                    </tr>
                    <tr>
                        <td><span class="label label-success">ASK FOR SELECTION</span></td>
                        <td>Notify decision maker to give recommendation</td>
                    </tr>
                    <tr>
                        <td><span class="label label-success">READY</span></td>
                        <td>Requisition is ready to be selected by vendor quotation</td>
                    </tr>
                    <tr>
                        <td><span class="label label-success">SELECTED</span></td>
                        <td>Vendor has been selected, ready for creating PO</td>
                    </tr>
                    <tr>
                        <td><span class="label label-primary">IN PROCESS</span></td>
                        <td>Purchase order has been sent and goods or services in process</td>
                    </tr>
                    <tr>
                        <td><span class="label label-success">DONE</span></td>
                        <td>Item was delivered successfully</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-danger" data-dismiss="modal">
                    CLOSE
                </button>
            </div>
        </div>
    </div>
</div>