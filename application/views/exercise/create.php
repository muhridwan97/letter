<form action="<?= site_url('syllabus/exercise/save?redirect=' . get_url_param('redirect')) ?>" method="POST" id="form-exercise" enctype="multipart/form-data">
    <?= _csrf() ?>

	<?php if (!empty($type) && !empty($exercisable)): ?>
		<div class="card border-<?= $type == ExerciseModel::TYPE_LESSON_EXERCISE ? 'primary' : 'danger' ?> mb-3">
			<div class="card-body">
				<input type="hidden" name="type" value="<?= $type ?>">
				<input type="hidden" name="reference_id" value="<?= $referenceId ?? $exercisable['id'] ?>">
				<?php if($type == ExerciseModel::TYPE_LESSON_EXERCISE): ?>
					<div class="d-flex flex-row align-items-center">
						<button type="button" class="btn btn-outline-primary mr-3 btn-sm" onclick="history.back()">
							<i class="mdi mdi-arrow-left"></i>
						</button>
						<div>
							<h5 class="card-title text-primary mb-1">
								Lesson Exercise: <?= $exercisable['lesson_title'] ?>
							</h5>
							<p class="mb-0 text-muted">
								<?= $exercisable['course_title'] ?> - <?= $exercisable['curriculum_title'] ?>
							</p>
						</div>
					</div>
				<?php endif; ?>
				<?php if($type == ExerciseModel::TYPE_CURRICULUM_EXAM): ?>
					<div class="d-flex flex-row align-items-center">
						<button type="button" class="btn btn-outline-danger mr-3 btn-sm" onclick="history.back()">
							<i class="mdi mdi-arrow-left"></i>
						</button>
						<div>
							<h5 class="card-title text-danger mb-1">
								Curriculum Exam: <?= $exercisable['curriculum_title'] ?>
							</h5>
							<p class="mb-0 text-muted">
								<?= $exercisable['department'] ?> department
							</p>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Create <?= if_empty(ucwords(str_replace(['-', '_'], ' ', $type ?? '')), 'Collection') ?></h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="exercise_title"><?= $type == ExerciseModel::TYPE_LESSON_EXERCISE ? 'Exercise' : 'Exam' ?> Title</label>
						<input type="text" class="form-control" id="exercise_title" name="exercise_title" required maxlength="100"
							   value="<?= set_value('exercise_title') ?>" placeholder="Exercise title">
						<?= form_error('exercise_title') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="category">Category</label>
						<select class="form-control select2" id="category" name="category" data-placeholder="Select category" required style="width: 100%">
							<option value="">No category</option>
							<option value="<?= ExerciseModel::CATEGORY_CHOICES ?>"<?= set_select('category', ExerciseModel::CATEGORY_CHOICES) ?>>
								<?= ExerciseModel::CATEGORY_CHOICES ?>
							</option>
							<option value="<?= ExerciseModel::CATEGORY_ESSAY ?>"<?= set_select('category', ExerciseModel::CATEGORY_ESSAY) ?>>
								<?= ExerciseModel::CATEGORY_ESSAY ?>
							</option>
							<option value="<?= ExerciseModel::CATEGORY_PRACTICE ?>"<?= set_select('category', ExerciseModel::CATEGORY_PRACTICE) ?>>
								<?= ExerciseModel::CATEGORY_PRACTICE ?>
							</option>
						</select>
						<?= form_error('category') ?>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="question_sequence">Question Order</label>
						<select class="form-control select2" id="question_sequence" name="question_sequence" data-placeholder="Select question sequence" required style="width: 100%">
							<option value="">No order</option>
							<option value="<?= ExerciseModel::QUESTION_SEQUENCE_RANDOM ?>"<?= set_select('question_order', ExerciseModel::QUESTION_SEQUENCE_RANDOM) ?>>
								<?= ExerciseModel::QUESTION_SEQUENCE_RANDOM ?>
							</option>
							<option value="<?= ExerciseModel::QUESTION_SEQUENCE_IN_ORDER ?>"<?= set_select('category', ExerciseModel::QUESTION_SEQUENCE_IN_ORDER) ?>>
								<?= ExerciseModel::QUESTION_SEQUENCE_IN_ORDER ?>
							</option>
						</select>
						<?= form_error('question_sequence') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label for="duration_hour">Duration Hours</label>
								<select class="form-control select2" id="duration_hour" name="duration_hour" data-placeholder="Hour" required style="width: 100%">
									<option value="">Hour</option>
									<?php for ($i = 0; $i < 24; $i++): ?>
										<?php $value = str_pad($i, 2, '0', STR_PAD_LEFT) ?>
										<option value="<?= $value ?>"<?= set_select('duration_hour', $value) ?>>
											<?= $value ?>
										</option>
									<?php endfor; ?>
								</select>
								<?= form_error('duration_hour') ?>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="duration_minute">Minutes</label>
								<select class="form-control select2" id="duration_minute" name="duration_minute" data-placeholder="Minute" required style="width: 100%">
									<option value="">Hour</option>
									<?php for ($i = 0; $i < 60; $i++): ?>
										<?php $value = str_pad($i, 2, '0', STR_PAD_LEFT) ?>
										<option value="<?= $value ?>"<?= set_select('duration_minute', $value) ?>>
											<?= $value ?>
										</option>
									<?php endfor; ?>
								</select>
								<?= form_error('duration_minute') ?>
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label for="duration_second">Seconds</label>
								<select class="form-control select2" id="duration_second" name="duration_second" data-placeholder="Second" required style="width: 100%">
									<option value="">Hour</option>
									<?php for ($i = 0; $i < 60; $i++): ?>
										<?php $value = str_pad($i, 2, '0', STR_PAD_LEFT) ?>
										<option value="<?= $value ?>"<?= set_select('duration_second', $value) ?>>
											<?= $value ?>
										</option>
									<?php endfor; ?>
								</select>
								<?= form_error('duration_second') ?>
							</div>
						</div>
					</div>
				</div>
			</div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter description"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

	<div class="card mb-3">
		<div class="card-body">
			<div class="d-flex justify-content-between mb-3">
				<h5 class="card-title">Questions</h5>
				<div>
					<div class="d-inline-block" data-toggle="tooltip" data-title="Toggle collapse questions">
						<button type="button" class="btn btn-warning btn-sm btn-collapse-questions">
							<i class="mdi mdi-arrow-collapse"></i>
						</button>
					</div>
					<button type="button" class="btn btn-primary btn-sm btn-add-question">
						Add Question
					</button>
				</div>
			</div>

			<div class="question-wrapper">
				<?php $questions = if_empty(set_value('questions[]'), []); ?>
				<div class="py-2 px-3 mb-2 text-muted question-placeholder"
					 style="border: 1px dashed #aaaaaa; font-size: 14px; <?= empty($questions) ? '' : 'display: none;' ?>">
					Click <strong>add question</strong> button to create list
				</div>
				<?php foreach ($questions as $questionIndex => $question): ?>
					<?php $this->load->view('exercise/_template_question', [
						'questionIndex' => $questionIndex,
						'questionOrder' => $questionIndex + 1,
						// data creation has not id yet: 'questionRowId' => $question['id'],
						'question' => $question['question'],
						'hint' => $question['hint'],
						//'attachment' => $question['attachment'],
						'description' => $question['description'],
						'answers' => $question['answers'],
					]) ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">
				Save <?= if_empty(ucwords(str_replace(['-', '_'], ' ', $type)), 'Collection') ?>
			</button>
        </div>
    </div>
</form>

<script id="question-template" type="x-tmpl-mustache">
	<?php $this->load->view('exercise/_template_question', [
		'questionIndex' => '{{ question_index }}',
		'questionOrder' => '{{ question_order }}',
		'question' => '{{ question }}',
		'hint' => '{{ hint }}',
		'attachment' => '{{ attachment }}',
		'description' => '{{ description }}',
	]) ?>
</script>
<script id="answer-choice-template" type="x-tmpl-mustache">
	<?php $this->load->view('exercise/_template_answer_choice', [
		'answerIndex' => '{{ answer_index }}',
		'answerOrder' => '{{ answer_order }}',
		'answer' => '{{ answer }}',
		'isCorrectAnswer' => '{{ is_correct_answer }}',
	]) ?>
</script>
<script id="answer-essay-template" type="x-tmpl-mustache">
	<?php $this->load->view('exercise/_template_answer_essay', [
		'questionIndex' => '{{ question_index }}',
		'answer' => '{{ answer }}',
	]) ?>
</script>
<?php $this->load->view('partials/modals/_alert') ?>
