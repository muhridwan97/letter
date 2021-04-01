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
                    </div>
					<div class="form-group">
						<label for="item">Item Name</label>
						<select class="form-control select2" name="item" id="item" style="width: 100%" required>
							<option value="0">All Item</option>
							<?php foreach ($items as $item): ?>
								<option value="<?= $item['id'] ?>">
									<?= $item['item_name'] ?><?= if_empty($item['brand'], '', ' - ', '', true) ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
                    <div class="form-row">
                        <div class="col-8 col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
                                    <?php
                                    $sortBy = [
                                        'request_date' => 'REQUEST DATE',
                                        'item_name' => 'ITEM NAME',
                                        'quantity' => 'QUANTITY',
                                        'unit_price' => 'PRICE',
                                        'vendor' => 'VENDOR',
                                        'offer_date' => 'OFFER DATE',
                                        'no_purchase' => 'NO PURCHASE',
                                        'order_date' => 'ORDER DATE',
                                    ];
                                    ?>
                                    <?php foreach ($sortBy as $key => $label): ?>
                                        <option value="<?= $key ?>"<?= set_select('sort_by', $key, get_url_param('sort_by') == $key) ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
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
                            </div>
                        </div>
                    </div>
					<div class="form-group">
						<label for="date_type">Date Type</label>
						<select class="custom-select" name="date_type" id="date_type" required>
							<option value="request_date"<?= set_select('date_type', 'request_date', get_url_param('order_method') == 'request_date') ?>>
								REQUEST DATE
							</option>
							<option value="offer_date"<?= set_select('date_type', 'offer_date', get_url_param('order_method') == 'offer_date') ?>>
								OFFER DATE
							</option>
							<option value="purchase_date"<?= set_select('date_type', 'purchase_date', get_url_param('order_method') == 'purchase_date') ?>>
								PURCHASE DATE
							</option>
						</select>
					</div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="text" class="form-control datepicker" name="date_from" id="date_from"
                                       value="<?= get_url_param('date_from') ?>" placeholder="Pick date from">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="text" class="form-control datepicker" name="date_to" id="date_to"
                                       value="<?= get_url_param('date_to') ?>" placeholder="Pick date to">
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
