<form action="<?= site_url('syllabus/curriculum/sort/' . $department['id'] . '?redirect=' . get_url_param('redirect')) ?>" method="POST" class="form-sort-curriculum">
	<?= _csrf() ?>
	<?= _method('put') ?>
	<div class="card mb-3 form-plaintext">
		<div class="card-body">
			<h5 class="card-title">Department</h5>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Department</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $department['department'] ?>
					</p>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 col-form-label">Total Curriculum</label>
				<div class="col-sm-9">
					<p class="form-control-plaintext">
						<?= $department['total_curriculums'] ?>
					</p>
				</div>
			</div>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Sort Curriculum</h5>
			<div class="list-group list-group-flush" id="curriculum-sortable-wrapper">
				<?php foreach ($curriculums as $index => $curriculum): ?>
					<div class="list-group-item curriculum-list-item">
						<div class="btn btn-light btn-sm cursor-pointer handle-order-curriculum px-2 mr-2">
							<i class="mdi mdi-menu-swap"></i>
						</div>
						<span class="order-number"><?= $index + 1 ?></span>. <span class="ml-2"><?= $curriculum['curriculum_title'] ?></span>
						<input type="hidden" class="input-order" name="curriculum_orders[<?= $curriculum['id'] ?>]" value="<?= $index + 1 ?>">
					</div>
				<?php endforeach; ?>
			</div>
			<?php if (empty($curriculums)): ?>
				No curriculum available
			<?php endif; ?>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body d-flex justify-content-between">
			<button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if (!empty($curriculums)): ?>
				<button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
					Sort Curriculum
				</button>
			<?php endif; ?>
		</div>
	</div>
</form>
