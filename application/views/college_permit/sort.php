<form action="<?= site_url('syllabus/course/sort/' . $curriculum['id'] . '?redirect=' . get_url_param('redirect')) ?>" method="POST" class="form-sort-course">
	<?= _csrf() ?>
	<?= _method('put') ?>
	<div class="card mb-3 form-plaintext">
		<div class="card-body">
			<h5 class="card-title">Sort Curriculum</h5>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Curriculum Title</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $curriculum['curriculum_title'] ?>
					</p>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Total Course</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $curriculum['total_courses'] ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Sort Courses</h5>
			<div class="list-group list-group-flush" id="course-sortable-wrapper">
				<?php foreach ($courses as $index => $course): ?>
					<div class="list-group-item course-list-item">
						<div class="btn btn-light btn-sm cursor-pointer handle-order-course px-2 mr-2">
							<i class="mdi mdi-menu-swap"></i>
						</div>
						<span class="order-number"><?= $index + 1 ?></span>. <span class="ml-2"><?= $course['course_title'] ?></span>
						<input type="hidden" class="input-order" name="course_orders[<?= $course['id'] ?>]" value="<?= $index + 1 ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (empty($courses)): ?>
				No course available
			<?php endif; ?>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body d-flex justify-content-between">
			<button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if (!empty($courses)): ?>
				<button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
					Sort Courses
				</button>
			<?php endif; ?>
		</div>
	</div>
</form>
