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
                               value="<?= get_url_param('search') ?>" placeholder="Search data">
                        <?= form_error('search'); ?>
                    </div>
                    <div class="form-group">
                        <label for="check_type">Check Type</label>
                        <select class="custom-select" name="check_type" id="check_type">
                            <option value="0"<?= set_select('check_type', '0', get_url_param('check_type') == '0') ?>>
                                ALL
                            <option value="checked"<?= set_select('check_type', 'checked', get_url_param('check_type') == 'checked') ?>>
                                CHECKED
                            </option>
                            <option value="unchecked"<?= set_select('check_type', 'unchecked', get_url_param('check_type') == 'unchecked') ?>>
                                UNCHECKED
                            </option>
                        </select>
                        <?= form_error('check_type'); ?>
                    </div>
                    <div class="form-row">
                        <div class="col-8 col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <option value="created_at"<?= set_select('sort_by', 'created_at', get_url_param('sort_by') == 'created_at') ?>>
                                        CREATED AT
                                    </option>
                                    <option value="employee_name"<?= set_select('sort_by', 'employee_name', get_url_param('sort_by') == 'employee_name') ?>>
                                        REQUESTER
                                    </option>
                                    <option value="no_requisition"<?= set_select('sort_by', 'no_requisition', get_url_param('sort_by') == 'no_requisition') ?>>
                                        NO REQUISITION
                                    </option>
                                    <option value="category"<?= set_select('sort_by', 'category', get_url_param('sort_by') == 'category') ?>>
                                        CATEGORY
                                    </option>
                                    <option value="type"<?= set_select('sort_by', 'type', get_url_param('sort_by') == 'type') ?>>
                                        TYPE
                                    </option>
                                    <option value="deadline"<?= set_select('sort_by', 'deadline', get_url_param('sort_by') == 'deadline') ?>>
                                        DEADLINE
                                    </option>
                                    <option value="status"<?= set_select('sort_by', 'status', get_url_param('sort_by') == 'status') ?>>
                                        STATUS
                                    </option>
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
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="text" class="form-control datepicker" name="date_from" id="date_from" autocomplete="off"
                                       value="<?= get_url_param('date_from') ?>" placeholder="Pick create date from">
                                <?= form_error('date_from'); ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="text" class="form-control datepicker" name="date_to" id="date_to" autocomplete="off"
                                       value="<?= get_url_param('date_to') ?>" placeholder="Pick create date to">
                                <?= form_error('date_to'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= site_url('requisition') ?>" class="btn btn-sm btn-secondary">
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