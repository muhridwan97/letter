<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
?>
<div class="bg-white d-flex flex-column position-fixed" id="classroom-sidebar-wrapper">
    <div class="sidebar-heading border-bottom">
        <a href="<?= site_url('training/class/view/' . $training['id']) ?>" class="text-link">
            <i class="mdi mdi-arrow-left mr-2"></i>Back To Training
        </a>
    </div>
    <div class="nav flex-column flex-nowrap classroom-nav">
		<div class="pb-3">
			<div class="nav-title d-flex align-items-center">
				<img src="<?= empty($training['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($training['cover_image']) ?>"
					 alt="Image cover" class="rounded mr-2" id="image-cover" style="width: 50px; height: 40px; object-fit: cover">
				<div>
					<p class="mb-0 text-info">CURRICULUM</p>
					<h6 class="text-body mb-0">
						<a href="<?= site_url("training/classroom/{$training['id']}") ?>" class="text-link">
							<?= $training['curriculum_title'] ?>
						</a>
					</h6>
				</div>
			</div>

			<hr class="mt-0 mb-2">
			<div class="nav-title text-fade pb-2<?= empty($this->uri->segment(4)) ? ' active' : '' ?>">
				<a href="<?= site_url("training/classroom/{$training['id']}") ?>" class="d-flex justify-content-between align-items-center mb-0 mr-2 text-link">
					<small>TABLE OF CONTENTS</small>
					<i class="mdi mdi-arrow-right"></i>
				</a>
			</div>
			<?php if (empty($courses)): ?>
				<div class="nav-title py-0 text-muted">
					No course and lesson available
				</div>
			<?php endif; ?>

			<?php foreach ($courses as $course): ?>
				<div class="nav-title position-relative<?= $this->uri->segment(5) == $course['id'] ? ' active' : '' ?>" id="nav-course-<?= $course['id'] ?>">
					<p class="mb-0 text-primary">COURSE</p>
					<h5 class="font-weight-bold text-body mb-0">
						<a href="<?= site_url("training/classroom/{$training['id']}/course/{$course['id']}") ?>" class="text-link stretched-link">
							<?= $course['course_title'] ?>
						</a>
					</h5>
				</div>

				<?php foreach ($course['lessons'] as $index => $lesson): ?>
					<div class="nav-item" id="nav-lesson-<?= $lesson['id'] ?>">
						<a class="nav-link<?= get_url_param('lesson') == $lesson['id'] ? ' active' : '' ?>"
						   href="<?= site_url("training/classroom/{$training['id']}/course/{$course['id']}?lesson=" . $lesson['id']) ?>">
							<?= $index + 1 ?>. &nbsp; <i class="<?= get_file_icon($lesson['source']) ?> mr-2"></i>
							<?= $lesson['lesson_title'] ?>
						</a>
						<?php if(!empty($lesson['exercises'])): ?>
							<ul class="nav flex-column sub-menu">
								<?php foreach ($lesson['exercises'] as $exercise): ?>
									<li class="nav-item" id="nav-exercise-<?= $exercise['id'] ?>">
										<a class="nav-link<?= get_url_param('exercise') == $exercise['id'] ? ' active' : '' ?>"
										   href="<?= site_url("training/classroom/{$training['id']}/course/{$course['id']}?exercise={$exercise['id']}") ?>">
											<i class="mdi mdi-ballot-outline mr-2"></i>
											<div>
											<span class="sub-text-menu">
												<?= ucfirst(strtolower($exercise['category'])) ?> Questions (<?= $exercise['total_questions'] ?>)
											</span>
												<small class="d-block text-muted"><?= $exercise['exercise_title'] ?></small>
											</div>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
				<hr>
			<?php endforeach; ?>
		</div>
    </div>
</div>
