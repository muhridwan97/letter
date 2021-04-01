<div class="page-header">
    <h3 class="page-title">
        Search Result of <span class="text-primary">"<?= get_url_param('q') ?>"</span>
    </h3>
</div>

<?php if(!empty($requisitions)): ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Result of Requisition</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>No Requisition</th>
                        <th>Request Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Last Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($requisitions as $requisition): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $requisition['no_requisition']) ?></td>
                            <td>
                                <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>" class="text-primary">
                                    <?= str_ireplace($q, "<b>{$q}</b>", $requisition['request_title']) ?>
                                </a>
                            </td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", if_empty($requisition['category'], '-')) ?></td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $requisition['status']) ?></td>
                            <td><?= format_date($requisition['updated_at'], 'd F Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($purchaseOffers)): ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Result of Purchase Offers</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Offer Of</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Last Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($purchaseOffers as $offer): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <a href="<?= site_url('purchasing/offer/view/' . $offer['id']) ?>" class="text-primary">
                                    <?= str_ireplace($q, "<b>{$q}</b>", $offer['request_title']) ?>
                                </a>
                            </td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $offer['vendor']) ?></td>
                            <td>Rp. <?= numerical(str_ireplace($q, "<b>{$q}</b>", $offer['total_price'])) ?></td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $offer['status']) ?></td>
                            <td><?= format_date($offer['updated_at'], 'd F Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($purchaseOrders)): ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Result of Purchase Orders</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>No Order</th>
                        <th>Request Title</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($purchaseOrders as $order): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <a href="<?= site_url('purchasing/order/view/' . $order['id']) ?>" class="text-primary">
                                    <?= str_ireplace($q, "<b>{$q}</b>", $order['no_purchase']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>" class="text-primary">
                                    <?= str_ireplace($q, "<b>{$q}</b>", $requisition['request_title']) ?>
                                </a>
                            </td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $order['vendor']) ?></td>
                            <td>Rp. <?= numerical(str_ireplace($q, "<b>{$q}</b>", $order['total_price'])) ?></td>
                            <td><?= str_ireplace($q, "<b>{$q}</b>", $order['status']) ?></td>
                            <td><?= format_date($order['created_at'], 'd F Y') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if(empty($requisitions) && empty($purchaseOffers) && empty($purchaseOffers)): ?>
    <p>No result found.</p>
<?php endif; ?>
