<form class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
			<div class="d-flex justify-content-between align-items-start">
            	<h5 class="card-title">View Curriculum</h5>
				<a href="<?= site_url('syllabus/curriculum/sort/' . $curriculum['id_department'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-title="Sort curriculum">
					SORT <i class="mdi mdi-sort-variant"></i>
				</a>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Curriculum Title</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $curriculum['curriculum_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Department</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $curriculum['department'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Status</label>
						<div class="col-sm-8">
							<?php
							$curriculumStatuses = [
								CurriculumModel::STATUS_ACTIVE => 'success',
								CurriculumModel::STATUS_INACTIVE => 'danger',
							];
							?>
							<p class="form-control-plaintext">
								<span class="badge badge-<?= get_if_exist($curriculumStatuses, $curriculum['status'], 'primary') ?>">
									<?= $curriculum['status'] ?>
								</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($curriculum['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($curriculum['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($curriculum['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<?php
	$courseStatuses = [
		CourseModel::STATUS_ACTIVE => 'success',
		CourseModel::STATUS_INACTIVE => 'danger',
	];
	?>

	<div class="d-flex justify-content-between align-items-start mb-2">
		<h5 class="card-title">Courses</h5>
		<div>
			<a href="<?= site_url('syllabus/course/sort/' . $curriculum['id']. '?redirect=' . site_url(uri_string())) ?>" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-title="Sort courses">
				SORT <i class="mdi mdi-sort-variant"></i>
			</a>
			<a href="<?= site_url('syllabus/course/create/' . $curriculum['id'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-title="Add course">
				<i class="mdi mdi-plus"></i>
			</a>
		</div>
	</div>
	<?php foreach ($courses as $course): ?>
		<div class="card d-flex flex-row overflow-hidden" style="z-index: 2; border-bottom-right-radius: 0">
			<img src="<?= empty($course['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($course['cover_image']) ?>"
				 alt="Image cover" id="image-cover" style="width: 120px; height: 120px; object-fit: cover">
			<div class="d-flex flex-fill">
				<div class="card-body d-flex justify-content-between">
					<div class="align-self-md-center">
						<a href="<?= site_url('syllabus/course/view/' . $course['id']) ?>" class="stretched-link">
							<h5 class="font-weight-bold mb-1"><?= $course['course_title'] ?></h5>
						</a>
						<p class="mb-0 text-muted">
							<?= word_limiter(if_empty($course['description'], 'No description'), 20) ?>
						</p>
						<span class="text-fade small">
							<?= $course['total_lessons'] ?> Lessons - Last updated at <?= format_date(if_empty($course['updated_at'], $course['created_at']), 'd M Y H:i') ?>
						</span>
					</div>
					<div class="d-flex align-items-start">
						<span class="badge badge-<?= get_if_exist($courseStatuses, $course['status'], 'primary') ?>">
							<?= $course['status'] ?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="list-group list-group-flush ml-3 mb-4">
			<div class="list-group-item font-weight-bold d-flex justify-content-between">
				<span>Lessons</span>
				<div>
					<a href="<?= site_url('syllabus/lesson/sort/' . $course['id']. '?redirect=' . site_url(uri_string())) ?>" class="btn btn-outline-primary px-1 py-0 btn-sm" data-toggle="tooltip" data-title="Sort lessons">
						<i class="mdi mdi-sort-variant"></i>
					</a>
					<a href="<?= site_url('syllabus/lesson/create/' . $course['id'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-info btn-sm px-1 py-0" data-toggle="tooltip" data-title="Add lesson">
						<i class="mdi mdi-plus"></i>
					</a>
				</div>
			</div>
			<?php foreach ($course['lessons'] as $lessonIndex => $lesson): ?>
				<a href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>" class="list-group-item list-group-item-action text-muted d-flex justify-content-between">
					<?= $lessonIndex + 1 ?>. <?= $lesson['lesson_title'] ?>
					<i class="<?= get_file_icon($lesson['source']) ?>"></i>
				</a>
			<?php endforeach; ?>
			<?php if (empty($course['lessons'])): ?>
				<div class="list-group-item text-fade text-base">
					No lesson available
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>

	<?php if(empty($courses)): ?>
		<div class="card mb-3">
			<div class="card-body">
				<p class="text-muted mb-0">No courses available</p>
			</div>
		</div>
	<?php endif; ?>

	<div class="d-flex justify-content-between align-items-start mb-2">
		<h5 class="card-title">Exams</h5>
		<div>
			<a href="<?= site_url('syllabus/exercise/create/' . ExerciseModel::TYPE_CURRICULUM_EXAM . '/' . $curriculum['id'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-title="Add exam">
				<i class="mdi mdi-plus"></i>
			</a>
		</div>
	</div>
	<?php if (!empty($exercises)): ?>
		<?php foreach ($exercises as $exercise): ?>
			<?php $this->load->view('exercise/_view_question', [
				'exercise' => $exercise,
				'questions' => $exercise['questions']
			]) ?>
		<?php endforeach; ?>
	<?php else: ?>
		<div class="card mb-3">
			<div class="card-body">
				<p class="text-muted mb-0">No exams available</p>
			</div>
		</div>
	<?php endif ?>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_EDIT)): ?>
				<a href="<?= site_url('syllabus/curriculum/edit/' . $curriculum['id']) ?>" class="btn btn-primary">
					Edit Curriculum
				</a>
			<?php endif; ?>
        </div>
    </div>
</form>
