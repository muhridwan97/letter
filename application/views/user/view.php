<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View User</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="name">Name</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="name">
                                <?= if_empty($user['name'], 'No name') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="username">Username</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="username">
                                <?= if_empty($user['username'], 'No username') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="email">Email</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="email">
                                <a href="mailto:<?= $user['email'] ?>">
                                    <?= if_empty($user['email'], 'No email') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Status</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="status">
                                <?= if_empty($user['status'], 'No status') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="avatar">Avatar</label>
                        <div class="col-sm-8">
                            <img class="img-fluid rounded my-2" style="max-width: 100px" src="<?= sso_storage(if_empty($user['avatar'], 'assets/dist/img/no-avatar.png')) ?>" alt="profile image">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="role">Role</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="role">
                                <?php foreach ($roles as $role): ?>
                                    <a href="<?= site_url('master/role/view/' . $role['id']) ?>" class="d-block">
                                        <?= $role['role'] ?>
                                    </a>
                                <?php endforeach; ?>
                                <?php if(empty($roles)): ?>
                                    No roles
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="status">Related Employee</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="status">
                                <?php if(empty($user['id_employee'])): ?>
                                    No employee linked
                                <?php else: ?>
                                    <a href="<?= site_url('master/employee/view/' . $user['id_employee']) ?>">
                                        <?= $user['no_employee'] ?>
                                        (<?= $user['employee_name'] ?>)
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($user['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($user['updated_at'], 'd F Y H:i'),  '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_USER_EDIT)): ?>
                <a href="<?= site_url('master/user/edit/' . $user['id']) ?>" class="btn btn-primary">
                    Edit User
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>