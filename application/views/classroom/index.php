<div class="card mb-3">
    <div class="card-body">
		<h5 class="card-title mb-0 text-primary">Classroom</h5>
	</div>
</div>
<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-md-4">
				<img src="<?= empty($training['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($training['cover_image']) ?>"
					 alt="Image cover" class="img-fluid mb-3" id="image-cover" style="object-fit: cover; width: 100%">
			</div>
			<div class="col-md-8">
				<div class="mb-3">
					<p class="font-weight-bold text-info mb-0">Curriculum Title</p>
					<h4 class="mb-1"><?= $training['curriculum_title'] ?></h4>
					<p class="text-muted"><?= $training['description'] ?></p>
				</div>

				<div class="mb-3">
					<p class="font-weight-bold text-info mb-0">Assigned Employee</p>
					<h5 class="mb-1"><?= $training['employee_name'] ?></h5>
				</div>

				<div class="mb-3">
					<p class="font-weight-bold text-info mb-0">Assigned At</p>
					<h5 class="mb-1"><?= format_date($training['created_at'], 'd F Y H:i') ?></h5>
				</div>

				<div class="mb-3">
					<p class="font-weight-bold text-info mb-0">Total Courses</p>
					<h5 class="mb-1"><?= numerical(count($courses)) ?> items</h5>
				</div>
			</div>
		</div>
	</div>
	<?php if(!empty($courses)): ?>
		<div class="card-footer bg-white d-flex">
			<a href="<?= site_url("training/classroom/{$training['id']}/course/{$courses[0]['id']}") ?>"
			   class="btn btn-info flex-grow-1 flex-lg-grow-0">
				Course Learning <i class="mdi mdi-arrow-right ml-2"></i>
			</a>
		</div>
	<?php endif; ?>
</div>

