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
                        <label for="q">Search</label>
                        <input type="text" class="form-control" name="q" id="q"
                               value="<?= get_url_param('q') ?>" placeholder="Search a data">
                        <?= form_error('q'); ?>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <option value="created_at"<?= set_select('sort_by', 'created_at', get_url_param('sort_by') == 'created_at') ?>>
                                        CREATED AT
                                    </option>
                                    <option value="no_purchase"<?= set_select('sort_by', 'no_purchase', get_url_param('sort_by') == 'no_purchase') ?>>
                                        NO PURCHASE
                                    </option>
                                    <option value="request_title"<?= set_select('sort_by', 'request_title', get_url_param('sort_by') == 'request_title') ?>>
                                        REQUISITION
                                    </option>
                                    <option value="vendor"<?= set_select('sort_by', 'vendor', get_url_param('sort_by') == 'vendor') ?>>
                                        VENDOR
                                    </option>
                                    <option value="date"<?= set_select('sort_by', 'date', get_url_param('sort_by') == 'date') ?>>
                                        DATE
                                    </option>
                                    <option value="total_price"<?= set_select('sort_by', 'total_price', get_url_param('sort_by') == 'total_price') ?>>
                                        TOTAL PRICE
                                    </option>
                                </select>
                                <?= form_error('sort_by'); ?>
                            </div>
                        </div>
						<div class="col-sm-6">
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
                    <a href="<?= site_url('purchasing/order') ?>" class="btn btn-sm btn-secondary">
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
