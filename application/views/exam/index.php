<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Exam</h5>
            <div>
				<div class="btn-group">
					<?php if(!empty($_GET)): ?>
						<a href="<?= site_url(uri_string()) ?>" class="btn btn-danger btn-sm px-2" data-toggle="tooltip" data-title="Reset Filter">
							<i class="mdi mdi-close"></i>
						</a>
					<?php endif; ?>
					<a href="#modal-filter" data-toggle="modal" class="btn <?= !empty($_GET) ? 'btn-danger active' : 'btn-info' ?> btn-sm pr-2 pl-2">
						<i class="mdi <?= !empty($_GET) ? '' : 'mdi-filter-variant' ?>"></i><?= !empty($_GET) ? ' Filtered' : '' ?>
					</a>
				</div>
                <a href="<?= base_url(uri_string()) ?>?<?= $_SERVER['QUERY_STRING'] ?>&export=true" class="btn btn-info btn-sm pr-2 pl-2">
                    <i class="mdi mdi-file-download-outline"></i>
                </a>
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_CREATE)): ?>
					<a href="<?= site_url('training/exam/create') ?>" class="btn btn-sm btn-primary">
						<i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
					</a>
				<?php endif; ?>
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive">
			<thead>
			<tr class="toggle-row" data-button="toggle-course">
				<th class="text-md-center" style="width: 60px">No</th>
				<th>Curriculum</th>
				<th>Employee</th>
				<th>Evaluator</th>
				<th>Created At</th>
				<th>Score</th>
				<th style="width: 90px">Status</th>
				<th style="width: 100px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$examStatuses = [
					ExamModel::STATUS_ACTIVE => 'success',
					ExamModel::STATUS_INACTIVE => 'danger',
				];
			?>
			<?php $no = isset($exams) ? ($exams['current_page'] - 1) * $exams['per_page'] : 0 ?>
			<?php foreach ($exams['data'] as $exam): ?>
				<tr>
					<td class="text-md-center"><?= ++$no ?></td>
					<td>
						<div class="d-flex align-items-center">
							<img src="<?= empty($exam['cover_image']) ? base_url('assets/dist/img/no-image.png') : asset_url($exam['cover_image']) ?>"
								 alt="Image cover" id="image-cover" class="rounded mr-3" style="height: 45px; width: 50px; object-fit: cover">
							<div>
								<h6 class="font-weight-bold mb-0"><?= $exam['curriculum_title'] ?></h6>
								<p class="text-muted mb-0"><?= if_empty($exam['department'], 'No') ?> department</p>
							</div>
						</div>
					</td>
					<td><?= $exam['employee_name'] ?></td>
					<td><?= if_empty($exam['evaluator_name'], 'No evaluator (Trainer)') ?></td>
					<td><?= format_date($exam['created_at'], 'd M Y H:i') ?></td>
					<td>
						<span class="font-weight-bold text-<?= $exam['score'] > 50 ? 'success' : 'danger' ?>">
							<?= if_empty(numerical($exam['score'], 1), 0) ?>
						</span>
						<span class="text-fade">(<?= $exam['total_assessed'] ?>/<?= $exam['total_exams'] ?>)</span>
					</td>
					<td>
						<span class="badge badge-<?= get_if_exist($examStatuses, $exam['status'], 'primary') ?>">
							<?= $exam['status'] ?>
						</span>
					</td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('training/exam/view/' . $exam['id']) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
								<?php endif; ?>
								<?php if ($exam['status'] == TrainingModel::STATUS_ACTIVE): ?>
									<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_EDIT)): ?>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item"
										   href="<?= site_url('training/exam/edit/' . $exam['id']) ?>">
											<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
										</a>
										<a class="dropdown-item btn-validate" data-action="inactive" data-label="Exam <?= $exam['curriculum_title'] ?>"
										   href="<?= site_url('training/exam/inactive/' . $exam['id']) ?>">
											<i class="mdi mdi-close mr-2"></i> Set Inactive
										</a>
									<?php endif; ?>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_DELETE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
									   data-id="<?= $exam['id'] ?>" data-label="Exam <?= $exam['curriculum_title'] ?> - <?= $exam['employee_name'] ?>" data-title="Exam"
									   data-url="<?= site_url('training/exam/delete/' . $exam['id']) ?>">
										<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
									</a>
								<?php endif; ?>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if(empty($exams['data'])): ?>
				<tr>
					<td colspan="8">No exam data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $exams]) ?>
    </div>
</div>

<?php $this->load->view('exam/_modal_filter') ?>
<?php $this->load->view('partials/modals/_validate') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
