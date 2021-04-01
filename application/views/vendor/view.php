<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Vendor</h4>
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
                    <?php if(empty($vendorLocations)): ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="city">City</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="city">
                                <?= if_empty($vendor['city'], 'No city') ?> (Unknown province)
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="office_phone">Office Phone</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="office_phone">
                                No office phone
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="address">Address</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="address">
                                <?= if_empty($vendor['address'], 'No address') ?>
                            </p>
                        </div>
                    </div>
                    <?php else : ?>
                        <?php foreach ($vendorLocations as $index => $vendorLocation) : ?>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="city">City <?= $index+1 ?></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="city">
                                        <?= if_empty($vendorLocation['city'], 'No city') ?> (<?= if_empty($vendorLocation['province'], 'Unknown province') ?>)
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="office_phone">Phone <?= $index+1 ?></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="office_phone">
                                        <?= if_empty($vendorLocation['office_phone'], 'No office phone') ?>
                                    </p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label" for="address">Address <?= $index+1 ?></label>
                                <div class="col-sm-9">
                                    <p class="form-control-plaintext" id="address">
                                        <?= if_empty($vendorLocation['address'], 'No address') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="offer">Offers</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="offer">
                                <?= numerical($vendor['total_offer']) ?>x offered
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="selected">Selected</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="selected">
                                <?= numerical($vendor['total_selected']) ?>x selected
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="rating">Rating</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="rating">
                            <a href="<?= site_url('report/satisfied-detail?status=COMPLETED&vendor='.$vendor['vendor'])?>" target="_blank">
                                <?php $totalRating = round(if_empty($vendor['average_rating'], 0)) ?>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <?php if ($i < $totalRating): ?>
                                        <i class="mdi mdi-star"></i>
                                    <?php else: ?>
                                        <i class="mdi mdi-star-outline"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                (<?= numerical($vendor['average_rating'], 1) ?>)
                            </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="support">Support City</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="support">
                                <?= if_empty($vendor['support_cities'],"Not defined") ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="is_pkp">Is PKP</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="is_pkp">
                                <?= $vendor['is_pkp'] ? 'Yes' : 'No' ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="business">Business</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="business">
                                <?= if_empty($vendor['business'],'No Business') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="is_owned">Is Owned</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="is_owned">
                                <?= $vendor['is_owned'] ? 'Yes' : 'No' ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="has_tax_number">Has Tax</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="has_tax_number">
                                <?= $vendor['has_tax_number'] ? 'Yes' : 'No' ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="tax_number">Tax Number</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="tax_number">
                                <?= if_empty($vendor['tax_number'], '-') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="tax_file">Tax File</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="tax_file">
                                <?php if(empty($vendor['tax_file'])): ?>
                                    No file
                                <?php else: ?>
                                    <a href="<?= base_url('uploads/' . $vendor['tax_file']) ?>">
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($vendor['description'], 'No description') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="facilities">Facilities</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="facilities">
                                <?= if_empty($vendor['facilities'], 'No facilities') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($vendor['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($vendor['updated_at'], 'd F Y H:i'),  '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Categories</h4>
            <table class="table responsive" id="table-category">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Category</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($vendorCategories as $category): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($category['category'], '-') ?></td>
                        <td><?= if_empty($category['description'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Item Categories</h4>
            <table class="table responsive" id="table-item-category">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Category</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($vendorItemCategories as $itemCategory): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($itemCategory['item_name'], '-') ?></td>
                        <td><?= if_empty($itemCategory['description'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Contacts</h4>
            <table class="table responsive" id="table-contact">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Type</th>
                    <th>Title</th>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Primary</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($vendorContacts as $contact): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($contact['type'], '-') ?></td>
                        <td><?= if_empty($contact['title'], '-') ?></td>
                        <td><?= if_empty($contact['name'], '-') ?></td>
                        <td><?= if_empty($contact['position'], '-') ?></td>
                        <td><?= if_empty($contact['contact'], '-') ?></td>
                        <td><?= if_empty($contact['email'], '-') ?></td>
                        <td><?= $contact['is_primary'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Banks</h4>
            <table class="table responsive" id="table-bank">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Bank</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Primary</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($vendorBanks as $bank): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($bank['bank'], '-') ?></td>
                        <td><?= if_empty($bank['account_name'], '-') ?></td>
                        <td><?= if_empty($bank['account_number'], '-') ?></td>
                        <td><?= $bank['is_primary'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($vendorBanks)): ?>
                    <tr>
                        <td colspan="5">No bank available.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h4 class="card-title">Price Offers</h4>
                <a href="<?= site_url('master/vendor/add-offer/' . $vendor['id']) ?>" class="btn btn-sm btn-success">
                    Add Offers
                </a>
            </div>
            <table class="table responsive" id="table-price">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Item Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Price Date</th>
                    <th>Price Until</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1 ?>
                <?php foreach ($vendorOffers as $vendorOffer): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= if_empty($vendorOffer['item_name'], '-') ?></td>
                        <td><?= if_empty($vendorOffer['brand'], '-') ?></td>
                        <td>Rp. <?= numerical($vendorOffer['price']) ?></td>
                        <td>Rp. <?= numerical($vendorOffer['discount']) ?></td>
                        <td><?= format_date($vendorOffer['price_date'], 'd F Y') ?></td>
                        <td><?= if_empty(format_date($vendorOffer['price_until'], 'd F Y'), '-') ?></td>
                        <td><?= if_empty($vendorOffer['description'], '-') ?></td>
                        <td>
                            <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_DELETE)): ?>
                                <a href="#modal-delete" class="btn btn-sm btn-danger btn-delete" data-toggle="modal"
                                   data-id="<?= $vendorOffer['id'] ?>" data-label="<?= $vendorOffer['item_name'] ?>" data-title="Vendor Offer"
                                   data-url="<?= site_url('master/vendor/delete-offer/' . $vendorOffer['id']) ?>">
                                    <i class="mdi mdi-trash-can-outline"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($vendorOffers)): ?>
                    <tr>
                        <td colspan="8">No offer available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_EDIT)): ?>
                <a href="<?= site_url('master/vendor/edit/' . $vendor['id']) ?>" class="btn btn-primary">
                    Edit Vendor
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>