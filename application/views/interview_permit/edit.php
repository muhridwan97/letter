<form action="<?= site_url('syllabus/course/update/' . $course['id']) ?>" method="POST" enctype="multipart/form-data" id="form-course">
	<?= _csrf() ?>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Edit Curriculum</h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="curriculum">Curriculum</label>
						<select class="form-control select2" id="curriculum" name="curriculum" data-placeholder="Select curriculum" required>
							<option value="">No curriculum</option>
							<?php foreach ($curriculums as $curriculum): ?>
								<option value="<?= $curriculum['id'] ?>"<?= set_select('curriculum', $curriculum['id'], $curriculum['id'] == $course['id_curriculum']) ?>>
									<?= $curriculum['curriculum_title'] ?> - <?= $curriculum['department'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('curriculum') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="course_title">Course Title</label>
						<input type="text" class="form-control" id="course_title" name="course_title" required maxlength="50"
							   value="<?= set_value('course_title', $course['course_title']) ?>" placeholder="Course title">
						<?= form_error('course_title') ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" id="description" name="description" maxlength="500"
						  placeholder="Enter course description"><?= set_value('description', $course['description']) ?></textarea>
				<?= form_error('description') ?>
			</div>
			<div class="form-group">
				<label for="status">Status</label>
				<div class="mt-1">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="status_active" name="status" value="ACTIVE" class="custom-control-input"<?= set_radio('status', 'ACTIVE', $course['status'] == 'ACTIVE') ?>>
						<label class="custom-control-label" for="status_active">Active</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="status_inactive" name="status" value="INACTIVE" class="custom-control-input"<?= set_radio('status', 'INACTIVE', $course['status'] == 'INACTIVE') ?>>
						<label class="custom-control-label" for="status_inactive">Inactive</label>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Course Image</h5>
			<div class="form-group">
				<div class="d-flex flex-column flex-sm-row align-items-center">
					<div class="mb-3 mb-sm-0">
						<img src="<?= empty($course['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($course['cover_image']) ?>"
							 alt="Image cover" id="image-cover" class="rounded img-fluid" style="height:140px; width: 140px; object-fit: cover">
					</div>
					<div class="mr-lg-3 ml-sm-4 flex-fill" style="max-width: 500px">
						<label for="cover_image" class="d-none d-md-block">
							Select a cover image
						</label>
						<input type="file" id="cover" name="cover_image" accept="image/jpeg,image/png" class="file-upload-default" data-max-size="2000000" data-target-preview="#image-cover">
						<div class="input-group">
							<input type="text" class="form-control file-upload-info" disabled aria-label="Select cover"
								   placeholder="<?= empty($course['cover_image']) ? 'Upload cover' : basename($course['cover_image']) ?>">
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-primary btn-simple-upload" type="button">
									Select Cover
								</button>
							</span>
						</div>
						<span class="form-text">Leave it unselected if you don't change the cover.</span>
						<?= form_error('cover_image') ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card mb-3 form-sort-lesson">
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
			<button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
				Update Course
			</button>
		</div>
	</div>
</form>
