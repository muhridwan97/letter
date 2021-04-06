<form action="<?= site_url('training/exam/save?redirect=' . get_url_param('redirect')) ?>" method="POST" id="form-exam">
    <?= _csrf() ?>
	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Create New Exam</h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="curriculum">Curriculum</label>
						<select class="form-control select2" id="curriculum" name="curriculum" data-placeholder="Select curriculum" required>
							<option value="">No curriculum</option>
							<?php foreach ($curriculums as $curriculum): ?>
								<option value="<?= $curriculum['id'] ?>"<?= set_select('curriculum', $curriculum['id']) ?>>
									<?= $curriculum['curriculum_title'] ?> - <?= $curriculum['department'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('curriculum') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="employee">Employee</label>
						<select class="form-control select2" id="employee" name="employee" data-placeholder="Select employee" required>
							<option value="">No employee</option>
							<?php foreach ($employees as $employee): ?>
								<option value="<?= $employee['id'] ?>"<?= set_select('employee', $employee['id']) ?>>
									<?= $employee['name'] ?>
								</option>
							<?php endforeach; ?>
						</select>
						<?= form_error('employee') ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="evaluator">Evaluator</label>
				<select class="form-control select2" id="evaluator" name="evaluator" data-placeholder="Select evaluator" required>
					<option value=""></option>
					<?php foreach ($employees as $employee): ?>
						<option value="<?= $employee['id'] ?>"<?= set_select('evaluator', $employee['id'], $employee['id'] == UserModel::loginData('id_employee')) ?>>
							<?= $employee['name'] ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?= form_error('evaluator') ?>
			</div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter exam description"><?= set_value('description') ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

	<div class="alert alert-warning">
		<h6 class="font-weight-bold mb-1">Exam</h6>
		<p class="mb-0">Create exam <strong>will deactivate</strong> related curriculum training of the employee.</p>
	</div>

	<div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Saving...">
				Save Exam
			</button>
        </div>
    </div>
</form>
