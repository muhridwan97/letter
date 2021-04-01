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
                    <div class="form-row">
                        <div class="col-8 col-sm-6">
                            <div class="form-group">
                                <label for="year">Year</label>
                                <select class="custom-select" name="year" id="year" required>
									<?php for ($y = date('Y'); $y >= 2018; $y--): ?>
										<option value="<?= $y ?>" <?= get_url_param('year') == $y ? 'selected' : '' ?>>
											<?= $y ?>
										</option>
									<?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4 col-sm-6">
                            <div class="form-group">
                                <label for="month">Month</label>
                                <select class="custom-select" name="month" id="month" required>
									<option value="0">ALL MONTHS</option>
									<?php foreach (get_months() as $index => $month): ?>
										<option value="<?= $index + 1 ?>" <?= get_url_param('month') == $index + 1 ? 'selected' : '' ?>>
											<?= $month ?>
										</option>
									<?php endforeach; ?>
                                </select>
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
