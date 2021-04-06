<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
		<div>
			<h5 class="card-title mb-1 text-primary">
				Exercise - <?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= count($exercise['questions']) ?>)
			</h5>
			<h6 class="mb-0">
				<?= $exercise['exercise_title'] ?>
			</h6>
		</div>
		<h2 class="mb-0 font-weight-bold">
			<time id="quiz-duration" class="text-info px-2 rounded" data-time="<?= $exercise['duration'] ?>" data-skip-submit="<?= $exercise['category'] == ExerciseModel::CATEGORY_CHOICES ? 0 : 1 ?>">
				<?= $exercise['duration'] ?>
			</time>
		</h2>
	</div>
</div>

<form action="<?= site_url("training/classroom/{$training['id']}/exercise/{$exercise['id']}") ?>" id="form-quiz" method="post">
	<?= _csrf() ?>
	<?php foreach ($exercise['questions'] as $index => $question): ?>
		<div class="card mb-3">
			<div class="card-body">
				<div class="mb-2">
					<div class="d-flex">
						<p class="lead mb-0 mr-2"><?= $index + 1 ?>.</p>
						<p class="lead mb-0"><?= ucfirst($question['question']) ?></p>
					</div>
					<?php if (!empty($question['attachment'])): ?>
						<p class="mb-1 text-fade">
							Attachment:
							<a class="btn-link" href="<?= site_url('media/view?source=' . $question['attachment']) ?>" target="_blank">
								<?= basename($question['attachment']) ?>
							</a>
						</p>
					<?php endif; ?>
					<p class="text-muted mb-0">
						<?= ucfirst($question['hint']) ?>
					</p>
				</div>
				<div class="ml-4">
					<?php if($exercise['category'] == ExerciseModel::CATEGORY_CHOICES): ?>
						<?php foreach($question['answer_choices'] as $answerChoice): ?>
							<div class="custom-control custom-radio">
								<input type="radio" aria-label="<?= $answerChoice['answer'] ?>" name="answers[<?= $question['id'] ?>]"
									   class="custom-control-input" id="answer-choice-<?= $answerChoice['id'] ?>" value="<?= $answerChoice['id'] ?>">
								<label class="custom-control-label font-weight-normal" for="answer-choice-<?= $answerChoice['id'] ?>">
									<?= ucfirst($answerChoice['answer']) ?>
								</label>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<p class="font-weight-bold text-success">Answer:</p>
						<p>
							. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
							. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="d-flex justify-content-between">
		<a href="<?= site_url('syllabus/exercise/print-question/' . $exercise['id']) ?>" class="btn btn-outline-info" target="_blank">
			<i class="mdi mdi-printer"></i> Print
		</a>
		<div>
			<?php if($exercise['category'] == ExerciseModel::CATEGORY_CHOICES): ?>
				<button type="reset" class="btn btn-outline-danger">Reset</button>
				<button type="submit" class="btn btn-primary">Submit Answer</button>
			<?php else: ?>
				<button type="button" class="btn btn-outline-secondary" disabled>
					Discuss with your Trainer
				</button>
			<?php endif; ?>
		</div>
	</div>
</form>

<?php $this->load->view('classroom/_modal_timeout') ?>
