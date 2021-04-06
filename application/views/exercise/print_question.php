<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Question - <?= $exercise['exercise_title'] ?></title>

	<style type="text/css">
		@page {
			margin: 25px;
		}

		body {
			margin: 25px;
			font-size: 12px;
		}

		* {
			font-family: sans-serif;
			margin: 0;
			padding: 0;
		}

		.text-small {
			font-size: 10px;
		}

		.table {
			border: 1px solid #333;
			border-collapse: collapse;
			margin: 0 auto 10px;
			width: 740px;
			font-size: 12px;
		}

		.table td, .table tr, .table th {
			padding: 3px 5px;
			border: 1px solid #333;
		}

		.table th {
			background-color: #f0f0f0;
		}

		.table-sm td, .table-sm tr, .table-sm th {
			padding: 5px;
			font-size: 11px;
		}

		.table-info td, .table-info th {
			padding: 8px;
		}

		.text-center {
			text-align: center;
		}

		.text-left {
			text-align: left;
		}

		.font-weight-bold {
			font-weight: bold;
		}

		.mb-1 {
			margin-bottom: 0.25rem;
		}

		.mb-2 {
			margin-bottom: 0.5rem;
		}

		.mr-2 {
			margin-right: 0.5rem;
		}

		.check-box {
			display: inline-block;
			height: 8px;
			width: 8px;
			border: 1px solid #333333;
		}

		.text-primary {
			color: #54ac93;
		}

		.text-muted {
			color: #6c757d;
		}
	</style>

</head>
<body>
<h3><?= $exercise['exercise_title'] ?></h3>
<p class="font-weight-bold text-primary">
	<?= if_empty(ucwords(str_replace(['-', '_'], ' ', $exercise['type'])), 'Collection') ?> - <?= if_empty($exercise['reference_title'], 'Question') ?>
</p>
<p class="text-muted">
	<?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= count($questions) ?>)
</p>
<br>

<ol style="padding-left: 20px">
<?php foreach ($questions as $question): ?>
	<li class="mb-2">
		<p class="mb-1"><?= ucfirst($question['question']) ?></p>
		<p class="text-muted mb-1 text-small"><?= if_empty(ucfirst($question['hint']), '', '(Hint: ', ')') ?></p>
		<?php if($exercise['category'] == ExerciseModel::CATEGORY_CHOICES): ?>
			<?php foreach($question['answer_choices'] as $answerChoice): ?>
				<p><span class="check-box mr-2"></span><?= ucfirst($answerChoice['answer']) ?></p>
			<?php endforeach; ?>
		<?php else: ?>
			<p class="text-success">
				<span class="font-weight-bold mb-1">Answer:</span>
				<br>
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .
				<br>
				<br>
				<br>
			</p>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>

</body>
</html>
