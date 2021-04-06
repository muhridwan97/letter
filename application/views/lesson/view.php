<form class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View Lesson</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Lesson Title</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $lesson['lesson_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Course</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $lesson['course_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Curriculum</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $lesson['curriculum_title'] ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($lesson['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($lesson['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($lesson['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

    <div class="card mb-3">
		<div class="card-header bg-white d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0">Media Viewer</h5>
			<button type="button" class="btn btn-sm btn-primary btn-fullscreen" data-target="#media-viewer-wrapper" title="Toggle fullscreen">
				<i class="mdi mdi-window-maximize"></i> Fullscreen
			</button>
		</div>
        <div class="card-body">
			<?php $this->load->view('media/_media_viewer', [
				'title' => $lesson['lesson_title'],
				'source' => $lesson['source'],
				'mime' => $lesson['mime']
			]) ?>
		</div>
	</div>

	<?php if (!empty($exercises)): ?>
		<?php foreach ($exercises as $exercise): ?>
			<?php $this->load->view('exercise/_view_question', [
				'exercise' => $exercise,
				'questions' => $exercise['questions']
			]) ?>
		<?php endforeach; ?>
	<?php endif ?>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_EDIT)): ?>
				<a href="<?= site_url('syllabus/lesson/edit/' . $lesson['id']) ?>" class="btn btn-primary">
					Edit Lesson
				</a>
			<?php endif; ?>
        </div>
    </div>
</form>

<?php $this->load->view('partials/modals/_alert') ?>
