<div class="card border-primary mb-3" id="quiz-header">
    <div class="card-body d-flex justify-content-between align-items-center">
		<div>
			<h5 class="card-title mb-1 text-primary">
				<?= $exercise['exercise_title'] ?>
			</h5>
			<h6 class="mb-0">
				Exam - <?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= count($questions) ?>)
			</h6>
		</div>
		<div class="d-flex align-items-center">
			<div class="text-muted mr-2" id="saving-progress-indicator" style="display: none">
				<i class="mdi mdi-loading mdi-spin"></i> <span class="text-base">Saving</span>
			</div>
			<h2 class="mb-0 font-weight-bold">
				<time id="quiz-duration" class="text-info px-2 rounded" data-time="<?= if_empty($timeLeft, $exercise['duration']) ?>">
					<?= if_empty($timeLeft, $exercise['duration']) ?>
				</time>
			</h2>
		</div>
	</div>
</div>
<form class="form-plaintext">
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title"><?= ucfirst(strtolower($exercise['category'])) ?> Exam</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Curriculum</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exam['curriculum_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Employee</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exam['employee_name'] ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Evaluator</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exam['evaluator_name'], 'No evaluator (Trainer)') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Started At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($examExercise['started_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<form action="<?= site_url("training/exam-exercise/submit-answer/{$examExercise['id']}") ?>" id="form-quiz" data-id="<?= $examExercise['id'] ?>" method="post" enctype="multipart/form-data">
	<?= _csrf() ?>
	<?php foreach ($questions as $index => $question): ?>
		<div class="card mb-3">
			<div class="card-body">
				<div class="mb-2">
					<div class="d-flex">
						<p class="lead mb-0 mr-2"><?= $index + 1 ?>.</p>
						<div>
							<p class="lead mb-1"><?= ucfirst($question['question']) ?></p>
							<p class="text-muted mb-1">
								<?= ucfirst($question['hint']) ?>
							</p>
							<?php if (!empty($question['attachment'])): ?>
								<p class="mb-1 text-fade">
									Attachment:
									<a class="btn-link" href="<?= site_url('media/view?source=' . $question['attachment']) ?>" target="_blank">
										<?= basename($question['attachment']) ?>
									</a>
								</p>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="ml-4">
					<?php if($exercise['category'] == ExerciseModel::CATEGORY_CHOICES): ?>
						<?php foreach($question['answer_choices'] as $answerChoice): ?>
							<div class="custom-control custom-radio">
								<input type="radio" aria-label="<?= $answerChoice['answer'] ?>" name="answers[<?= $question['id'] ?>]"
									   class="custom-control-input radio-answer-choice" id="answer-choice-<?= $answerChoice['id'] ?>" value="<?= $answerChoice['id'] ?>"
										<?= get_if_exist($question, 'current_answer') == $answerChoice['answer'] ? ' checked' : '' ?>>
								<label class="custom-control-label font-weight-normal" for="answer-choice-<?= $answerChoice['id'] ?>">
									<?= ucfirst($answerChoice['answer']) ?>
								</label>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="form-group">
							<label for="answers-<?= $question['id'] ?>" class="font-weight-bold text-success">Answer</label>
							<textarea name="answers[<?= $question['id'] ?>][answer]" id="answers-<?= $question['id'] ?>" rows="6" maxlength="5000"
									  class="form-control input-answer-essay" placeholder="Write your answer"><?= get_if_exist($question, 'current_answer') ?></textarea>
						</div>
						<div class="form-group">
							<input type="file" id="attachment" name="answers[<?= $question['id'] ?>][attachment]" class="file-upload-default" data-max-size="1000000">
							<div class="input-group">
								<input type="text" class="form-control file-upload-info" disabled placeholder="Upload attachment" value="<?= $attachment ?? '' ?>" aria-label="Attachment">
								<div class="input-group-append">
									<button class="file-upload-browse btn btn-outline-primary btn-simple-upload" type="button">
										Pick File
									</button>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

	<?php if ($exercise['category'] != ExerciseModel::CATEGORY_PRACTICE): ?>
		<div class="alert alert-warning d-flex">
			<i class="mdi mdi-information-outline mb-0 mr-1"></i>
			<p class="mb-0">
				<strong>Please double check your answer</strong>, you can't edit your answer after submission
			</p>
		</div>
	<?php endif; ?>

	<div class="d-flex justify-content-between">
		<button type="reset" class="btn btn-outline-danger">Reset</button>
		<div>
			<?php if ($exercise['category'] != ExerciseModel::CATEGORY_CHOICES): ?>
				<button type="button" class="btn btn-outline-secondary" disabled>
					Assessed By Trainer
				</button>
			<?php endif; ?>
			<button type="button" class="btn btn-primary" id="btn-submit-answer">Submit Answers</button>
		</div>
	</div>
</form>

<?php $this->load->view('classroom/_modal_timeout') ?>
<?php $this->load->view('partials/modals/_confirm') ?>
