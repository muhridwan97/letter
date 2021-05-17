<form class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View Course</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Course Title</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $course['course_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Curriculum</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $course['curriculum_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Status</label>
						<div class="col-sm-8">
							<?php
							$courseStatuses = [
								CourseModel::STATUS_ACTIVE => 'success',
								CourseModel::STATUS_INACTIVE => 'danger',
							];
							?>
							<p class="form-control-plaintext">
								<span class="badge badge-<?= get_if_exist($courseStatuses, $course['status'], 'primary') ?>">
									<?= $course['status'] ?>
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
								<?= if_empty($course['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($course['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($course['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<div class="card mb-3">
		<div class="card-body">
			<div class="d-flex justify-content-between mb-2">
				<h5 class="card-title">Lessons</h5>
				<div>
					<a href="<?= site_url('syllabus/lesson/sort/' . $course['id']. '?redirect=' . site_url(uri_string())) ?>" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-title="Sort lessons">
						SORT <i class="mdi mdi-sort-variant"></i>
					</a>
					<a href="<?= site_url('syllabus/lesson/create/' . $course['id'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-success btn-sm" data-toggle="tooltip" data-title="Add lesson">
						<i class="mdi mdi-plus"></i>
					</a>
				</div>
			</div>
			<table class="table table-md table-hover responsive">
				<thead>
				<tr>
					<th style="width: 50px" class="text-md-center">No</th>
					<th>Lesson</th>
					<th>Exercise</th>
					<th>Type</th>
					<th>Description</th>
					<th class="text-md-right">Last Updated</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($lessons as $index => $lesson): ?>
					<tr>
						<td class="text-md-center"><?= $index + 1 ?></td>
						<td>
							<a href="<?= site_url('syllabus/lesson/view/' . $lesson['id']) ?>">
								<i class="<?= get_file_icon($lesson['source']) ?> mr-1"></i> <?= $lesson['lesson_title'] ?>
							</a>
						</td>
						<td><?= numerical($lesson['total_exercises']) ?></td>
						<td><?= strtoupper(pathinfo($lesson['source'], PATHINFO_EXTENSION)) ?></td>
						<td><?= word_limiter(if_empty($lesson['description'], 'No description'), 10) ?></td>
						<td class="text-md-right"><?= format_date(if_empty($lesson['updated_at'], $lesson['created_at']), 'd M Y H:i') ?></td>
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

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_EDIT)): ?>
				<a href="<?= site_url('syllabus/course/edit/' . $course['id']) ?>" class="btn btn-primary">
					Edit Course
				</a>
			<?php endif; ?>
        </div>
    </div>
</form>
