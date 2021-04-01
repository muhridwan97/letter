<form action="<?= site_url('master/city/update/' . $city['id']) ?>" method="POST" id="form-city">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Edit City</h4>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" required maxlength="50"
                       value="<?= set_value('city', $city['city']) ?>" placeholder="Enter a city title">
                <?= form_error('city') ?>
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <select class="form-control select2" name="province" id="province" required>
                    <?php foreach ($provinces as $province): ?>
                        <option value="<?= $province?>"<?= set_select('province', $province, $province==$city['province']) ?>>
                            <?= $province?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= form_error('province') ?>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" maxlength="500"
                          placeholder="Enter city description"><?= set_value('description', $city['description']) ?></textarea>
                <?= form_error('description') ?>
            </div>
        </div>
    </div>
    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-primary" data-toggle="one-touch" data-touch-message="Updating...">Update City</button>
        </div>
    </div>
</form>