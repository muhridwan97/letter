<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Course</h5>
            <div>
				<div class="btn-group">
					<?php if(!empty($_GET)): ?>
						<a href="<?= site_url(uri_string()) ?>" class="btn btn-danger btn-sm px-2" data-toggle="tooltip" data-title="Reset Filter">
							<i class="mdi mdi-close"></i>
						</a>
					<?php endif; ?>
					<a href="#modal-filter" data-toggle="modal" class="btn <?= !empty($_GET) ? 'btn-danger active' : 'btn-info' ?> btn-sm pr-2 pl-2">
						<i class="mdi <?= !empty($_GET) ? '' : 'mdi-filter-variant' ?>"></i><?= !empty($_GET) ? ' Filtered' : '' ?>
					</a>
				</div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_CREATE)): ?>
					<a href="<?= site_url('syllabus/course/create') ?>" class="btn btn-sm btn-primary">
						<i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
					</a>
				<?php endif; ?>
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive position-relative">
			<thead>
			<tr class="toggle-row" data-button="toggle-course">
				<th class="text-md-center" style="width: 80px">
					<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block btn-toggle-expand-all btn-toggle-auto-hide state-collapse"
							style="left: 5px" data-target="toggle-course">
						<i class="mdi mdi-plus" style="margin: 0"></i>
					</button>
					<span class="ml-md-2">No</span>
				</th>
				<th>Course</th>
				<th>Lesson</th>
				<th>Last Updated</th>
				<th style="width: 90px">Status</th>
				<th style="width: 100px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$courseStatuses = [
					CourseModel::STATUS_ACTIVE => 'success',
					CourseModel::STATUS_INACTIVE => 'danger',
				];
			?>
			<?php $no = isset($courses) ? ($courses['current_page'] - 1) * $courses['per_page'] : 0 ?>
			<?php foreach ($courses['data'] as $course): ?>
				<tr class="toggle-row" data-button="course-<?= $course['id'] ?>">
					<td class="text-md-center">
						<?php if (!empty($course['lessons'])): ?>
							<button type="button" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block toggle-course btn-toggle-expand btn-toggle-auto-hide state-collapse"
									style="left: 5px" data-target="course-<?= $course['id'] ?>" data-id="course-<?= $course['id'] ?>">
								<i class="mdi mdi-plus" style="margin: 0"></i>
							</button>
						<?php endif; ?>
						<span class="ml-md-1"><?= ++$no ?></span>
					</td>
					<td style="max-width: 350px">
						<div class="d-flex align-items-center">
							<img src="<?= empty($course['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($course['cover_image']) ?>"
								 alt="Image cover" id="image-cover" class="rounded flex-shrink-0 mr-3" style="height: 60px; width: 75px; object-fit: cover">
							<div>
								<h6 class="font-weight-bold mb-0"><?= $course['course_title'] ?></h6>
								<a href="<?= site_url('syllabus/curriculum/view/' . $course['id_curriculum']) ?>" class="d-block font-weight-bold btn-link mb-0">
									<?= if_empty($course['curriculum_title'], 'No curriculum') ?>
								</a>
								<span class="text-fade"><?= word_limiter(if_empty($course['description'], 'No description'), 10) ?></span>
							</div>
						</div>
					</td>
					<td><?= numerical($course['total_lessons']) ?></td>
					<td><?= format_date(if_empty($course['updated_at'], $course['created_at']), 'd M Y H:i') ?></td>
					<td>
						<span class="badge badge-<?= get_if_exist($courseStatuses, $course['status'], 'primary') ?>">
							<?= $course['status'] ?>
						</span>
					</td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/course/view/' . $course['id']) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_EDIT)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/course/edit/' . $course['id']) ?>">
										<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
									</a>
								<?php endif; ?>
								<?php if ($course['status'] == CourseModel::STATUS_ACTIVE): ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_CREATE)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
										   href="<?= site_url('syllabus/lesson/create/' . $course['id']) ?>">
											<i class="mdi mdi-plus mr-2"></i> Add Lesson
										</a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_EDIT)): ?>
										<a class="dropdown-item"
										   href="<?= site_url('syllabus/lesson/sort/' . $course['id']) ?>">
											<i class="mdi mdi-sort-variant mr-2"></i> Sort Lessons
										</a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_DELETE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
									   data-id="<?= $course['id'] ?>" data-label="<?= $course['course_title'] ?>" data-title="Course"
									   data-url="<?= site_url('syllabus/course/delete/' . $course['id']) ?>">
										<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
									</a>
								<?php endif; ?>
							</div>
						</div>
						<?php if (!empty($course['lessons'])): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary d-inline-block d-md-none btn-toggle-expand state-collapse"
									style="left: 3px" data-target="course-<?= $course['id'] ?>" data-id="course-<?= $course['id'] ?>">
								<i class="mdi mdi-plus"></i>
							</button>
						<?php endif; ?>
					</td>
				</tr>
				<?php foreach ($course['lessons'] as $lessonIndex => $lesson): ?>
					<?php if ($lessonIndex == 0): ?>
						<tr class="toggle-row ml-4 ml-md-0 text-primary bg-primary-fade row-header row-header-<?= $course['id'] ?>" data-button="course-<?= $course['id'] ?>" data-parent="course-<?= $course['id'] ?>" style="display: none">
							<th class="d-none d-md-table-cell"><span class="d-inline-block d-md-none">No</span></th>
							<th>Lesson</th>
							<th>Exercise</th>
							<th>Last Updated</th>
							<th colspan="2" class="text-md-right">Type</th>
						</tr>
					<?php endif; ?>
					<tr class="toggle-row ml-4 ml-md-0 text-muted" data-button="course-<?= $course['id'] ?>" data-header="row-header-<?= $course['id'] ?>" data-parent="course-<?= $course['id'] ?>" style="display: none">
						<td><span class="d-md-none"><?= $lessonIndex + 1 ?></span></td>
						<td>
							<a href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>" class="text-link">
								<span class="d-none d-md-inline"><?= $lessonIndex + 1 ?>.</span> <?= $lesson['lesson_title'] ?>
							</a>
						</td>
						<td><?= $lesson['total_exercises'] ?></td>
						<td><?= format_date(if_empty($lesson['updated_at'], $lesson['created_at']), 'd M Y H:i') ?></td>
						<td colspan="2" class="text-md-right"><?= strtoupper(pathinfo($lesson['source'], PATHINFO_EXTENSION)) ?></td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php if(empty($courses['data'])): ?>
				<tr>
					<td colspan="6">No course data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $courses]) ?>
    </div>
</div>

<?php $this->load->view('course/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
