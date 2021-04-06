<form class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View <?= if_empty(ucwords(str_replace(['-', '_'], ' ', $exercise['type'])), 'Collection') ?></h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Title</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exercise['exercise_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Category</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exercise['category'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Type</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(ucwords(str_replace(['-', '_'], ' ', $exercise['type'])), 'Collection') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Reference</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?php if($exercise['type'] == ExerciseModel::TYPE_CURRICULUM_EXAM): ?>
									<a href="<?= site_url('syllabus/curriculum/view/' . $exercise['id_reference']) ?>">
										<?= $exercise['reference_title'] ?>
									</a>
								<?php elseif($exercise['type'] == ExerciseModel::TYPE_LESSON_EXERCISE): ?>
									<a href="<?= site_url('syllabus/lesson/view/' . $exercise['id_reference']) ?>">
										<?= $exercise['reference_title'] ?>
									</a>
								<?php else: ?>
									-
								<?php endif; ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Total Questions</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= numerical($exercise['total_questions']) ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Question Sequence</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exercise['question_sequence'], '-') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Duration</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exercise['duration'], '-') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exercise['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($exercise['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($exercise['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<?php $this->load->view('exercise/_view_question', [
		'exercise' => $exercise,
		'questions' => $questions
	]) ?>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_EDIT)): ?>
				<a href="<?= site_url('syllabus/exercise/edit/' . $exercise['id']) ?>" class="btn btn-primary">
					Edit Exercise
				</a>
			<?php endif; ?>
        </div>
    </div>
</form>
