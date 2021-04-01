<div class="card">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h4 class="card-title mb-sm-0">Data Vendor Offers</h4>
            <div>
                <a href="#modal-filter" data-toggle="modal" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-filter-variant mr-0"></i>
                </a>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline mr-0"></i>
                </a>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_CREATE)): ?>
                    <a href="<?= site_url('master/vendor/add-offer') ?>" class="btn btn-sm btn-success">
                        <i class="mdi mdi-plus-box-outline mr-2"></i>Create
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <table class="table table-hover table-sm mt-3 responsive">
            <thead>
            <tr>
                <th class="text-md-center" style="width: 60px">No</th>
                <th>Vendor</th>
                <th>Item Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Price Date</th>
                <th>Price Until</th>
                <th style="min-width: 120px" class="text-md-right">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = isset($vendorOffers) ? ($vendorOffers['current_page'] - 1) * $vendorOffers['per_page'] : 0 ?>
            <?php foreach ($vendorOffers['data'] as $vendorOffer): ?>
                <tr>
                    <td class="text-md-center"><?= ++$no ?></td>
                    <td>
                        <a href="<?= site_url('master/vendor/view/' . $vendorOffer['id_vendor']) ?>">
                            <?= $vendorOffer['vendor'] ?>
                        </a>
                    </td>
                    <td><?= if_empty($vendorOffer['item_name'], '-') ?></td>
                    <td><?= if_empty($vendorOffer['brand'], '-') ?></td>
                    <td>Rp. <?= numerical($vendorOffer['price']) ?></td>
                    <td>Rp. <?= numerical($vendorOffer['discount']) ?></td>
                    <td><?= format_date($vendorOffer['price_date'], 'd F Y') ?></td>
                    <td><?= if_empty(format_date($vendorOffer['price_until'], 'd F Y'), '-') ?></td>
                    <td class="text-md-right">
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
            <?php if(empty($vendorOffers['data'])): ?>
                <tr>
                    <td colspan="9">No vendors data available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <?php $this->load->view('partials/_pagination', ['pagination' => $vendorOffers]) ?>
    </div>
</div>

<?php $this->load->view('vendor/_modal_filter_offer') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>