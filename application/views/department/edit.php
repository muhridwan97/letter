<form action="<?= site_url('master/department/update/' . $department['id']) ?>" method="POST" id="form-department">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Edit Department</h5>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" class="form-control" id="department" name="department" required maxlength="50"
                       value="<?= set_value('department', $department['department']) ?>" placeholder="Enter a department title">
                <?= form_error('department') ?>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter department description"><?= set_value('description', $department['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update Department</button>
        </div>
    </div>
</form>
