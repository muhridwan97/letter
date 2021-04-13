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
					<div class="form-row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="curriculum">Curriculum</label>
								<select class="form-control select2" name="curriculum" id="curriculum" style="width: 100%">
									<option value="0">All Curriculum</option>
									<?php foreach ($curriculums as $curriculum): ?>
										<option value="<?= $curriculum['id'] ?>"<?= set_select('curriculum', $curriculum['id'], get_url_param('curriculum') == $curriculum['id']) ?>>
											<?= $curriculum['curriculum_title'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label for="course">Courses</label>
								<select class="form-control select2" name="course" id="course" style="width: 100%">
									<option value="0">All Course</option>
									<?php foreach ($courses as $course): ?>
										<option value="<?= $course['id'] ?>" data-curriculum="<?= $course['id_curriculum'] ?>"<?= set_select('course', $course['id'], get_url_param('course') == $course['id']) ?>>
											<?= $course['course_title'] ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
                    <div class="form-row">
						<div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort_by">Sort By</label>
                                <select class="custom-select" name="sort_by" id="sort_by" required>
									<?php
									$filterSort = [
										'created_at' => 'Created At',
										'lesson_title' => 'Lesson Title',
										'course_title' => 'Course Title',
										'curriculum_title' => 'Curriculum Title',
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