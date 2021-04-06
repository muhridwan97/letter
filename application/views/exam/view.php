<div class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">View Exam</h5>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Curriculum</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exam['curriculum_title'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Employee</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= $exam['employee_name'] ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Evaluator</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exam['evaluator_name'], 'No evaluator (Trainer)') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Status</label>
						<div class="col-sm-8">
							<?php
							$examStatuses = [
								ExamModel::STATUS_ACTIVE => 'success',
								ExamModel::STATUS_INACTIVE => 'danger',
							];
							?>
							<p class="form-control-plaintext">
								<span class="badge badge-<?= get_if_exist($examStatuses, $exam['status'], 'primary') ?>">
									<?= $exam['status'] ?>
								</span>
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exam['description'], 'No description') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Assigned By</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty($exam['assigned_by'], 'Default') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Created At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= format_date($exam['created_at'], 'd F Y H:i') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Updated At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($exam['updated_at'], 'd F Y H:i'), '-') ?>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Curriculum Exams</h5>
			<table class="table table-md responsive" id="table-exam-exercise">
				<thead>
				<tr>
					<th>No</th>
					<th>Title</th>
					<th>Category</th>
					<th>Question</th>
					<th>Started At</th>
					<th>Finished At</th>
					<th>Status</th>
					<th>Score</th>
					<th class="text-md-right">Action</th>
				</tr>
				</thead>
				<tbody>
				<?php
				$examStatuses = [
					ExamExerciseModel::STATUS_PENDING => 'primary',
					ExamExerciseModel::STATUS_STARTED => 'warning',
					ExamExerciseModel::STATUS_FINISHED => 'info',
					ExamExerciseModel::STATUS_ASSESSED => 'success',
				];
				?>
				<?php foreach ($examExercises as $index => $examExercise): ?>
				<tr>
					<td><?= $index + 1 ?></td>
					<td><?= if_empty($examExercise['title'], $examExercise['exercise_title']) ?></td>
					<td><?= ucfirst(strtolower($examExercise['category'])) ?></td>
					<td><?= $examExercise['total_questions'] ?> items</td>
					<td><?= if_empty(format_date($examExercise['started_at'], 'd M Y H:i'), '<span class="text-fade">No Started Yet</span>') ?></td>
					<td><?= if_empty(format_date($examExercise['finished_at'], 'd M Y H:i'), '<span class="text-fade">Not Started Yet</span>') ?></td>
					<td>
						<span class="badge badge-<?= get_if_exist($examStatuses, $examExercise['status'], 'light') ?>">
							<?= $examExercise['status'] ?>
						</span>
					</td>
					<td>
						<h5 class="mb-0 font-weight-bold text-<?= $examExercise['score'] > 50 ? 'success' : 'danger' ?>">
							<?= numerical($examExercise['score'], 1) ?>
						</h5>
					</td>
					<td class="text-md-right">
						<?php if ($exam['status'] == ExamModel::STATUS_ACTIVE): ?>
							<?php if ($examExercise['status'] == ExamExerciseModel::STATUS_PENDING): ?>
								<?php if ($examExercise['id_employee'] == UserModel::loginData('id_employee') || ($examExercise['id_evaluator'] == UserModel::loginData('id_employee') && $examExercise['category'] != ExerciseModel::CATEGORY_CHOICES)): ?>
									<form action="<?= site_url('training/exam-exercise/start/' . $examExercise['id']) ?>" method="post">
										<?= _csrf() ?>
										<button type="button" class="btn btn-sm btn-primary btn-start-quiz" data-title="<?= $examExercise['title'] ?>" data-duration="<?= $examExercise['duration'] ?>">
											Start <i class="mdi mdi-timer-outline"></i>
										</button>
									</form>
								<?php else: ?>
									<?= if_empty($examExercise['employee_name'], 'Training') ?>
								<?php endif; ?>
							<?php elseif ($examExercise['status'] == ExamExerciseModel::STATUS_STARTED): ?>
								<?php if ($examExercise['id_employee'] == UserModel::loginData('id_employee') || ($examExercise['id_evaluator'] == UserModel::loginData('id_employee') && $examExercise['category'] != ExerciseModel::CATEGORY_CHOICES)): ?>
									<a href="<?= site_url('training/exam-exercise/quiz/' . $examExercise['id']) ?>" class="btn btn-sm btn-warning">
										Continue <i class="mdi mdi-arrow-right"></i>
									</a>
								<?php else: ?>
									<?= if_empty($examExercise['employee_name'], 'Training') ?>
								<?php endif; ?>
							<?php elseif ($examExercise['status'] == ExamExerciseModel::STATUS_FINISHED): ?>
								<?php if ($examExercise['id_evaluator'] == UserModel::loginData('id_employee') && AuthorizationModel::hasPermission(PERMISSION_EXAM_ASSESS)): ?>
									<a href="<?= site_url('training/assessment/edit/' . $examExercise['id']) ?>" class="btn btn-sm btn-danger">
										Assess <i class="mdi mdi-square-edit-outline"></i>
									</a>
								<?php else: ?>
									<?= if_empty($examExercise['evaluator_name'], 'Evaluator') ?>
								<?php endif; ?>
							<?php endif; ?>
						<?php elseif ($examExercise['status'] != ExamExerciseModel::STATUS_ASSESSED): ?>
							<span class="badge badge-danger">INCOMPLETE</span>
						<?php endif; ?>

						<?php if ($examExercise['status'] == ExamExerciseModel::STATUS_ASSESSED): ?>
							<a href="<?= site_url('training/exam-exercise/view/' . $examExercise['id']) ?>" class="btn btn-sm btn-success">
								Result <i class="mdi mdi-file-document-outline"></i>
							</a>
							<?php if ($exam['status'] == ExamModel::STATUS_ACTIVE && AuthorizationModel::hasPermission(PERMISSION_EXAM_ASSESS) && $examExercise['id_evaluator'] == UserModel::loginData('id_employee')): ?>
								<a href="<?= site_url('training/assessment/edit/' . $examExercise['id']) ?>"
								   class="btn btn-sm btn-primary<?= $examExercise['category'] == ExerciseModel::CATEGORY_CHOICES ? ' disabled' : '' ?>"
								   data-toggle="tooltip" data-title="Edit assessment">
									<i class="mdi mdi-square-edit-outline"></i>
								</a>
							<?php endif; ?>
						<?php endif; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php if(empty($examExercises)): ?>
					<tr>
						<td colspan="9">No curriculum exam available</td>
					</tr>
				<?php endif; ?>
				<tr class="font-weight-bold bg-light">
					<td></td>
					<td colspan="6">Average Score</td>
					<td colspan="2" class="text-<?= $exam['score'] > 50 ? 'success' : 'danger' ?>">
						<h5 class="font-weight-bold"><?= numerical($exam['score'], 1) ?></h5>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title mb-3">Status Histories</h5>
			<table class="table table-sm responsive">
				<thead>
				<tr>
					<th>No</th>
					<th>Status</th>
					<th style="max-width: 300px">Description</th>
					<th>Data</th>
					<th>Created At</th>
					<th>Created By</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($statusHistories as $index => $statusHistory): ?>
					<tr>
						<td><?= ($index + 1) ?></td>
						<td>
							<span class="badge badge-<?= get_if_exist($examStatuses, $statusHistory['status'], 'light') ?>">
								<?= $statusHistory['status'] ?>
							</span>
						</td>
						<td style="max-width: 300px"><?= if_empty($statusHistory['description'], 'No description') ?></td>
						<td>
							<?php if(empty($statusHistory['data'])): ?>
								-
							<?php else: ?>
								<a href="<?= site_url('history/view/' . $statusHistory['id']) ?>">
									View History
								</a>
							<?php endif; ?>
						</td>
						<td><?= if_empty(format_date($statusHistory['created_at'], 'd F Y H:i'), '-') ?></td>
						<td>
							<a href="<?= site_url('master/user/view/' . $statusHistory['created_by']) ?>">
								<?= if_empty($statusHistory['creator_name'], 'No user') ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
				<?php if(empty($statusHistories)): ?>
					<tr class="row-no-header">
						<td colspan="6">No status history available</td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>

    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
			<?php if ($exam['status'] == TrainingModel::STATUS_ACTIVE): ?>
				<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_EDIT)): ?>
					<a href="<?= site_url('training/exam/inactive/' . $exam['id']) ?>" class="btn btn-danger btn-validate" data-action="inactive" data-label="Exam <?= $exam['curriculum_title'] ?>">
						Set Inactive
					</a>
				<?php endif; ?>
			<?php endif; ?>
        </div>
    </div>
</div>

<?php $this->load->view('partials/modals/_confirm') ?>
<?php $this->load->view('partials/modals/_validate') ?>
