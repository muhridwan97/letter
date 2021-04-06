<div class="card mb-3">
    <div class="card-body">
		<div class="d-flex justify-content-between align-items-center mb-3">
			<h5 class="card-title mb-0 text-primary">Lesson: <?= $lesson['lesson_title'] ?></h5>
			<button type="button" class="btn btn-sm btn-primary btn-fullscreen" data-target="#media-viewer-wrapper" title="Toggle fullscreen">
				<i class="mdi mdi-window-maximize"></i> Fullscreen
			</button>
		</div>
		<?php $this->load->view('media/_media_viewer', [
			'title' => $lesson['lesson_title'],
			'source' => $lesson['source'],
			'mime' => $lesson['mime']
		]) ?>
	</div>
	<div class="card-footer bg-white d-flex justify-content-between">
		<div>
			<p class="mb-1"><strong>Description:</strong> <?= $lesson['description'] ?></p>
			<p class="text-fade mb-0">
				Last Updated: <?= format_date(if_empty($lesson['updated_at'], $lesson['created_at']), 'd F Y H:i') ?>
			</p>
		</div>
		<?php if (!empty($nextLesson)): ?>
			<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?lesson={$nextLesson['id']}") ?>" class="btn btn-info align-self-center">
				Next Lesson<i class="mdi mdi-arrow-right ml-1"></i>
			</a>
		<?php elseif (!empty($nextCourse)): ?>
			<a href="<?= site_url("training/classroom/{$training['id']}/course/{$nextCourse['id']}") ?>" class="btn btn-danger align-self-center">
				Next Course<i class="mdi mdi-arrow-right ml-1"></i>
			</a>
		<?php endif; ?>
	</div>
</div>

<?php if (!empty($lesson['exercises'])): ?>
	<div class="card mb-3">
		<div class="card-body">
			<ul class="list-group list-group-flush">
				<li class="list-group-item font-weight-bold">Lesson Exercises</li>
				<?php foreach ($lesson['exercises'] as $exercise): ?>
					<li class="list-group-item d-flex justify-content-between align-items-center">
						<?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= $exercise['total_questions'] ?>) - <?= $exercise['exercise_title'] ?>
						<a href="<?= site_url("training/classroom/{$training['id']}/course/{$lesson['id_course']}?exercise={$exercise['id']}") ?>" class="btn btn-warning btn-sm">
							Start Exercise
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
