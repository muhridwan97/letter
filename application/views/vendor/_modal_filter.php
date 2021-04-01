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
                               value="<?= get_url_param('search') ?>" placeholder="Search data including vendor categories">
                        <?= form_error('search'); ?>
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <select class="custom-select select2" name="cities[]" id="city" multiple style="width: 100%;">
                            <option value="">ALL CITY</option>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= $city ?>" <?= set_select('cities[]', $city, isset($_GET['cities'])?in_array($city, get_url_param('cities')):false ) ?>>
                                    <?= $city ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="province">Province</label>
                        <select class="custom-select select2" name="provinces[]" id="province" multiple style="width: 100%;">
                            <option value="">ALL PROVINCE</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province ?>" <?= set_select('provinces[]', $province, isset($_GET['provinces'])?in_array($province,get_url_param('provinces')):false ) ?>>
                                    <?= $province ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="custom-select select2" name="categories[]" id="category" multiple style="width: 100%;">
                            <option value="">ALL Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category ?>" <?= set_select('categories[]', $category, isset($_GET['categories'])?in_array($category,get_url_param('categories')):false ) ?>>
                                    <?= $category ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item_category">Item Category</label>
                        <select class="custom-select select2" name="item_categories[]" id="item_category" multiple style="width: 100%;">
                            <option value="">ALL Item Category</option>
                            <?php foreach ($item_categories as $item_category): ?>
                                <option value="<?= $item_category ?>" <?= set_select('item_categories[]', $item_category, isset($_GET['item_categories'])?in_array($item_category,get_url_param('item_categories')):false ) ?>>
                                    <?= $item_category ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="rating">Rating</label>
                                <select class="custom-select" name="rating" id="rating">
                                    <option value="">ALL STARS</option>
                                    <option value="0"<?= set_select('rating', 0, isset($_GET['rating']) && $_GET['rating'] === '0') ?>>NOT RATED</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>"<?= set_select('rating', $i, get_url_param('rating') == $i) ?>>
                                            <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="activity">Activity Status</label>
                                <select class="custom-select" name="activity" id="activity"<?= $this->uri->segment(2) == 'vendor-active' ? ' disabled' : '' ?>>
                                    <option value="">ALL ACTIVITY</option>
                                    <option value="ACTIVE"<?= set_select('activity', 'ACTIVE', get_url_param('activity') == 'ACTIVE' || $this->uri->segment(2) == 'vendor-active') ?>>
                                        ACTIVE (selected at least once)
                                    </option>
                                    <option value="INACTIVE"<?= set_select('activity', 'INACTIVE', get_url_param('activity') == 'INACTIVE') ?>>
                                        INACTIVE (never selected)
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-8 col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <option value="created_at"<?= set_select('sort_by', 'created_at', get_url_param('sort_by') == 'created_at') ?>>
                                        CREATED AT
                                    </option>
                                    <option value="vendor"<?= set_select('sort_by', 'vendor', get_url_param('sort_by') == 'vendor') ?>>
                                        VENDOR
                                    </option>
                                    <option value="contact"<?= set_select('sort_by', 'contact', get_url_param('sort_by') == 'contact') ?>>
                                        CONTACT
                                    </option>
                                    <option value="email"<?= set_select('sort_by', 'email', get_url_param('sort_by') == 'email') ?>>
                                        EMAIL
                                    </option>
                                    <option value="total_offer"<?= set_select('sort_by', 'total_offer', get_url_param('sort_by') == 'total_offer') ?>>
                                        TOTAL OFFERED
                                    </option>
                                    <option value="total_selected"<?= set_select('sort_by', 'total_selected', get_url_param('sort_by') == 'total_selected') ?>>
                                        TOTAL SELECTED
                                    </option>
                                    <option value="rating"<?= set_select('sort_by', 'rating', get_url_param('sort_by') == 'rating') ?>>
                                        TOTAL RATING
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
                    <a href="<?= site_url('master/vendor') ?>" class="btn btn-sm btn-secondary">
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
