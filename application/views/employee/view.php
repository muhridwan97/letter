<div class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Employee</h4>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="name">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="name">
                                <?= if_empty($employee['name'], 'No name') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="no_employee">Employee ID</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="no_employee">
                                <?= if_empty($employee['no_employee'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="supervisor">Supervisor</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="supervisor">
                                <?= if_empty($employee['supervisor_name'], 'No supervisor') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="photo">Photo</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="photo">
                                <a href="<?= base_url(if_empty($employee['photo'], 'assets/dist/img/no-image.png', 'uploads/')) ?>">
                                    Download
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="position">Position</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="position">
                                <?= if_empty($employee['position'], 'position') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="position_level">Position Level</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="position_level">
                                <?= if_empty($employee['position_level'], 'No position level') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="enter_date">Enter Date</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="enter_date">
                                <?= if_empty(format_date($employee['enter_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="quit_date">Quit Enter</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="quit_date">
                                <?= if_empty(format_date($employee['quit_date'], 'd F Y'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="department">Department</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="department">
                                <?= if_empty($employee['department'], 'No department') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="gender">Gender</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="gender">
                                <?= ucfirst($employee['gender']) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="company">Company</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="company">
                                <?= if_empty($employee['company'], 'No company') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="work_location">Work Location</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="work_location">
                                <?= if_empty($employee['work_location'], 'No work location') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="tax_no">Tax No</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="tax_no">
                                <?= if_empty($employee['tax_no'], 'No tax') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="tax_address">Tax Address</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="tax_address">
                                <?= if_empty($employee['tax_address'], 'No address') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="id_card_no">ID Card No</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="id_card_no">
                                <?= if_empty($employee['id_card_no'], 'No ID card') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="id_card_address">ID Card Address</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="id_card_address">
                                <?= if_empty($employee['id_card_address'], 'No address') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="place_of_birth">Place of Birth</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="place_of_birth">
                                <?= if_empty($employee['place_of_birth'], 'No place of birth') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="date_of_birth">Date of Birth</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="date_of_birth">
                                <?= if_empty(format_date($employee['date_of_birth'], 'd F Y'), 'No birthday') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="contact_phone">Contact Phone</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="contact_phone">
                                <?= if_empty($employee['contact_phone'], 'No contact phone') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="contact_mobile">Contact Mobile</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="contact_mobile">
                                <?= if_empty($employee['contact_mobile'], 'No contact mobile') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="related_account">Related Account</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="related_account">
                                <?php if(empty($employee['username'])): ?>
                                    No account
                                <?php else: ?>
                                    <a href="<?= site_url('master/user/view/' . $employee['id_user']) ?>">
                                        <?= $employee['username'] ?>
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="related_email">Related Email</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="related_email">
                                <?= if_empty($employee['email'], 'No email') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($employee['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($employee['updated_at'], 'd F Y H:i'), '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="description">Description</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($employee['description'], '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Status</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="status">
                                <?php
                                $statuses = [
                                    EmployeeModel::STATUS_ACTIVE => 'success',
                                    EmployeeModel::STATUS_INACTIVE => 'danger',
                                ]
                                ?>
                                <label class="mb-0 small badge badge-<?= $statuses[$employee['status']] ?>">
                                    <?= $employee['status'] ?>
                                </label>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Subordinates</h4>
            <table class="table responsive">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>No Employee</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Level</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; ?>
                <?php foreach ($subordinates as $subordinate): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <a href="<?= site_url('master/employee/view/'. $subordinate['id']) ?>">
                                <?= $subordinate['name'] ?>
                            </a>
                        </td>
                        <td><?= $subordinate['no_employee'] ?></td>
                        <td><?= if_empty($subordinate['department'], '-') ?></td>
                        <td><?= if_empty($subordinate['position'], '-') ?></td>
                        <td><?= if_empty($subordinate['position_level'], '-') ?></td>
                        <td><?= if_empty($subordinate['email'], '-') ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if(empty($subordinates)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No subordinate available</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(!$this->config->item('sso_enable')): ?>
                <?php if(AuthorizationModel::isAuthorized(PERMISSION_EMPLOYEE_EDIT)): ?>
                    <a href="<?= site_url('master/employee/edit/' . $employee['id']) ?>" class="btn btn-primary">
                        Edit Employee
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>