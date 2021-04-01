<div class="modal fade" id="modal-order" tabindex="-1" role="dialog" aria-labelledby="modal-order" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="post" id="form-order">
                <?= _csrf() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalOrder">Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Select Vendor</label>
                                <p class="mb-0" id="vendor-title"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">For Requisition</label>
                                <p class="mb-0" id="requisition-title"></p>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="no_purchase" class="control-label">No Purchase Order</label>
                                <input type="text" class="form-control" name="no_purchase" id="no_purchase" readonly
                                       value="<?= isset($noPurchaseOrder) ? $noPurchaseOrder : 'XXXX'?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date" class="control-label">Purchase Date</label>
                                <input type="text" class="form-control datepicker" id="date" name="date" autocomplete="off"
                                       value="<?= format_date('now', 'd/m/Y') ?>"
                                       required maxlength="50" placeholder="Purchase date">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nominal" class="control-label">Tax Type</label>
                                <div class="form-row">
                                    <div class="col-5">
                                        <div class="form-check form-check-inline mt-2 mb-0">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="tax_type" id="tax_nominal" value="nominal"> Nominal
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-check-inline mt-2 mb-0">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="tax_type" id="tax_percentage" value="percentage" checked> Percentage
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax" class="control-label">Tax Percent (VAT %)</label>
                                <input type="number" class="form-control" id="tax" name="tax"
                                       value="10" required maxlength="25" placeholder="Order tax amount">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="offer_discount_goods" class="control-label">
                                    Offer Discount Goods
                                </label>
                                <input type="text" class="form-control currency" id="offer_discount_goods"
                                       name="offer_discount_goods" value="Rp. 0"
                                       maxlength="50" placeholder="Revision discount goods">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="offer_discount_services" class="control-label">
                                    Offer Discount Services
                                </label>
                                <input type="text" class="form-control currency" id="offer_discount_services"
                                       name="offer_discount_services" value="Rp. 0"
                                       maxlength="50" placeholder="Revision discount services">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rounding_goods" class="control-label">
                                    Rounding Goods
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select class="custom-select" name="rounding_goods_sign" id="rounding_goods_sign">
                                            <option value="">+</option>
                                            <option value="-">―</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control currency" id="rounding_goods"
                                           name="rounding_goods" value="Rp. 0"
                                           maxlength="25" placeholder="Round goods value">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rounding_services" class="control-label">
                                    Rounding Services
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select class="custom-select" name="rounding_services_sign" id="rounding_services_sign">
                                            <option value="">+</option>
                                            <option value="-">―</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control currency" id="rounding_services"
                                           name="rounding_services" value="Rp. 0"
                                           maxlength="25" placeholder="Round service value">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nominal" class="control-label">Tax Basis Type</label>
                                <div class="form-row">
                                    <div class="col-5">
                                        <div class="form-check form-check-inline mt-2 mb-0">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="tax_basis_type" id="tax_basis_nominal" value="nominal" required> Nominal
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-check-inline mt-2 mb-0">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="tax_basis_type" id="tax_basis_percentage" value="percentage"> Percentage
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Services Total</label>
                                <small class="form-control-plaintext text-primary pt-0">
                                    Price of services <strong id="label-total-services"></strong>
                                    and offer discount service <strong id="label-offer-discount"></strong>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_basis_label" class="control-label">Tax Basis Label</label>
                                <input type="text" class="form-control" id="tax_basis_label"
                                       name="tax_basis_label" value=""
                                       maxlength="25" placeholder="Label tax basis">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tax_basis" class="control-label">
                                    Tax Basis Amount
                                </label>
                                <input type="text" class="form-control" id="tax_basis"
                                       name="tax_basis" value="Rp. 0" step="any"
                                       maxlength="25" placeholder="Tax basis amount">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="miscellaneous_label" class="control-label">
                                    Miscellaneous Label
                                </label>
                                <input type="text" class="form-control" id="miscellaneous_label"
                                       name="miscellaneous_label" value="Miscellaneous"
                                       maxlength="50" placeholder="Other cost label">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="miscellaneous_expense" class="control-label">
                                    Miscellaneous Value
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select class="custom-select" name="miscellaneous_expense_sign" id="miscellaneous_expense_sign">
                                            <option value="">+</option>
                                            <option value="-">―</option>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control currency" id="miscellaneous_expense"
                                           name="miscellaneous_expense" value="Rp. 0"
                                           maxlength="25" placeholder="Another cost order">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="delivery_cost" class="control-label">Delivery Cost</label>
                                <input type="text" class="form-control currency" id="delivery_cost"
                                       name="delivery_cost" value="Rp. 0"
                                       maxlength="25" placeholder="Order delivery cost">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="total" class="control-label">
                                    Estimated Total Price
                                </label>
                                <input type="text" class="form-control" id="total"
                                       name="total" value="Rp. 0" readonly
                                       maxlength="25" placeholder="Estimated Total">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="note" class="control-label">Purchase Note</label>
                        <p class="form-text">Additional info about purchasing, included in email content and PO</p>
                        <table class="table table-sm">
                            <tbody id="note-wrapper">
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-info btn-sm btn-block btn-add-note">ADD NOTE ITEM</button>
                    </div>

                    <div class="form-group mb-0">
                        <label for="email_note" class="control-label">Email Note</label>
                        <textarea class="form-control" id="email_note" name="email_note" rows="2"
                                  maxlength="500" placeholder="Additional note in email"></textarea>
                        <p class="form-text mb-0">Information will be included inside email body</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-sm btn-info" id="btn-preview" target="_blank">Preview Draft PO</a>
                    <button type="submit" class="btn btn-sm btn-primary" data-toggle="one-touch">Save and Send PO</button>
                </div>
            </form>
        </div>
    </div>
</div>
