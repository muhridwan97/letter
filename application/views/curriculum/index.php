<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Curriculum</h5>
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
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_CREATE)): ?>
					<a href="<?= site_url('syllabus/curriculum/create') ?>" class="btn btn-sm btn-primary">
						<i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
					</a>
				<?php endif; ?>
            </div>
        </div>
		<table class="table table-sm mt-3 responsive position-relative">
			<thead>
			<tr class="toggle-row" data-button="toggle-curriculum">
				<th class="text-md-center" style="width: 80px">
					<button type="button" data-toggle="tooltip" data-title="Toggle All" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block btn-toggle-expand-all btn-toggle-auto-hide state-collapse"
							style="left: 5px" data-target="toggle-curriculum">
						<i class="mdi mdi-plus" style="margin: 0"></i>
					</button>
					<span class="ml-md-2">No</span>
				</th>
				<th>Curriculum</th>
				<th>Course</th>
				<th>Exam</th>
				<th>Last Updated</th>
				<th style="width: 90px">Status</th>
				<th style="width: 100px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$curriculumStatuses = [
					CurriculumModel::STATUS_ACTIVE => 'success',
					CurriculumModel::STATUS_INACTIVE => 'danger',
				];
			?>
			<?php $no = isset($curriculums) ? ($curriculums['current_page'] - 1) * $curriculums['per_page'] : 0 ?>
			<?php foreach ($curriculums['data'] as $curriculum): ?>
				<tr class="toggle-row" data-button="curriculum-<?= $curriculum['id'] ?>">
					<td class="text-md-center">
						<?php if (!empty($curriculum['courses'])): ?>
							<button type="button" class="btn btn-sm py-0 px-1 mr-2 position-absolute btn-outline-secondary d-none d-md-inline-block toggle-curriculum btn-toggle-expand btn-toggle-auto-hide state-collapse"
									style="left: 5px" data-target="curriculum-<?= $curriculum['id'] ?>" data-id="curriculum-<?= $curriculum['id'] ?>">
								<i class="mdi mdi-plus" style="margin: 0"></i>
							</button>
						<?php endif; ?>
						<span class="ml-md-1"><?= ++$no ?></span>
					</td>
					<td style="max-width: 350px">
						<div class="d-flex align-items-center">
							<img src="<?= empty($curriculum['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($curriculum['cover_image']) ?>"
								 alt="Image cover" id="image-cover" class="rounded flex-shrink-0 mr-3" style="height: 60px; width: 75px; object-fit: cover">
							<div>
								<h6 class="font-weight-bold mb-0"><?= $curriculum['curriculum_title'] ?></h6>
								<p class="text-muted mb-0"><?= if_empty($curriculum['department'], 'No') ?> department</p>
								<span class="text-fade"><?= word_limiter(if_empty($curriculum['description'], 'No description'), 10) ?></span>
							</div>
						</div>
					</td>
					<td><?= numerical($curriculum['total_courses']) ?></td>
					<td><?= numerical($curriculum['total_exams']) ?></td>
					<td><?= format_date(if_empty($curriculum['updated_at'], $curriculum['created_at']), 'd M Y H:i') ?></td>
					<td>
						<span class="badge badge-<?= get_if_exist($curriculumStatuses, $curriculum['status'], 'primary') ?>">
							<?= $curriculum['status'] ?>
						</span>
					</td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/curriculum/view/' . $curriculum['id']) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_EDIT)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/curriculum/edit/' . $curriculum['id']) ?>">
										<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
									</a>
								<?php endif; ?>
								<?php if ($curriculum['status'] == CurriculumModel::STATUS_ACTIVE): ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_CREATE)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
										   href="<?= site_url('syllabus/exercise/create/' . ExerciseModel::TYPE_CURRICULUM_EXAM . '/' . $curriculum['id'] . '?redirect=' . site_url('syllabus/curriculum/view/' . $curriculum['id'])) ?>">
											<i class="mdi mdi-file-document-edit-outline mr-2"></i> Add Exam
										</a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_CREATE)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
										   href="<?= site_url('syllabus/course/create/' . $curriculum['id']) ?>">
											<i class="mdi mdi-plus mr-2"></i> Add Course
										</a>
									<?php endif; ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_EDIT)): ?>
										<a class="dropdown-item"
										   href="<?= site_url('syllabus/course/sort/' . $curriculum['id']) ?>">
											<i class="mdi mdi-sort-variant mr-2"></i> Sort Courses
										</a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_DELETE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
									   data-id="<?= $curriculum['id'] ?>" data-label="<?= $curriculum['curriculum_title'] ?>" data-title="Curriculum"
									   data-url="<?= site_url('syllabus/curriculum/delete/' . $curriculum['id']) ?>">
										<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
									</a>
								<?php endif; ?>
							</div>
						</div>
						<?php if (!empty($curriculum['courses'])): ?>
							<button type="button" class="btn btn-sm btn-outline-secondary d-inline-block d-md-none btn-toggle-expand state-collapse"
									style="left: 3px" data-target="curriculum-<?= $curriculum['id'] ?>" data-id="curriculum-<?= $curriculum['id'] ?>">
								<i class="mdi mdi-plus"></i>
							</button>
						<?php endif; ?>
					</td>
				</tr>
				<?php foreach ($curriculum['courses'] as $courseIndex => $course): ?>
					<?php if ($courseIndex == 0): ?>
						<tr class="ml-4 ml-md-0 text-primary bg-primary-fade row-header row-header-<?= $curriculum['id'] ?>" data-parent="curriculum-<?= $curriculum['id'] ?>" style="display: none">
							<th class="d-none d-md-table-cell"><span class="d-inline-block d-md-none">No</span></th>
							<th>Course</th>
							<th colspan="2">Lesson</th>
							<th>Last Updated</th>
							<th colspan="2" class="text-md-right">Status</th>
						</tr>
					<?php endif; ?>
					<tr class="ml-4 ml-md-0 text-muted" data-header="row-header-<?= $curriculum['id'] ?>" data-parent="curriculum-<?= $curriculum['id'] ?>" style="display: none">
						<td><span class="d-md-none"><?= $courseIndex + 1 ?></span></td>
						<td>
							<img src="<?= empty($course['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($course['cover_image']) ?>"
								 alt="Image cover" id="image-cover" class="rounded mr-2" style="height: 30px; width: 30px; object-fit: cover">
							<a href="<?= site_url('syllabus/course/view/' . $course['id']) ?>" class="text-link">
								<?= $course['course_title'] ?>
							</a>
						</td>
						<td colspan="2"><?= $course['total_lessons'] ?> lessons</td>
						<td><?= format_date(if_empty($course['updated_at'], $course['created_at']), 'd M Y H:i') ?></td>
						<td colspan="2" class="text-md-right"><?= $course['status'] ?></td>
					</tr>
					<?php if (!empty($course['lessons'])): ?>
						<tr class="ml-5 ml-md-0" data-parent="curriculum-<?= $curriculum['id'] ?>" style="display: none">
							<td class="pl-3"><span class="d-inline-block d-md-none">Lessons</span></td>
							<td colspan="6" class="pl-3">
								<table class="table table-borderless table-sm responsive">
									<thead>
									<tr class="row-header row-header-lesson-<?= $course['id'] ?>">
										<th style="width: 40px">No</th>
										<th>Lesson</th>
										<th class="text-md-right">Type</th>
									</tr>
									</thead>
									<tbody>
									<?php foreach ($course['lessons'] as $lessonIndex => $lesson): ?>
										<tr class="text-fade" data-header="row-header-lesson-<?= $course['id'] ?>">
											<td><?= $lessonIndex + 1 ?></td>
											<td>
												<a href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>" class="text-link">
													<i class="<?= get_file_icon($lesson['source']) ?> mr-1"></i><?= $lesson['lesson_title'] ?>
												</a>
											</td>
											<td class="text-md-right">
												<?= strtoupper(pathinfo($lesson['source'], PATHINFO_EXTENSION)) ?>
											</td>
										</tr>
									<?php endforeach; ?>
									</tbody>
								</table>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php if(empty($curriculums['data'])): ?>
				<tr>
					<td colspan="7">No curriculum data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $curriculums]) ?>
    </div>
</div>

<?php $this->load->view('curriculum/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
