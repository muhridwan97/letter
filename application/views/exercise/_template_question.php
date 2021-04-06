<div class="card mb-3 border-primary question-item" data-index="<?= $questionIndex ?? '' ?>">
	<div class="card-header bg-white d-flex justify-content-between align-items-center">
		<div>
			<div class="btn btn-light btn-sm cursor-pointer px-2 mr-2 handle-order-question">
				<i class="mdi mdi mdi-menu"></i>
			</div>
			<strong>Question #<span class="item-order"><?= $questionOrder ?? '' ?></span></strong>
		</div>
		<div>
			<button type="button" class="btn btn-light btn-sm active" data-toggle="collapse" data-target="#collapse-question-<?= $questionOrder ?? '' ?>">
				<i class="mdi mdi-chevron-down"></i>
			</button>
			<button type="button" class="btn btn-danger btn-sm btn-remove-question">
				<i class="mdi mdi-trash-can-outline"></i>
			</button>
		</div>
	</div>
	<div class="question-content-item collapse show" id="collapse-question-<?= $questionOrder ?? '' ?>">
		<div class="card-body">
			<input type="hidden" name="questions[<?= $questionIndex ?? '' ?>][id]" value="<?= $questionRowId ?? '' ?>">
			<input type="hidden" name="questions[<?= $questionIndex ?? '' ?>][question_order]" value="<?= $questionOrder ?? '' ?>" class="input-order">
			<div class="form-group">
				<label for="question">Question</label>
				<input type="text" class="form-control" id="question" name="questions[<?= $questionIndex ?? '' ?>][question]" required maxlength="500"
					   value="<?= $question ?? '' ?>" placeholder="Question content">
			</div>
			<div class="form-row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="hint">Question Hint</label>
						<input type="text" class="form-control" id="hint" name="questions[<?= $questionIndex ?? '' ?>][hint]" maxlength="100"
							   value="<?= $hint ?? '' ?>" placeholder="Hint of answer or direction (optional)">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="attachment" class="d-none d-md-block">Attachment</label>
						<input type="file" id="attachment" name="questions[<?= $questionIndex ?? '' ?>][attachment]" class="file-upload-default" data-max-size="1000000">
						<div class="input-group">
							<input type="text" class="form-control file-upload-info" disabled placeholder="Upload attachment" value="<?= $attachment ?? '' ?>" aria-label="Attachment">
							<div class="input-group-append">
								<button class="file-upload-browse btn btn-outline-primary btn-simple-upload" type="button">
									Pick File
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea type="text" class="form-control" id="description" name="questions[<?= $questionIndex ?? '' ?>][description]" maxlength="500"
						  placeholder="Description will be hidden for users"><?= $description ?? '' ?></textarea>
			</div>
		</div>
		<div class="card-footer">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h5 class="card-title mb-0">Answer #<span class="item-order"><?= $questionOrder ?? '' ?></span></h5>
				<?php if(($exerciseCategory ?? ExerciseModel::CATEGORY_CHOICES) == ExerciseModel::CATEGORY_CHOICES): ?>
					<button type="button" class="btn btn-success btn-sm btn-add-answer-choice">
						Add Answer
					</button>
				<?php endif; ?>
			</div>
			<div class="answer-wrapper">
				<?php if(($exerciseCategory ?? ExerciseModel::CATEGORY_CHOICES) == ExerciseModel::CATEGORY_CHOICES): ?>
					<div class="py-2 px-3 mb-2 text-muted answer-placeholder"
						 style="border: 1px dashed #aaaaaa; font-size: 14px; <?= empty($answers ?? []) ? '' : 'display: none;' ?>">
						Click <strong>add answer</strong> button to create choices
					</div>
					<?php foreach ($answers ?? [] as $answerIndex => $answer): ?>
						<?php $this->load->view('exercise/_template_answer_choice', [
							'questionIndex' => $questionIndex ?? '',
							'answerIndex' => $answerIndex,
							'answerOrder' => $answerIndex + 1,
							'answerRowId' => get_if_exist($answer, 'id'), // for data creation may has not id yet
							'answer' => $answer['answer'],
							'isCorrectAnswer' => get_if_exist($answer, 'is_correct_answer'),
						]) ?>
					<?php endforeach; ?>
				<?php else: ?>
					<?php $this->load->view('exercise/_template_answer_essay', [
							'questionIndex' => $questionIndex ?? '',
							'answer' => $answer ?? '',
					]) ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
