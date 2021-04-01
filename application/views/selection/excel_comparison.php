<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title">Comparison Vendor</h4>
                <p class="text-muted">Comparison vendor who take requisition order</p>
            </div>
            <div>
            </div>
        </div>
        <form class="form-plaintext">
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
                                <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>">
                                    <?= $requisition['request_title'] ?>
                                </a>
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
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="requester">Requester</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="requester">
                                <?= $requisition['employee_name'] ?> (<?= if_empty($requisition['supervisor_name'], 'No SPV', 'SPV ') ?>)
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="urgency">Urgency</label>
                        <div class="col-sm-9">
                            <?php
                            $statusLabels = [
                                'LOW' => 'success',
                                'NORMAL' => 'primary',
                                'HIGH' => 'warning',
                                'VERY HIGH' => 'danger',
                            ]
                            ?>
                            <p class="form-control-plaintext" id="urgency">
                                <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['urgency'], 'secondary') ?>">
                                    <?= $requisition['urgency'] ?>
                                </span>
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
            </div>
        </form>
    </div>
</div>


<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Request Items</h4>
        <table class="table table-sm responsive">
            <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Brand</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Selected</th>
                <th>Unit</th>
                <th>Description</th>
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
                    <td><?= numerical($item['quantity']) ?></td>
                    <td><?= numerical($item['total_selected']) ?></td>
                    <td><?= if_empty($item['unit'], '-') ?></td>
                    <td><?= if_empty($item['description'], '-') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title">Comparison Items</h4>
            </div>
            <div>
                <a href="<?= site_url('purchasing/selection/add-comparison/' . $requisition['id']) ?>" class="btn btn-primary btn-sm" title="Add Comparison">
                    <i class="mdi mdi-plus-box-outline"></i>
                </a>
            </div>
        </div>
        <table class="table table-sm responsive" id="table-add-comparison">
            <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <?php $tempComparisonItem = []; ?>
                <?php foreach ($purchaseOffers as $offer): ?>
                <th><?= if_empty($offer['vendor'], '-') ?></th>
                <?php endforeach; ?>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $no = 1 ?>
            <?php foreach ($comparisonItems as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= if_empty($item['comparison_item'], '-') ?></td>
                    <?php foreach ($purchaseOffers as $offer): ?>
                        <?php $hasItem = false; ?>
                        <?php foreach ($offer['comparison_items'] as $comp): ?>
                            <?php if($comp['comparison_item'] == $item['comparison_item']): ?>
                                <?php $hasItem = true; ?>
                                <td><?= if_empty($comp['description'], '-') ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasItem): ?>
                            <td>-</td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td>
                    <a href="<?= site_url('purchasing/selection/edit-comparison/' . $requisition['id']).'/'.$item['comparison_item'] ?>" class="btn btn-info btn-sm" title="Edit Comparison">
                        <i class="mdi mdi-square-edit-outline"></i>
                    </a>
                    <a href="<?= site_url('purchasing/selection/delete-comparison/' . $requisition['id']).'/'.$item['comparison_item'] ?>" class="btn btn-danger btn-sm btn-delete"
                        data-request-title="<?=$item['comparison_item']?>" title="Delete Comparison">
                        <i class="mdi mdi-trash-can-outline"></i>
                    </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($comparisonItems)): ?>
                <tr>
                    <td colspan="4">
                        No Comparison Item Available
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <div>
                <h4 class="card-title">Comparison Vendor</h4>
            </div>
            <div>
                <a href="<?= site_url('purchasing/selection/print-comparison/' . $requisition['id'].'?&amp;export=true') ?>" class="btn btn-info btn-sm pr-2 pl-2" title="Export Excel">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
                <a href="<?= site_url('purchasing/selection/print-comparison/' . $requisition['id']) ?>" class="btn btn-success btn-sm" title="Print Comparison">
                    <i class="mdi mdi-printer"></i> Print Comparison
                </a>
            </div>
        </div>
        <table class="table table-sm responsive" id="table-all-comparison">
            <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <?php $tempComparisonItem = []; ?>
                <?php foreach ($purchaseOffers as $offer): ?>
                <th><?= if_empty($offer['vendor'], '-') ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php $no = 1; ?>
            <?php foreach ($requisitionItems as $itemRequest): ?>

                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <span class="d-block"><?= $itemRequest['item_name'] . ' ' . if_empty($itemRequest['brand'], '', '', '', true) ?></span>
                        <small class="text-muted"><?= if_empty($itemRequest['description']) ?></small>
                    </td>
                    <?php foreach ($purchaseOffers as $offer): ?>
                        <?php $hasItem = false; ?>
                        <?php foreach ($offer['items'] as $item): ?>
                            <?php if ($item['id_requisition_item'] == $itemRequest['id']): ?>
                                <?php $hasItem = true; ?>
                                <td>Rp. <?= numerical($item['price']).' / '. $item['unit']. ' ('.$item['quantity'].')' ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasItem): ?>
                            <td>-</td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>

            <?php endforeach; ?>
            <?php foreach ($comparisonItems as $item): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= if_empty($item['comparison_item'], '-') ?></td>
                    <?php foreach ($purchaseOffers as $offer): ?>
                        <?php $hasItem = false; ?>
                        <?php foreach ($offer['comparison_items'] as $comp): ?>
                            <?php if($comp['comparison_item'] == $item['comparison_item']): ?>
                                <?php $hasItem = true; ?>
                                <td><?= if_empty($comp['description'], '-') ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (!$hasItem): ?>
                            <td>-</td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card grid-margin">
    <div class="card-body d-flex justify-content-between">
        <button onclick="history.back()" type="button" class="btn btn-light px-3 px-sm-4">Back</button>
    </div>
</div>

<form action="#" method="post" id="form-selection">
    <?= _csrf() ?>
    <?= _method('put') ?>
</form>

<?php $this->load->view('partials/modals/_delete') ?>
