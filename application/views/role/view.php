<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View Role</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="role">Role Name</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="role">
                                <?= if_empty($role['role'], 'No role') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="total_permission">Permission</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="total_permission">
                                <?= if_empty($role['total_permission'], 0) ?> permissions
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="description">Description</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="description">
                                <?= if_empty($role['description'], 'No description') ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="created_at">
                                <?= format_date($role['created_at'], 'd F Y H:i') ?>
                            </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                        <div class="col-sm-9">
                            <p class="form-control-plaintext" id="updated_at">
                                <?= if_empty(format_date($role['updated_at'], 'd F Y H:i'),  '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Permissions</h4>
            <span class="text-muted">Role at least must has one permission</span>

            <div class="form-group">
                <div class="row">
                    <?php $lastGroup = '' ?>
                    <?php $lastSubGroup = '' ?>
                    <?php foreach ($permissions as $permission): ?>
                        <?php
                        $module = $permission['module'];
                        $submodule = $permission['submodule'];
                        ?>

                        <?php if($lastGroup != $module): ?>
                            <?php
                            $lastGroup = $module;
                            $lastGroupName = preg_replace('/ /', '_', $lastGroup);
                            ?>
                            <div class="col-12 mt-3">
                                <hr>
                                <h4 class="mt-2">
                                    Module <?= ucwords($lastGroup) ?>
                                </h4>
                                <hr class="mb-0">
                            </div>
                        <?php endif; ?>

                        <?php if($lastSubGroup != $submodule): ?>
                            <?php $lastSubGroup = $submodule; ?>
                            <div class="col-12">
                                <div class="mb-2 mt-3">
                                    <h6>
                                        <?= ucwords(preg_replace('/\-/', ' ', $lastSubGroup)) ?>
                                    </h6>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-sm-4">
                            <p class="mb-0 text-muted">
                                <?= ucwords(preg_replace('/(_|\-)/', ' ', $permission['permission'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?= form_error('permissions[]'); ?>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_ROLE_EDIT)): ?>
                <a href="<?= site_url('master/role/edit/' . $role['id']) ?>" class="btn btn-primary">
                    Edit Role
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>