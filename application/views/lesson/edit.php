<form action="<?= site_url('syllabus/lesson/update/' . $lesson['id']) ?>" method="POST" id="form-lesson">
	<?= _csrf() ?>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Edit Lesson</h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="course">Course</label>
						<select class="form-control select2" id="course" name="course" data-placeholder="Select course" required>
							<option value="">No course</option>
							<?php foreach ($courses as $course): ?>
								<option value="<?= $course['id'] ?>"<?= set_select('course', $course['id'], $course['id'] == $lesson['id_course']) ?>>
									<?= $course['course_title'] ?> - <?= $course['curriculum_title'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('course') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="lesson_title">Course Title</label>
						<input type="text" class="form-control" id="lesson_title" name="lesson_title" required maxlength="50"
							   value="<?= set_value('lesson_title', $lesson['lesson_title']) ?>" placeholder="Course title">
						<?= form_error('lesson_title') ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" id="description" name="description" maxlength="500"
						  placeholder="Enter lesson description"><?= set_value('description', $lesson['description']) ?></textarea>
				<?= form_error('description') ?>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Upload Lesson</h5>
			<?php $fileInput = set_value('file_input', $lesson['source']) ?>
			<div class="d-flex flex-column align-items-center justify-content-center mb-3 uploader-wrapper">
				<h6>Lesson</h6>
				<div class="upload-content-wrapper">
					<div class="d-flex flex-row align-items-center p-4 mb-2" style="border: 1px dashed #aaaaaa;">
						<h1 class="display-3 line-height-1 mb-0 mr-2">
							<i class="mdi mdi-file-document-outline"></i>
						</h1>
						<div class="text-left mr-4">
							<p class="mb-1 upload-label-title">Drag file here or click button pick file</p>
							<p class="small text-muted mb-2 upload-label-file">
								<?= empty($fileInput) ? 'Selected file: Unknown file' : $fileInput ?>
							</p>

							<div class="progress mb-0" style="height: 20px">
								<div class="progress-bar" id="upload-progress" role="progressbar"
									 style="width: <?= empty($fileInput) ? '0' : '100%' ?>"
									 aria-valuenow="<?= empty($fileInput) ? '0' : '100' ?>"
									 aria-valuemin="0"
									 aria-valuemax="100"></div>
							</div>
						</div>
					</div>
				</div>
				<p class="small text-muted mb-3">Only file pdf, image, presentation, and video type</p>

				<button type="button" data-target="upload-file-input" class="btn <?= empty($fileInput) ? 'btn-outline-primary' : 'btn-success' ?> px-4" id="btn-pick-file">
					<i class="mdi mdi-upload mr-2"></i><?= empty($fileInput) ? 'PICK FILE' : 'CHANGE FILE' ?>
				</button>

				<div>
					<input type="text" class="visually-hidden" id="uploaded-file" name="file_input" value="<?= $fileInput ?>" required aria-label="Uploaded file">
					<input type="file" class="visually-hidden upload-file-input" id="upload-file-input" accept="application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/jpg,image/jpeg,image/png,video/mp4">
				</div>

			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body d-flex justify-content-between">
			<button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<button type="submit" class="btn btn-primary">
				Update Lesson
			</button>
		</div>
	</div>
</form>

<?php $this->load->view('partials/modals/_alert') ?>
