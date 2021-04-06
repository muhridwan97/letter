<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Lesson</h5>
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
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_CREATE)): ?>
					<a href="<?= site_url('syllabus/lesson/create') ?>" class="btn btn-sm btn-primary">
						<i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
					</a>
				<?php endif; ?>
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive">
			<thead>
			<tr>
				<th class="text-md-center" style="width: 60px">No</th>
				<th>Lesson</th>
				<th>Course</th>
				<th>Curriculum</th>
				<th>Exercise</th>
				<th>Last Updated</th>
				<th style="width: 100px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$lessonStatuses = [
					CourseModel::STATUS_ACTIVE => 'success',
					CourseModel::STATUS_INACTIVE => 'danger',
				];
			?>
			<?php $no = isset($lessons) ? ($lessons['current_page'] - 1) * $lessons['per_page'] : 0 ?>
			<?php foreach ($lessons['data'] as $lesson): ?>
				<tr>
					<td class="text-md-center"><?= ++$no ?></td>
					<td>
						<i class="<?= get_file_icon($lesson['source']) ?> lead mr-1" title="<?= $lesson['mime'] ?>"></i>
						<?= $lesson['lesson_title'] ?>
					</td>
					<td>
						<a href="<?= site_url('syllabus/course/view/' . $lesson['id_course']) ?>">
							<?= if_empty($lesson['course_title'], '-') ?>
						</a>
					</td>
					<td>
						<a href="<?= site_url('syllabus/curriculum/view/' . $lesson['id_curriculum']) ?>">
							<?= if_empty($lesson['curriculum_title'], '-') ?>
						</a>
					</td>
					<td><?= numerical($lesson['total_exercises']) ?></td>
					<td><?= format_date(if_empty($lesson['updated_at'], $lesson['created_at']), 'd M Y H:i') ?></td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_EDIT)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/lesson/edit/' . $lesson['id']) ?>">
										<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_CREATE)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/exercise/create/' . ExerciseModel::TYPE_LESSON_EXERCISE . '/' . $lesson['id'] . '?redirect=' . site_url('syllabus/lesson/view/' . $lesson['id'])) ?>">
										<i class="mdi mdi-file-document-edit-outline mr-2"></i> Add Exercise
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_DELETE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
									   data-id="<?= $lesson['id'] ?>" data-label="<?= $lesson['lesson_title'] ?>" data-title="Lesson"
									   data-url="<?= site_url('syllabus/lesson/delete/' . $lesson['id']) ?>">
										<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
									</a>
								<?php endif; ?>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if(empty($lessons['data'])): ?>
				<tr>
					<td colspan="6">No lesson data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $lessons]) ?>
    </div>
</div>

<?php $this->load->view('lesson/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
