<form action="<?= site_url('purchasing/offer/save') ?>" method="POST" id="form-offer" enctype="multipart/form-data">
    <?= _csrf() ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Create New Offer</h4>
            <div class="form-row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="requisition">Requisition</label>
                        <select class="form-control select2" name="requisition" id="requisition" data-placeholder="Select requisition" required>
                            <option value="">-- Select Requisition --</option>
                            <?php foreach ($requisitions as $requisition): ?>
                                <option value="<?= $requisition['id'] ?>"<?= set_select('requisition', $requisition['id'], $requisition['_selected']) ?>>
                                    <?= $requisition['request_title'] ?> (<?= $requisition['no_requisition'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('requisition') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="vendor">Vendor</label>
                        <select class="form-control select2" name="vendor" id="vendor" data-placeholder="Select vendor" required>
                            <option value="">-- Select Vendor --</option>
                            <?php foreach ($vendors as $vendor): ?>
                                <option value="<?= $vendor['id'] ?>"<?= set_select('vendor', $vendor['id']) ?>>
                                    <?= $vendor['vendor'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('vendor') ?>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="lead_time">Delivery Date</label>
                        <input type="text" class="form-control datepicker" id="lead_time" name="lead_time" required maxlength="50"
                               value="<?= set_value('lead_time') ?>" placeholder="Expected the items has arrived" autocomplete="off">
                        <?= form_error('lead_time') ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="top">Terms Of Payment (Days)</label>
                        <input type="number" class="form-control" id="top" name="top" required min="0" max="100"
                               placeholder="Terms of payment" value="<?= set_value('top') ?>">
                        <?= form_error('top') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="document">Attachment</label>
                        <input type="file" id="document" name="document" class="file-upload-default" data-max-size="3000000" required>
                        <div class="input-group">
                            <input type="text" class="form-control file-upload-info" disabled placeholder="Upload file">
                            <div class="input-group-append">
                                <button class="file-upload-browse btn btn-info btn-simple-upload" type="button">
                                    Upload
                                </button>
                            </div>
                        </div>
                        <?= form_error('document') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500" rows="3"
                          placeholder="Purchase offer note"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Request Items</h4>

            <table class="table responsive" id="table-item">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Note</th>
                    <th class="text-md-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $takenItems = set_value('taken_items', []); ?>
                <?php $items = set_value('items', $requisitionItems); ?>
                <?php $no = 1 ?>
                <?php foreach ($items as $index => &$item): ?>
                    <?php
                        foreach ($takenItems as $taken) {
                            if($item['id'] == $taken['id_requisition_item']) {
                                $item['quantity'] -= $taken['quantity'];
                            }
                        }
                    ?>
                    <?php if($item['quantity'] > 0): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="label-item"><?= $item['item_name'] ?></td>
                            <td class="label-brand"><?= if_empty($item['brand'], '-') ?></td>
                            <td class="label-quantity"><?= $item['quantity'] ?></td>
                            <td class="label-unit"><?= if_empty($item['unit'], '-') ?></td>
                            <td class="label-description"><?= if_empty($item['description'], '-') ?></td>
                            <td class="text-md-right">
                                <input type="hidden" id="id_requisition_item" class="id_requisition_item"  value="<?= $item['id'] ?>">
                                <input type="hidden" id="quantity" value="<?= $item['quantity'] ?>">
                                <input type="hidden" id="item_name" value="<?= $item['item_name'] ?>">
                                <button class="btn btn-sm btn-info btn-take" type="button">
                                    Take
                                </button>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if(empty($items)): ?>
                    <tr class="row-placeholder">
                        <td colspan="7">No items available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Taken Offer Items</h4>
            <?= form_error('taken_items[]') ?>

            <table class="table responsive" id="table-taken">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Unit Price</th>
                    <th>Unit Disc</th>
                    <th>Total</th>
                    <th>Description</th>
                    <th class="text-md-right">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($takenItems as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="label-item"><?= $item['item_name'] ?></td>
                        <td class="label-brand"><?= $item['brand'] ?></td>
                        <td class="label-quantity"><?= $item['quantity'] ?></td>
                        <td class="label-unit"><?= $item['unit'] ?></td>
                        <td class="label-price">Rp. <?= numerical($item['price']) ?></td>
                        <td class="label-discount">Rp. <?= numerical($item['discount']) ?></td>
                        <td class="label-total">Rp. <?= numerical($item['total']) ?></td>
                        <td class="label-description"><?= $item['description'] ?></td>
                        <td class="text-md-right">
                            <input type="hidden" name="taken_items[<?= $index ?>][id_requisition_item]" id="id_requisition_item" value="<?= $item['id_requisition_item'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][item_name]" id="item_name" value="<?= $item['item_name'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][brand]" id="brand" value="<?= $item['brand'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][unit]" id="unit" value="<?= $item['unit'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][total]" id="total" value="<?= $item['total'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][quantity]" id="quantity" value="<?= $item['quantity'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][price]" id="price" value="<?= $item['price'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][discount]" id="discount" value="<?= $item['discount'] ?>">
                            <input type="hidden" name="taken_items[<?= $index ?>][description]" id="description" value="<?= $item['description'] ?>">
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($takenItems)): ?>
                    <tr class="row-placeholder">
                        <td colspan="10">No taken items available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Offer Discount</h4>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="offer_discount_goods">Discount Goods</label>
                        <input type="text" class="form-control currency" id="offer_discount_goods" name="offer_discount_goods"
                               placeholder="Discount of all goods" value="<?= set_value('offer_discount_goods') ?>">
                        <?= form_error('offer_discount_goods') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="offer_discount_services">Discount Services</label>
                        <input type="text" class="form-control currency" id="offer_discount_services" name="offer_discount_services"
                               placeholder="Discount of all services" value="<?= set_value('offer_discount_services') ?>">
                        <?= form_error('offer_discount_services') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save Purchase Offer</button>
        </div>
    </div>
</form>

<?php $this->load->view('offer/_modal_take_item') ?>

<script>
    var _requisitionItems = <?= json_encode($requisitionItems) ?>;
</script>

