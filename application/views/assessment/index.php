<div class="card mb-3">
    <div class="card-body">
        <div class="d-sm-flex justify-content-between">
            <h5 class="card-title mb-sm-0">Data Exam Assessment</h5>
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
            </div>
        </div>
		<table class="table table-hover table-sm mt-3 responsive">
			<thead>
			<tr class="toggle-row" data-button="toggle-course">
				<th class="text-md-center" style="width: 60px">No</th>
				<th>Curriculum</th>
				<th>Employee</th>
				<th>Evaluator</th>
				<th>Status</th>
				<th>Score</th>
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
			<?php
				$examStatuses = [
					ExamExerciseModel::STATUS_PENDING => 'primary',
					ExamExerciseModel::STATUS_STARTED => 'warning',
					ExamExerciseModel::STATUS_FINISHED => 'info',
					ExamExerciseModel::STATUS_ASSESSED => 'success',
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
					<td>
						<span class="badge badge-<?= get_if_exist($examStatuses, $exam['status'], 'primary') ?>">
							<?= $exam['status'] ?>
						</span><br>
						<span class="badge badge-<?= $exam['total_assessed'] < $exam['total_exams'] && $exam['status'] == ExamModel::STATUS_ACTIVE  ? 'danger' : 'info' ?>">
							<?= $exam['total_assessed'] ?> / <?= $exam['total_exams'] ?>
						</span>
					</td>
					<td class="font-weight-bold text-<?= $exam['score'] > 50 ? 'success' : 'danger' ?>">
						<?= if_empty(numerical($exam['score'], 1), 0) ?>
					</td>
					<td class="text-md-right">
						<a class="btn btn-info btn-sm" href="<?= site_url('training/exam/view/' . $exam['id']) ?>">
							View <i class="mdi mdi-arrow-right"></i>
						</a>
					</td>
				</tr>
				<?php foreach ($exam['exam_exercises'] as $examIndex => $examExercise): ?>
					<?php if ($examIndex == 0): ?>
						<tr class="ml-4 ml-md-0 text-primary bg-primary-fade row-header row-header-<?= $exam['id'] ?>">
							<th class="d-none d-md-table-cell"><span class="d-inline-block d-md-none">No</span></th>
							<th>Exam Title</th>
							<th>Category</th>
							<th>Question</th>
							<th>Status</th>
							<th>Score</th>
							<th class="text-md-right">Action</th>
						</tr>
					<?php endif; ?>
					<tr class="ml-4 ml-md-0 text-muted" data-header="row-header-<?= $exam['id'] ?>">
						<td><span class="d-md-none"><?= $examIndex + 1 ?></span></td>
						<td><?= $examExercise['title'] ?></td>
						<td><?= $examExercise['category'] ?></td>
						<td><?= numerical($examExercise['total_questions']) ?> items</td>
						<td>
							<span class="badge badge-<?= get_if_exist($examStatuses, $examExercise['status'], 'light') ?>">
								<?= $examExercise['status'] ?>
							</span>
						</td>
						<td class="text-<?= $examExercise['score'] > 50 ? 'success' : 'danger' ?>">
							<?= numerical($examExercise['score'], 1) ?>
						</td>
						<td class="text-nowrap text-md-right">
							<?php if ($exam['status'] == ExamModel::STATUS_ACTIVE): ?>
								<?php if (AuthorizationModel::hasPermission(PERMISSION_EXAM_ASSESS)): ?>
									<?php if ($exam['id_evaluator'] == UserModel::loginData('id_employee') || AuthorizationModel::hasPermission(PERMISSION_EXAM_MANAGE)): ?>
										<?php if ($examExercise['status'] == ExamExerciseModel::STATUS_FINISHED): ?>
											<a href="<?= site_url('training/assessment/edit/' . $examExercise['id']) ?>" class="btn btn-sm btn-danger<?= $examExercise['category'] == ExerciseModel::CATEGORY_CHOICES ? ' disabled' : '' ?>">
												Assess <i class="mdi mdi-square-edit-outline"></i>
											</a>
										<?php elseif ($examExercise['status'] == ExamExerciseModel::STATUS_ASSESSED): ?>
											<a href="<?= site_url('training/assessment/edit/' . $examExercise['id']) ?>"
											   class="btn btn-sm btn-primary<?= $examExercise['category'] == ExerciseModel::CATEGORY_CHOICES ? ' disabled' : '' ?>"
											   data-toggle="tooltip" data-title="Edit assessment">
												<i class="mdi mdi-square-edit-outline"></i>
											</a>
										<?php else: ?>
											<button class="btn btn-sm btn-outline-secondary disabled">Waiting</button>
										<?php endif; ?>
									<?php endif; ?>
								<?php endif; ?>
							<?php elseif ($examExercise['status'] != ExamExerciseModel::STATUS_ASSESSED): ?>
								<span class="badge badge-danger">INCOMPLETE</span>
							<?php else: ?>
								<a href="<?= site_url('training/exam-exercise/view/' . $examExercise['id']) ?>" class="btn btn-sm btn-success">
									Result <i class="mdi mdi-file-document-outline"></i>
								</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
			<?php if(empty($exams['data'])): ?>
				<tr>
					<td colspan="7">No exam data available</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>
        <?php $this->load->view('partials/_pagination', ['pagination' => $exams]) ?>
    </div>
</div>

<?php $this->load->view('assessment/_modal_filter') ?>
