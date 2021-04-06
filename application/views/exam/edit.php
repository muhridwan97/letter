<form action="<?= site_url('training/exam/update/' . $exam['id'] . '?redirect=' . get_url_param('redirect')) ?>" method="POST" id="form-exam">
    <?= _csrf() ?>
    <?= _method('put') ?>

	<div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Edit Exam</h5>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="curriculum">Curriculum</label>
						<input type="text" class="form-control" readonly value="<?= $exam['curriculum_title'] ?>" id="curriculum" placeholder="Curriculum">
						<input type="hidden" name="curriculum" value="<?= $exam['id_curriculum'] ?>">
						<?= form_error('curriculum') ?>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label for="employee">Employee</label>
						<input type="text" class="form-control" readonly value="<?= $exam['employee_name'] ?>" id="employee" placeholder="employee">
						<input type="hidden" name="employee" value="<?= $exam['id_employee'] ?>">
						<?= form_error('employee') ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label for="evaluator">Evaluator</label>
				<select class="form-control select2" id="evaluator" name="evaluator" data-placeholder="Select evaluator" required>
					<option value=""></option>
					<?php foreach ($employees as $employee): ?>
						<option value="<?= $employee['id'] ?>"<?= set_select('employee', $employee['id'], $employee['id'] == $exam['id_evaluator']) ?>>
							<?= $employee['name'] ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?= form_error('evaluator') ?>
			</div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter exam description"><?= set_value('description', $exam['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>

	<div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Saving...">
				Update Exam
			</button>
        </div>
    </div>
</form>
