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
                               value="<?= get_url_param('search') ?>" placeholder="Search data including related employee">
                        <?= form_error('search'); ?>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="custom-select" name="role" id="role">
                            <option value="">ALL ROLE</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>"<?= set_select('role', 'role', get_url_param('role') == $role['id']) ?>>
                                    <?= $role['role'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?= form_error('role'); ?>
                    </div>
                    <div class="form-row">
                        <div class="col-8 col-sm-4">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <option value="created_at"<?= set_select('sort_by', 'created_at', get_url_param('sort_by') == 'created_at') ?>>
                                        CREATED AT
                                    </option>
                                    <option value="name"<?= set_select('sort_by', 'name', get_url_param('sort_by') == 'name') ?>>
                                        NAME
                                    </option>
                                    <option value="username"<?= set_select('sort_by', 'username', get_url_param('sort_by') == 'username') ?>>
                                        USERNAME
                                    </option>
                                    <option value="email"<?= set_select('sort_by', 'email', get_url_param('sort_by') == 'email') ?>>
                                        EMAIL
                                    </option>
                                    <option value="status"<?= set_select('sort_by', 'status', get_url_param('sort_by') == 'status') ?>>
                                        STATUS
                                    </option>
                                    <option value="role"<?= set_select('sort_by', 'role', get_url_param('sort_by') == 'role') ?>>
                                        ROLE
                                    </option>
                                </select>
                                <?= form_error('sort_by'); ?>
                            </div>
                        </div>
                        <div class="col-4 col-sm-4">
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
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="custom-select" name="status" id="status">
                                    <option value="">ALL STATUS</option>
                                    <option value="PENDING"
                                        <?= set_select('status', 'PENDING', get_url_param('status') == 'PENDING') ?>>
                                        PENDING
                                    </option>
                                    <option value="ACTIVATED"
                                        <?= set_select('status', 'ACTIVATED', get_url_param('status') == 'ACTIVATED') ?>>
                                        ACTIVATED
                                    </option>
                                    <option value="SUSPENDED"
                                        <?= set_select('status', 'SUSPENDED', get_url_param('status') == 'SUSPENDED') ?>>
                                        SUSPENDED
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
                    <a href="<?= site_url('master/user') ?>" class="btn btn-sm btn-secondary">
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