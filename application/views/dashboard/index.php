<div class="page-header">
    <h3 class="page-title m-3 m-sm-0">
        <span class="page-title-icon bg-primary text-white mr-2">
            <i class="mdi mdi-home"></i>
        </span>
        Dashboard
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview
                <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<?php if(AuthorizationModel::hasPermission(PERMISSION_ORDER_MANAGE)): ?>
    <div class="row">
        <div class="col-md-4 d-md-flex d-xl-block align-content-stretch grid-margin">
            <div class="card mx-3 mx-sm-0 bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/dist/img/layouts/circle.svg') ?>"
                         class="card-img-absolute" alt="circle-image"/>
                    <h4 class="font-weight-normal mb-3">Purchase Total
                        <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                        Rp. <?= numerical(get_if_exist($allStats, 'total_price', 0) / 1000000, 2) ?>M
                    </h2>
                    <h6 class="card-text">From <?= numerical(get_if_exist($allStats, 'total_transaction', 0)) ?> transactions</h6>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-md-flex d-xl-block align-content-stretch grid-margin">
            <div class="card mx-3 mx-sm-0 bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/dist/img/layouts/circle.svg') ?>"
                         class="card-img-absolute" alt="circle-image"/>
                    <h4 class="font-weight-normal mb-3">Monthly Orders
                        <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                        Rp. <?= numerical(get_if_exist($currentMonthlyStats, 'total_price', 0) / 1000, 1) ?>K
                    </h2>
                    <h6 class="card-text">
                        <?= $currentMonthlyStats['total_price'] > $currentMonthlyStats['last_total_price'] ? 'Increase' : 'Decreased' ?>
                        by <?= numerical($currentMonthlyStats['diff_percent'], 2) ?>%
                    </h6>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-md-flex d-xl-block align-content-stretch grid-margin">
            <div class="card mx-3 mx-sm-0 bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="<?= base_url('assets/dist/img/layouts/circle.svg') ?>"
                         class="card-img-absolute" alt="circle-image"/>
                    <h4 class="font-weight-normal mb-3">Outstanding Request
                        <i class="mdi mdi-diamond mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">
                        <small>pending</small>
                        <?= numerical($requisitionStats['outstanding']) ?> / <?= numerical($requisitionStats['total']) ?>
                    </h2>
                    <h6 class="card-text"><?= numerical($requisitionStats['proceed']) ?> request is proceed</h6>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="card grid-margin">
    <div class="card-body">
        <h4 class="card-title">Recent Request</h4>
        <table class="table table-md responsive">
            <thead>
            <tr>
                <th>No</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Last Update</th>
                <th>Request ID</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $statusLabels = [
                RequisitionModel::STATUS_PENDING => 'secondary',
                RequisitionModel::STATUS_REJECTED => 'danger',
                RequisitionModel::STATUS_CANCELLED => 'danger',
                RequisitionModel::STATUS_APPROVED => 'success',
                RequisitionModel::STATUS_LISTED => 'dark',
                RequisitionModel::STATUS_READY => 'info',
                RequisitionModel::STATUS_SELECTED => 'primary',
                RequisitionModel::STATUS_IN_PROCESS => 'warning',
                RequisitionModel::STATUS_DONE => 'success',
            ];
            ?>
            <?php $no = 1; ?>
            <?php foreach ($requisitions['data'] as $requisition): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <a href="<?= site_url('requisition/view/' . $requisition['id']) ?>" class="text-primary">
                            <?= $requisition['request_title'] ?>
                        </a>
                    </td>
                    <td><?= if_empty($requisition['category'], '-') ?></td>
                    <td>
                        <span class="badge badge-<?= get_if_exist($statusLabels, $requisition['status'], 'secondary') ?>">
                            <?= $requisition['status'] ?>
                        </span>
                    </td>
                    <td><?= format_date($requisition['updated_at'], 'd F Y') ?></td>
                    <td><?= $requisition['no_requisition'] ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($requisitions['data'])): ?>
                <tr>
                    <td colspan="6">No recent request available</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
