<form action="<?= site_url('master/vendor/save-offer/' . (empty($vendor) ? '' : $vendor['id'])) ?>" method="POST" id="form-vendor-offer">
    <?= _csrf() ?>

    <div class="form-plaintext">
        <div class="card grid-margin">
            <div class="card-body">
                <h4 class="card-title">View Vendor</h4>
                <?php if(empty($vendor)): ?>
                    <div class="form-group">
                        <label for="vendor">Vendor</label>
                        <select class="form-control select2" name="vendor" id="vendor" data-placeholder="Select vendor" required>
                            <option value="">-- Select Vendor --</option>
                            <?php foreach ($vendors as $vendorData): ?>
                                <option value="<?= $vendorData['id'] ?>"<?= set_select('vendor', $vendorData['id']) ?>>
                                    <?= $vendorData['vendor'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('vendor') ?>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="vendor">Name</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="vendor">
                                        <?= $vendor['vendor'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="city">City</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="city">
                                        <?= if_empty($vendor['city'], 'No city') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="rating">Rating</label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="rating">
                                        <?php $totalRating = round(if_empty($vendor['average_rating'], 0)) ?>
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <?php if ($i < $totalRating): ?>
                                                <i class="mdi mdi-star"></i>
                                            <?php else: ?>
                                                <i class="mdi mdi-star-outline"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        (<?= numerical($vendor['average_rating'], 1) ?>)
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-4 col-form-label" for="contact_person">Contact Name</label>
                                <div class="col-sm-9 col-md-8">
                                    <p class="form-control-plaintext" id="contact_person">
                                        <?= if_empty($vendor['contact_person'], 'No contact person') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-4 col-form-label" for="contact">Contact</label>
                                <div class="col-sm-9 col-md-8">
                                    <p class="form-control-plaintext" id="contact">
                                        <?= if_empty($vendor['contact'], 'No contact') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-md-4 col-form-label" for="email">Email</label>
                                <div class="col-sm-9 col-md-8">
                                    <p class="form-control-plaintext" id="email">
                                        <?= if_empty($vendor['email'], 'No email') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Vendor Offers</h4>
            <?= form_error('items[]') ?>

            <table class="table" id="table-item">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Date Price</th>
                    <th>Date Until</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $items = set_value('items', []); ?>
                <?php foreach ($items as $index => $item): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td class="label-item"><?= $item['item_name'] ?></td>
                        <td class="label-brand"><?= $item['brand'] ?></td>
                        <td class="label-price">Rp. <?= numerical($item['price']) ?></td>
                        <td class="label-price">Rp. <?= numerical($item['discount']) ?></td>
                        <td class="label-date-price"><?= format_date($item['date_price'], 'd F Y') ?></td>
                        <td class="label-date-until"><?= format_date($item['date_until'], 'd F Y') ?></td>
                        <td class="label-description"><?= $item['description'] ?></td>
                        <td class="text-right">
                            <input type="hidden" name="items[<?= $index ?>][item_name]" id="item_name" value="<?= $item['item_name'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][brand]" id="brand" value="<?= $item['brand'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][quantity]" id="quantity" value="<?= $item['price'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][quantity]" id="quantity" value="<?= $item['discount'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][date_price]" id="unit" value="<?= $item['date_price'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][date_until]" id="unit" value="<?= $item['date_until'] ?>">
                            <input type="hidden" name="items[<?= $index ?>][description]" id="description" value="<?= $item['description'] ?>">
                            <button class="btn btn-sm btn-outline-info btn-edit" type="button">
                                <i class="mdi mdi-square-edit-outline"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger btn-delete" type="button">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($items)): ?>
                    <tr class="row-placeholder">
                        <td colspan="9">No items available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-sm btn-info" id="btn-add-item" type="button">ADD ITEM OFFER</button>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">Save Vendor Offer</button>
        </div>
    </div>
</form>

<?php $this->load->view('vendor/_modal_form_offer') ?>