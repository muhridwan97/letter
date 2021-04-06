<div class="form-plaintext">
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Exam Result</h5>
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
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Started At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($examExercise['started_at'], 'd M Y H:i'), '<span class="text-fade">No Started Yet</span>') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Finished At</label>
						<div class="col-sm-8">
							<p class="form-control-plaintext">
								<?= if_empty(format_date($examExercise['started_at'], 'd M Y H:i'), '<span class="text-fade">No Started Yet</span>') ?>
							</p>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-sm-4 col-form-label">Status</label>
						<div class="col-sm-8">
							<?php
							$examExerciseStatuses = [
								ExamExerciseModel::STATUS_PENDING => 'primary',
								ExamExerciseModel::STATUS_STARTED => 'warning',
								ExamExerciseModel::STATUS_FINISHED => 'info',
								ExamExerciseModel::STATUS_ASSESSED => 'success',
							];
							?>
							<p class="form-control-plaintext">
								<span class="badge badge-<?= get_if_exist($examExerciseStatuses, $examExercise['status'], 'light') ?>">
									<?= $examExercise['status'] ?>
								</span>
							</p>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>

	<div class="card mb-3">
		<div class="card-body">
			<div class="mb-3">
				<h5 class="card-title mb-1">
					<?= ucfirst(strtolower($examExercise['category'])) ?> Exam (<?= count($examExerciseAnswers) ?>)
				</h5>
				<p class="text-muted"><?= $examExercise['title'] ?></p>
			</div>
			<?php foreach ($examExerciseAnswers as $index => $answer): ?>
				<div class="card mb-2 border-primary">
					<div class="card-body py-2 px-3 d-flex">
						<p class="lead mb-0 mr-2"><?= $index + 1 ?>.</p>
						<div>
							<p class="lead mb-0"><?= ucfirst($answer['question']) ?></p>
							<div>
								<p class="mb-0 text-success font-weight-bold">Answer:</p>
								<p class="mb-1"><?= ucfirst(nl2br($answer['answer'])) ?></p>
							</div>
							<?php if (!empty($answer['attachment'])): ?>
								<div>
									<p class="mb-0 text-success font-weight-bold">
										Attachment:
										<span class="font-weight-normal text-body">
											<a href="<?= asset_url($answer['attachment']) ?>" class="text-link">
												<i class="mdi mdi-file-outline mr-1"></i>Download
											</a>
										</span>
									</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="card-footer pt-2">
						<h5 class="mb-0">
							Score:
							<strong class="text-<?= $answer['score'] > 50 ? 'success' : 'danger' ?>">
								<?= is_null($answer['score']) ? 'Not assessed yet' : numerical($answer['score']) ?>
							</strong>
						</h5>
						<p class="mb-0">Assessment Note: <?= if_empty($answer['assessment_note'], '-') ?></p>
					</div>
				</div>
			<?php endforeach; ?>
			<?php if(empty($examExerciseAnswers)): ?>
				<span class="text-muted">No answer result available</span>
			<?php endif; ?>
		</div>
	</div>
	<div class="card mb-3">
		<div class="card-body">
			<h5 class="card-title">Result Summary</h5>
			<h5 class="mb-1">
				Total Score:
				<strong class="text-<?= $examExercise['score'] > 50 ? 'success' : 'danger' ?>">
					<?= numerical($examExercise['score']) ?>
				</strong>
			</h5>
			<p class="mb-0">Description: <?= if_empty($examExercise['description'], 'No description') ?></p>
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
							<span class="badge badge-<?= get_if_exist($examExerciseStatuses, $statusHistory['status'], 'light') ?>">
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
        </div>
    </div>
</div>
