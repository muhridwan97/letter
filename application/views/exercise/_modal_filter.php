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
                    </div>
					<div class="form-group d-none">
						<label for="type">Type</label>
						<select class="custom-select" name="type" id="type">
							<?php
							$filterTypes = [
								ExerciseModel::TYPE_CURRICULUM_EXAM => 'Exam',
								ExerciseModel::TYPE_LESSON_EXERCISE => 'Exercise',
							];
							?>
							<option value="">All Type</option>
							<?php foreach ($filterTypes as $typeKey => $filterType): ?>
								<option value="<?= $typeKey ?>"<?= set_select('type', $typeKey, get_url_param('type') == $typeKey) ?>>
									<?= $filterType ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="category">Category</label>
						<select class="custom-select" name="category" id="category">
							<?php
							$filterCategories = [
								ExerciseModel::CATEGORY_CHOICES,
								ExerciseModel::CATEGORY_ESSAY,
								ExerciseModel::CATEGORY_PRACTICE,
							];
							?>
							<option value="">All Category</option>
							<?php foreach ($filterCategories as $filterCategory): ?>
								<option value="<?= $filterCategory ?>"<?= set_select('category', $filterCategory, get_url_param('category') == $filterCategory) ?>>
									<?= $filterCategory ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
                    <div class="form-row">
						<div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
									<?php
									$filterSort = [
										'created_at' => 'Created At',
										'exercise_title' => 'Exercise Title',
										'category' => 'Category',
										'total_questions' => 'Total Question',
										'description' => 'Description',
									];
									?>
									<?php foreach ($filterSort as $key => $value): ?>
										<option value="<?= $key ?>"<?= set_select('sort_by', $key, get_url_param('sort_by') == $key) ?>>
											<?= $value ?>
										</option>
									<?php endforeach; ?>
                                </select>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group">
                                <label for="order_method">Order</label>
                                <select class="custom-select" name="order_method" id="order_method" required>
                                    <option value="desc"
                                        <?= set_select('order_method', 'desc', get_url_param('order_method') == 'desc') ?>>
                                        Descending
                                    </option>
                                    <option value="asc"
                                        <?= set_select('order_method', 'asc', get_url_param('order_method') == 'asc') ?>>
                                        Ascending
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_from">Date From</label>
                                <input type="text" class="form-control datepicker" name="date_from" id="date_from"
                                       value="<?= get_url_param('date_from') ?>" placeholder="Pick create date from">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="date_to">Date To</label>
                                <input type="text" class="form-control datepicker" name="date_to" id="date_to"
                                       value="<?= get_url_param('date_to') ?>" placeholder="Pick create date to">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="<?= site_url(uri_string()) ?>" class="btn btn-sm btn-light">
                        RESET
                    </a>
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">
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
