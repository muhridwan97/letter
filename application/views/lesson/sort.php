<form action="<?= site_url('syllabus/lesson/sort/' . $course['id'] . '?redirect=' . get_url_param('redirect')) ?>" method="POST" class="form-sort-lesson">
	<?= _csrf() ?>
	<?= _method('put') ?>
	<div class="card mb-3 form-plaintext">
		<div class="card-body">
			<h5 class="card-title">Sort Course</h5>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Curriculum Title</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $course['curriculum_title'] ?>
					</p>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Course Title</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $course['course_title'] ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Sort Lessons</h5>
			<div class="list-group list-group-flush" id="lesson-sortable-wrapper">
				<?php foreach ($lessons as $index => $lesson): ?>
					<div class="list-group-item lesson-list-item">
						<div class="btn btn-light btn-sm cursor-pointer handle-order-lesson px-2 mr-2">
							<i class="mdi mdi-menu-swap"></i>
						</div>
						<span class="order-number"><?= $index + 1 ?></span>. <span class="ml-2"><?= $lesson['lesson_title'] ?></span>
						<input type="hidden" class="input-order" name="lesson_orders[<?= $lesson['id'] ?>]" value="<?= $index + 1 ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (empty($lessons)): ?>
				No lesson available
			<?php endif; ?>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body d-flex justify-content-between">
			<button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if (!empty($lessons)): ?>
				<button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
					Sort Lessons
				</button>
			<?php endif; ?>
		</div>
	</div>
</form>
