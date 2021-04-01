<div class="modal fade" id="modal-filter" aria-labelledby="modalFilter">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?= site_url(uri_string()) ?>" id="form-filter">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFilter">Filter <?= $title ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" name="search" id="search"
                               value="<?= get_url_param('search') ?>" placeholder="Search a data">
                        <?= form_error('search'); ?>
                    </div>
                    <div class="form-row">
                        <div class="col-8 col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <?php
                                    $sortBy = [
                                        'request_date' => 'REQUEST DATE',
                                        'no_requisition' => 'NO REQUISITION',
                                        'deadline' => 'DEADLINE DATE',
                                        'vendor' => 'VENDOR',
                                        'no_purchase' => 'PO NO.',
                                        'purchase_date' => 'PO DATE',
                                        'vendor_invoice' => 'VENDOR INVOICE NO.',
                                        'vendor_invoice_date' => 'VENDOR INVOICE DATE',
                                        'no_handover' => 'FINANCE RECEIPT NO.',
                                        'handover_date' => 'FINANCE RECEIPT DATE',
                                    ];
                                    ?>
                                    <?php foreach ($sortBy as $key => $label): ?>
                                        <option value="<?= $key ?>"<?= set_select('sort_by', $key, get_url_param('sort_by') == $key) ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?= form_error('sort_by'); ?>
                            </div>
                        </div>
                        <div class="col-4 col-sm-6">
                            <div class="form-group">
                                <label for="order_method">Order</label>
                                <select class="custom-select" name="order_method" id="order_method" required>
                                    <option value="desc"
                                        <?= set_select('order_method', 'desc', get_url_param('order_method') == 'desc') ?>>
                                        DESCENDING
                                    </option>
                                    <option value="asc"
                                        <?= set_select('order_method', 'asc', get_url_param('order_method') == 'asc') ?>>
                                        ASCENDING
                                    </option>
                                </select>
                                <?= form_error('order_method'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_by">Date Filter</label>
                        <select class="custom-select" name="date_by" id="date_by" required>
                            <?php
                            $dateBy = [
                                'request_date' => 'REQUEST DATE',
                                'deadline' => 'DEADLINE DATE',
                                'purchase_date' => 'PO DATE',
                                'vendor_invoice_date' => 'VENDOR INVOICE DATE',
                                'handover_date' => 'FINANCE RECEIPT DATE',
                            ];
                            ?>
                            <?php foreach ($dateBy as $key => $label): ?>
                                <option value="<?= $key ?>"<?= set_select('date_by', $key, get_url_param('date_by') == $key) ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('date_by'); ?>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="text" class="form-control datepicker" name="date_from" id="date_from"
                                       value="<?= get_url_param('date_from') ?>" placeholder="Pick create date from">
                                <?= form_error('date_from'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="text" class="form-control datepicker" name="date_to" id="date_to"
                                       value="<?= get_url_param('date_to') ?>" placeholder="Pick create date to">
                                <?= form_error('date_to'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= site_url(uri_string()) ?>" class="btn btn-sm btn-secondary">
                        RESET
                    </a>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                        CLOSE
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        APPLY FILTER
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
