<form class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View Department</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Department Title</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $department['department'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Total Employee</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $department['total_employee'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Total Curriculum</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $department['total_curriculums'] ?>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($department['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($department['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($department['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<?php
	$curriculumStatuses = [
		CurriculumModel::STATUS_ACTIVE => 'success',
		CurriculumModel::STATUS_INACTIVE => 'danger',
	];
	?>

	<div class="d-flex justify-content-between mb-2">
		<h5 class="card-title">Curriculums</h5>
		<div>
			<a href="<?= site_url('syllabus/curriculum/sort/' . $department['id']) ?>" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-title="Sort curriculum">
				SORT <i class="mdi mdi-sort-variant"></i>
			</a>
			<a href="<?= site_url('syllabus/curriculum/create/' . $department['id'] . '?redirect=' . site_url(uri_string())) ?>" class="btn btn-primary btn-sm" data-toggle="tooltip" data-title="Add curriculum">
				<i class="mdi mdi-plus"></i>
			</a>
		</div>
	</div>
	<?php foreach ($curriculums as $curriculum): ?>
		<div class="card mb-3 d-flex flex-row overflow-hidden">
			<img src="<?= empty($curriculum['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($curriculum['cover_image']) ?>"
				 alt="Image cover" id="image-cover" style="width: 120px; height: 120px; object-fit: cover">
			<div class="d-flex flex-fill">
				<div class="card-body d-flex justify-content-between">
					<div>
						<a href="<?= site_url('syllabus/curriculum/view/' . $curriculum['id']) ?>" class="stretched-link">
							<h5 class="font-weight-bold mb-1"><?= $curriculum['curriculum_title'] ?></h5>
						</a>
						<p class="mb-0 text-muted">
							<?= word_limiter(if_empty($curriculum['description'], 'No description'), 20) ?>
						</p>
						<span class="text-fade small">
							<?= $curriculum['total_courses'] ?> Courses - Last updated at <?= format_date(if_empty($curriculum['updated_at'], $curriculum['created_at']), 'd M Y H:i') ?>
						</span>
					</div>
					<div>
						<span class="badge badge-<?= get_if_exist($curriculumStatuses, $curriculum['status'], 'primary') ?>">
							<?= $curriculum['status'] ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if(empty($curriculums)): ?>
		<div class="card mb-3">
			<div class="card-body">
				<p class="text-muted mb-0">No curriculums available</p>
			</div>
		</div>
	<?php endif; ?>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(!$this->config->item('sso_enable')): ?>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_EDIT)): ?>
                    <a href="<?= site_url('master/department/edit/' . $department['id']) ?>" class="btn btn-primary">
                        Edit Department
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</form>
