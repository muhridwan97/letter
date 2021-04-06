<form action="<?= site_url('syllabus/curriculum/update/' . $curriculum['id']) ?>" method="POST" enctype="multipart/form-data" id="form-curriculum">
	<?= _csrf() ?>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Edit Curriculum</h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="department">Department</label>
						<select class="form-control select2" id="department" name="department" data-placeholder="Select department" required>
							<option value="">No department</option>
							<?php foreach ($departments as $department): ?>
								<option value="<?= $department['id'] ?>"<?= set_select('department', $department['id'], $department['id'] == $curriculum['id_department']) ?>>
									<?= $department['department'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('department') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="curriculum_title">Curriculum Title</label>
						<input type="text" class="form-control" id="curriculum_title" name="curriculum_title" required maxlength="100"
							   value="<?= set_value('curriculum_title', $curriculum['curriculum_title']) ?>" placeholder="Curriculum title">
						<?= form_error('curriculum_title') ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" id="description" name="description" maxlength="500"
						  placeholder="Enter curriculum description"><?= set_value('description', $curriculum['description']) ?></textarea>
				<?= form_error('description') ?>
			</div>
			<div class="form-group">
				<label for="status">Status</label>
				<div class="mt-1">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="status_active" name="status" value="ACTIVE" class="custom-control-input"<?= set_radio('status', 'ACTIVE', $curriculum['status'] == 'ACTIVE') ?>>
						<label class="custom-control-label" for="status_active">Active</label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="status_inactive" name="status" value="INACTIVE" class="custom-control-input"<?= set_radio('status', 'INACTIVE', $curriculum['status'] == 'INACTIVE') ?>>
						<label class="custom-control-label" for="status_inactive">Inactive</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Curriculum Image</h5>
			<div class="form-group">
				<div class="d-flex flex-column flex-sm-row align-items-center">
					<div class="mb-3 mb-sm-0">
						<img src="<?= empty($curriculum['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($curriculum['cover_image']) ?>"
							 alt="Image cover" id="image-cover" class="rounded img-fluid" style="height:140px; width: 140px; object-fit: cover">
					</div>
					<div class="mr-lg-3 ml-sm-4 flex-fill" style="max-width: 500px">
						<label for="cover_image" class="d-none d-md-block">
							Select a cover image
						</label>
						<input type="file" id="cover" name="cover_image" accept="image/jpeg,image/png" class="file-upload-default" data-max-size="2000000" data-target-preview="#image-cover">
						<div class="input-group">
							<input type="text" class="form-control file-upload-info" disabled aria-label="Select cover"
								   placeholder="<?= empty($curriculum['cover_image']) ? 'Upload cover' : basename($curriculum['cover_image']) ?>">
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

	<div class="card mb-3 form-sort-course">
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
			<button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
				Update Curriculum
			</button>
		</div>
	</div>
</form>
