<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Exercise</h5>
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
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_CREATE)): ?>
					<a href="<?= site_url('syllabus/exercise/create') ?>" class="btn btn-sm btn-primary d-none">
						<i class="mdi mdi-plus-box-outline mr-2"></i>CREATE
					</a>
				<?php endif; ?>
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive">
			<thead>
			<tr>
				<th class="text-md-center" style="width: 60px">No</th>
				<th>Exercise</th>
				<th>Type</th>
				<th>Category</th>
				<th>Question</th>
				<th>Duration</th>
				<th>Sequence</th>
				<th style="width: 100px" class="text-md-right">Action</th>
			</tr>
			</thead>
			<tbody>
			<?php $no = isset($exercises) ? ($exercises['current_page'] - 1) * $exercises['per_page'] : 0 ?>
			<?php foreach ($exercises['data'] as $exercise): ?>
				<tr>
					<td class="text-md-center"><?= ++$no ?></td>
					<td>
						<p class="mb-0"><?= $exercise['exercise_title'] ?></p>
						<p class="text-primary mb-0"><?= $exercise['reference_title'] ?></p>
					</td>
					<td><?= if_empty(ucwords(str_replace(['-', '_'], ' ', $exercise['type'])), 'Collection') ?></td>
					<td><?= $exercise['category'] ?></td>
					<td><?= $exercise['total_questions'] ?> items</td>
					<td><?= if_empty($exercise['duration'], '-') ?></td>
					<td><?= if_empty($exercise['question_sequence'], '-') ?></td>
					<td class="text-md-right">
						<div class="dropdown">
							<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionButton" data-toggle="dropdown">
								Action
							</button>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButton">
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_VIEW)): ?>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/exercise/view/' . $exercise['id'] . '?type=' . get_url_param('type')) ?>">
										<i class="mdi mdi-eye-outline mr-2"></i> View
									</a>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/exercise/print-question/' . $exercise['id']) ?>">
										<i class="mdi mdi-cloud-print-outline mr-2"></i> Print Question
									</a>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/exercise/print-answer/' . $exercise['id']) ?>">
										<i class="mdi mdi-cloud-print-outline mr-2"></i> Print Answer
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_EDIT)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item"
									   href="<?= site_url('syllabus/exercise/edit/' . $exercise['id'] . '?type=' . get_url_param('type')) ?>">
										<i class="mdi mdi-square-edit-outline mr-2"></i> Edit
									</a>
								<?php endif; ?>
								<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_DELETE)): ?>
									<div class="dropdown-divider"></div>
									<a class="dropdown-item btn-delete" href="#modal-delete" data-toggle="modal"
									   data-id="<?= $exercise['id'] ?>" data-label="<?= $exercise['exercise_title'] ?>" data-title="Exercise"
									   data-url="<?= site_url('syllabus/exercise/delete/' . $exercise['id']) ?>">
										<i class="mdi mdi-trash-can-outline mr-2"></i> Delete
									</a>
								<?php endif; ?>
							</div>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			<?php if(empty($exercises['data'])): ?>
				<tr>
					<td colspan="6">No exercise data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $exercises]) ?>
    </div>
</div>

<?php $this->load->view('exercise/_modal_filter') ?>

<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_DELETE)): ?>
    <?php $this->load->view('partials/modals/_delete') ?>
<?php endif; ?>
