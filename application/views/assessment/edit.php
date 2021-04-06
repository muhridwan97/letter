<div class="card form-plaintext mb-3">
	<div class="card-body">
		<h5 class="card-title">Exam Assessment</h5>
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
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">Evaluator</label>
					<div class="col-sm-8">
						<p class="form-control-plaintext">
							<?= if_empty($exam['evaluator_name'], 'No evaluator (Trainer)') ?>
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">Started At</label>
					<div class="col-sm-8">
						<p class="form-control-plaintext">
							<?= if_empty(format_date($examExercise['started_at'], 'd M Y H:i'), '<span class="text-fade">No Started Yet</span>') ?>
						</p>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">Finished At</label>
					<div class="col-sm-8">
						<p class="form-control-plaintext">
							<?= if_empty(format_date($examExercise['started_at'], 'd M Y H:i'), '<span class="text-fade">No Started Yet</span>') ?>
						</p>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-4 col-form-label">Status</label>
					<div class="col-sm-8">
						<?php
						$examExerciseStatuses = [
							ExamExerciseModel::STATUS_PENDING => 'primary',
							ExamExerciseModel::STATUS_STARTED => 'warning',
							ExamExerciseModel::STATUS_FINISHED => 'info',
							ExamExerciseModel::STATUS_ASSESSED => 'success',
						];
						?>
						<p class="form-control-plaintext">
							<span class="badge badge-<?= get_if_exist($examExerciseStatuses, $examExercise['status'], 'light') ?>">
								<?= $examExercise['status'] ?>
							</span>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="<?= site_url("training/assessment/update/{$examExercise['id']}") ?>" method="post">
	<?= _csrf() ?>
	<?= _method('put') ?>
	<div class="card mb-3">
		<div class="card-body">
			<div class="mb-3">
				<h5 class="card-title mb-1">
					<?= ucfirst(strtolower($examExercise['category'])) ?> Exam (<?= count($examExerciseAnswers) ?>)
				</h5>
				<p class="text-muted"><?= $examExercise['title'] ?></p>
			</div>

			<?php foreach ($examExerciseAnswers as $index => $answer): ?>
				<div class="card mb-2 border-primary">
					<div class="card-body p-3 d-flex">
						<p class="lead mb-0 mr-2"><?= $index + 1 ?>.</p>
						<div>
							<p class="lead mb-0"><?= ucfirst($answer['question']) ?></p>
							<div>
								<p class="mb-0 text-success font-weight-bold">Answer:</p>
								<p class="mb-1"><?= ucfirst(nl2br($answer['answer'])) ?></p>
							</div>
							<?php if (!empty($answer['attachment'])): ?>
								<div>
									<p class="mb-0 text-success font-weight-bold">
										Attachment:
										<span class="font-weight-normal text-body">
											<a href="<?= asset_url($answer['attachment']) ?>" class="text-link">
												<i class="mdi mdi-file-outline mr-1"></i>Download
											</a>
										</span>
									</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="card-footer pt-2">
						<label class="mb-2">
							Answer Key : <span class="text-fade"><?= if_empty($answer['question_answer'], '-') ?></span>
						</label>
						<div class="form-group">
							<label for="assessment-score-<?= $answer['id'] ?>">Score</label>
							<input type="number" class="form-control" id="assessment-score-<?= $answer['id'] ?>" name="assessments[<?= $answer['id'] ?>][score]"
								   required min="0" max="100" step="1" autocomplete="off"
								   value="<?= set_value('assessments[' . $answer['id'] . '][score]', $answer['score']) ?>" placeholder="Score">
							<?= form_error('assessments[' . $answer['id'] . '][score]') ?>
						</div>
						<div class="form-group">
							<label for="assessment-note-<?= $answer['id'] ?>">Assessment Note</label>
							<textarea class="form-control" id="assessment-note-<?= $answer['id'] ?>" name="assessments[<?= $answer['id'] ?>][assessment_note]" maxlength="500" rows="2"
									  placeholder="Enter assessment note"><?= set_value('assessments[' . $answer['id'] . '][assessment_note]', $answer['assessment_note'], $answer['assessment_note']) ?></textarea>
							<?= form_error('assessments[' . $answer['id'] . '][assessment_note]') ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
			<?php if(empty($examExerciseAnswers)): ?>
				<span class="text-muted">No answer result available</span>
			<?php endif; ?>
		</div>
	</div>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Assessment Summary</h5>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea class="form-control" id="description" name="description" maxlength="500"
						  placeholder="Enter exam description"><?= set_value('description', $examExercise['description']) ?></textarea>
				<?= form_error('description') ?>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body d-flex justify-content-between">
			<button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">
				Save Assessment
			</button>
		</div>
	</div>
</form>
