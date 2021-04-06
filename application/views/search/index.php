<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="card-title">Scan Data</h5>
        </div>
		<form action="<?= site_url('search') ?>" method="get">
			<div class="form-group mb-2">
				<div class="input-group search-input">
					<input type="text" class="form-control" maxlength="50" name="q" id="q" aria-label="Search"
						   autofocus placeholder="Search data..." value="<?= get_url_param('q') ?>" required>
					<div class="input-group-append">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>

<?php if(!empty(get_url_param('q'))): ?>
	<?php
	$curriculumStatuses = [
		CurriculumModel::STATUS_ACTIVE => 'success',
		CurriculumModel::STATUS_INACTIVE => 'danger',
	];
	?>
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h5 class="card-title mb-sm-0">Result of Curriculums</h5>
	</div>
	<div class="form-row mb-4">
		<?php foreach ($curriculums as $index => $curriculum): ?>
			<div class="col-sm-6 mb-3">
				<div class="card d-flex flex-row overflow-hidden">
					<img src="<?= empty($curriculum['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($curriculum['cover_image']) ?>"
						 alt="Image cover" id="image-cover" style="width: 120px; height: 120px; object-fit: cover">
					<div class="d-flex flex-fill">
						<div class="card-body d-flex justify-content-between">
							<div>
								<a href="<?= site_url('syllabus/curriculum/view/' . $curriculum['id']) ?>" class="stretched-link">
									<h5 class="font-weight-bold mb-1"><?= $curriculum['curriculum_title'] ?></h5>
								</a>
								<p class="mb-0 text-muted">
									<?= word_limiter(if_empty($curriculum['description'], 'No description'), 20) ?>
								</p>
								<span class="text-fade small">
									<?= $curriculum['total_courses'] ?> Courses - Last updated at <?= format_date(if_empty($curriculum['updated_at'], $curriculum['created_at']), 'd M Y H:i') ?>
								</span>
							</div>
							<div>
								<span class="badge badge-<?= get_if_exist($curriculumStatuses, $curriculum['status'], 'primary') ?>">
									<?= $curriculum['status'] ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($curriculums)): ?>
			<div class="col text-muted">
				No any curriculums found
			</div>
		<?php endif; ?>
	</div>


	<?php
	$courseStatuses = [
		CourseModel::STATUS_ACTIVE => 'success',
		CourseModel::STATUS_INACTIVE => 'danger',
	];
	?>
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h5 class="card-title mb-sm-0">Result of Courses</h5>
	</div>
	<div class="form-row mb-4">
		<?php foreach ($courses as $course): ?>
			<div class="col-6 col-md-3 mb-3">
				<div class="card h-100">
					<img src="<?= empty($course['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($course['cover_image']) ?>"
						 alt="Cover <?= $course['course_title'] ?>" class="card-img-top" style="height: 130px; object-fit: cover">
					<div class="card-body">
						<div class="d-flex flex-fill flex-column">
							<a href="<?= site_url('syllabus/course/view/' . $course['id']) ?>">
								<h6 class="font-weight-bold mb-1"><?= $course['course_title'] ?></h6>
							</a>
							<p class="mb-0 text-muted">
								<?= word_limiter(if_empty($course['description'], 'No description'), 20) ?>
							</p>
						</div>
					</div>
					<div class="card-footer bg-white">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-fade small">
								<?= $course['total_lessons'] ?> Lessons
							</span>
							<span class="badge badge-<?= get_if_exist($courseStatuses, $course['status'], 'primary') ?>">
								<?= $course['status'] ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($courses)): ?>
			<div class="col text-muted">
				No any courses found
			</div>
		<?php endif; ?>
	</div>

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h5 class="card-title mb-sm-0">Result of Lessons</h5>
	</div>
	<?php if(!empty($lessons)): ?>
		<div class="card mb-3">
			<div class="card-body">
				<table class="table table-md table-hover responsive">
					<thead>
					<tr>
						<th class="border-top-0">Lesson Title</th>
						<th class="border-top-0">Course</th>
						<th class="border-top-0">Type</th>
						<th class="border-top-0">Description</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($lessons as $index => $lesson): ?>
						<tr>
							<td>
								<a href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>">
									<i class="<?= get_file_icon($lesson['source']) ?> h4 align-middle mr-1"></i> <?= $lesson['lesson_title'] ?>
								</a>
							</td>
							<td><?= $lesson['course_title'] ?></td>
							<td><?= strtoupper(pathinfo($lesson['source'], PATHINFO_EXTENSION)) ?></td>
							<td><?= word_limiter(if_empty($lesson['description'], 'No description'), 10) ?></td>
						</tr>
					<?php endforeach; ?>
					<?php if (empty($lessons)): ?>
						<tr>
							<td colspan="5">No course data available</td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>
	<?php if(empty($lessons)): ?>
		<div class="text-muted">
			No any lessons found
		</div>
	<?php endif; ?>

<?php endif; ?>
