<div class="card mb-3">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-start mb-4">
			<div>
				<h5 class="card-title mb-1">
					<?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= count($questions) ?>)
				</h5>
				<p class="text-muted mb-0"><?= $exercise['exercise_title'] ?></p>
			</div>
			<?php if (AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_EDIT)): ?>
				<a href="<?= site_url('syllabus/exercise/edit/' . $exercise['id']) ?>" class="btn btn-info">
					Edit
				</a>
			<?php endif; ?>
		</div>
		<?php foreach ($questions as $question): ?>
			<div class="card mb-3 border-primary">
				<div class="card-body">
					<div class="mb-2">
						<p class="lead mb-0"><?= ucfirst($question['question']) ?></p>
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
					<div class="ml-2">
						<?php if($exercise['category'] == ExerciseModel::CATEGORY_CHOICES): ?>
							<?php foreach($question['answer_choices'] as $answerChoice): ?>
								<div class="custom-control custom-radio">
									<input type="radio" aria-label="<?= $answerChoice['answer'] ?>" readonly class="custom-control-input"<?= $answerChoice['is_correct_answer'] ? ' checked' : '' ?>>
									<label class="custom-control-label font-weight-normal<?= $answerChoice['is_correct_answer'] ? ' text-success' : '' ?>">
										<?= ucfirst($answerChoice['answer']) ?>
									</label>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<p class="text-success">
								<span class="font-weight-bold">Answer:</span>
								<br>
								<?= ucfirst(nl2br($question['answer'])) ?>
							</p>
						<?php endif; ?>
					</div>
					<hr>
					<p class="mb-0 text-fade">Description: <?= if_empty($question['description'], 'No description') ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
