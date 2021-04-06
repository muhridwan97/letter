<div class="form-group mb-2 answer-item">
	<div class="input-group">
		<div class="input-group-prepend">
			<div class="input-group-text">
				<div class="custom-control custom-control-no-label custom-radio">
					<input type="radio" id="answer_choice_<?= $questionIndex ?? '' ?>_<?= $answerIndex ?? '' ?>"
						   name="questions[<?= $questionIndex ?? '' ?>][answers][<?= $answerIndex ?? '' ?>][is_correct_answer]"
						   <?= in_array(($isCorrectAnswer ?? ''), [1, 'on']) ? 'checked' : ($isCorrectAnswer ?? '') ?>
						   data-checked="<?= in_array(($isCorrectAnswer ?? ''), [1, 'on']) ? 'checked' : ($isCorrectAnswer ?? '') ?>"
						   class="custom-control-input radio-answer-choice">
					<label class="custom-control-label" for="answer_choice_<?= $questionIndex ?? '' ?>_<?= $answerIndex ?? '' ?>"></label>
				</div>
			</div>
		</div>
		<input type="text" class="form-control input-answer" placeholder="Answer choice <?= $answerOrder ?? '' ?>" aria-label="Answer" name="questions[<?= $questionIndex ?? '' ?>][answers][<?= $answerIndex ?? '' ?>][answer]" value="<?= $answer ?? '' ?>" required>
		<input type="hidden" name="questions[<?= $questionIndex ?? '' ?>][answers][<?= $answerIndex ?? '' ?>][id]" value="<?= $answerRowId ?? '' ?>">
		<div class="input-group-append">
			<button class="btn btn-warning btn-sm btn-remove-answer">
				<i class="mdi mdi-trash-can-outline"></i>
			</button>
		</div>
	</div>
</div>
