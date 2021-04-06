<div class="card mb-3">
    <div class="card-body">
		<h5 class="card-title mb-0 text-primary">
			Exercise - <?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= count($exercise['questions']) ?>) Result
		</h5>
	</div>
</div>

<div class="card mb-3">
	<div class="card-body text-center">
		<p class="text-fade mb-0">Lesson: <?= $lesson['lesson_title'] ?></p>
		<h5 class="text-primary">Exercise: <?= $exercise['exercise_title'] ?></h5>
		<hr>
		<h6>Corrects: <?= $correct ?> / <?= count($exercise['questions']) ?></h6>
		<h4 class="font-weight-bold">Your Score</h4>
		<h1 class="display-3 font-weight-bold mb-4 <?= $score > 50 ? 'text-primary' : 'text-danger' ?>">
			<?= numerical($score, 1) ?>
		</h1>
		<div class="d-flex justify-content-between mb-3">
			<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?lesson=" . $lesson['id']) ?>" class="btn btn-info">
				Back To Lesson
			</a>
			<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?exercise={$exercise['id']}") ?>" class="btn btn-primary">
				<i class="mdi mdi-reload mr-1"></i>Try Again
			</a>
			<?php if (!empty($nextExercise)): ?>
				<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?exercise={$nextExercise['id']}") ?>" class="btn btn-info">
					Next Exercise<i class="mdi mdi-arrow-right ml-1"></i>
				</a>
			<?php elseif (!empty($nextLesson)): ?>
				<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?lesson={$nextLesson['id']}") ?>" class="btn btn-info">
					Next Lesson<i class="mdi mdi-arrow-right ml-1"></i>
				</a>
			<?php else: ?>
				<button disabled class="btn btn-info disabled">
					Next Lesson<i class="mdi mdi-arrow-right ml-1"></i>
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php if (!empty($trainingExerciseScores)): ?>
	<div class="card mb-3">
		<div class="card-body">
			<table class="table table-md responsive">
				<thead>
				<tr>
					<th class="border-top-0" style="width: 50px">Attempt</th>
					<th class="border-top-0">Date</th>
					<th class="border-top-0">Question</th>
					<th class="border-top-0">Correct</th>
					<th class="border-top-0">Score</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($trainingExerciseScores as $index => $trainingExerciseScore): ?>
					<tr>
						<td><?= $index + 1 ?></td>
						<td><?= format_date($trainingExerciseScore['created_at'], 'd F Y H:i') ?></td>
						<td><?= numerical($trainingExerciseScore['total_question']) ?> items</td>
						<td><?= numerical($trainingExerciseScore['correct']) ?></td>
						<td class="font-weight-bold text-<?= $trainingExerciseScore['score'] > 50 ? 'success' : 'danger' ?>">
							<?= numerical($trainingExerciseScore['score'], 1) ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>
