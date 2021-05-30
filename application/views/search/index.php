<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h5 class="card-title">Scan Data</h5>
        </div>
		<form action="<?= site_url('search') ?>" method="get">
			<div class="form-group mb-2">
				<div class="input-group search-input">
					<input type="text" class="form-control" maxlength="50" name="q" id="q" aria-label="Search"
						   autofocus placeholder="Search data..." value="<?= get_url_param('q') ?>" required>
					<div class="input-group-append">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div>
			</div>
		</form>
    </div>
</div>

<?php if(!empty(get_url_param('q'))): ?>
	<?php
	$skripsiStatuses = [
		SkripsiModel::STATUS_ACTIVE => 'success',
		SkripsiModel::STATUS_REJECTED => 'danger',
		SkripsiModel::STATUS_PENDING => 'secondary',
	];
	?>
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h5 class="card-title mb-sm-0">Result of Skripsi</h5>
	</div>
	<div class="form-row mb-4">
		<?php foreach ($skripsis as $index => $skripsi): ?>
			<div class="col-sm-6 mb-3">
				<div class="card d-flex flex-row overflow-hidden">
					<div class="d-flex flex-fill">
						<div class="card-body d-flex justify-content-between">
							<div>
								<a href="<?= site_url('skripsi/skripsi/view/' . $skripsi['id']) ?>" class="stretched-link">
									<h5 class="font-weight-bold mb-1"><?= $skripsi['judul'] ?></h5>
								</a>
								<p class="mb-0 text-muted">
									<?= word_limiter(if_empty($skripsi['description'], 'No description'), 20) ?>
								</p>
								<span class="text-fade small">
									<?= $skripsi['total_logbook'] ?> Courses - Last updated at <?= format_date(if_empty($skripsi['updated_at'], $skripsi['created_at']), 'd M Y H:i') ?>
								</span>
							</div>
							<div>
								<span class="badge badge-<?= get_if_exist($skripsiStatuses, $skripsi['status'], 'primary') ?>">
									<?= $skripsi['status'] ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($skripsis)): ?>
			<div class="col text-muted">
				No any skripsis found
			</div>
		<?php endif; ?>
	</div>


	<?php
	$logbookStatuses = [
		LogbookModel::STATUS_VALIDATE => 'success',
		LogbookModel::STATUS_REJECTED => 'danger',
		LogbookModel::STATUS_PENDING => 'secondary',
	];
	?>
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h5 class="card-title mb-sm-0">Result of Logbooks</h5>
	</div>
	<div class="form-row mb-4">
		<?php foreach ($logbooks as $logbook): ?>
			<div class="col-6 col-md-3 mb-3">
				<div class="card h-100">
					<div class="card-body">
						<div class="d-flex flex-fill flex-column">
							<a href="<?= site_url('skripsi/logbook/view/' . $logbook['id']) ?>">
								<h6 class="font-weight-bold mb-1"><?= $logbook['konsultasi'] ?></h6>
							</a>
							<p class="mb-0 text-muted">
								<?= word_limiter(if_empty($logbook['description'], 'No description'), 20) ?>
							</p>
						</div>
					</div>
					<div class="card-footer bg-white">
						<div class="d-flex justify-content-between align-items-center">
							<span class="text-fade small">
								<?= $logbook['nama_mahasiswa'] ?>
							</span>
							<span class="badge badge-<?= get_if_exist($logbookStatuses, $logbook['status'], 'primary') ?>">
								<?= $logbook['status'] ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
		<?php if(empty($logbooks)): ?>
			<div class="col text-muted">
				No any logbooks found
			</div>
		<?php endif; ?>
	</div>

<?php endif; ?>
