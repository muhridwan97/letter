<form class="form-plaintext">
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">View City</h4>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="city">City</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="city">
                        <?= $city['city'] ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="province">Province</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="province">
                        <?= if_empty($city['province'], 0) ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="description">Description</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="description">
                        <?= if_empty($city['description'], 'No description') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="created_at">Created At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="created_at">
                        <?= format_date($city['created_at'], 'd F Y H:i') ?>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="updated_at">Updated At</label>
                <div class="col-sm-9">
                    <p class="form-control-plaintext" id="updated_at">
                        <?= if_empty(format_date($city['updated_at'], 'd F Y H:i'),  '-') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_EDIT)): ?>
                <a href="<?= site_url('master/city/edit/' . $city['id']) ?>" class="btn btn-primary">
                    Edit City
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>