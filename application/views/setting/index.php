<form action="<?= site_url('setting') ?>" method="POST" id="form-setting">
    <?= _csrf() ?>
    <?= _method('put') ?>
    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Setting Basic</h4>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="app_name">App Name</label>
                        <input type="text" class="form-control" id="app_name" name="app_name"
                               placeholder="Application title" required maxlength="20"
                               value="<?= set_value('app_name', get_if_exist($setting, 'app_name', 'E-purchasing')) ?>">
                        <?= form_error('app_name') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="app_url">App URL</label>
                        <input type="url" class="form-control" id="meta_url" name="meta_url"
                               placeholder="Application full url" required maxlength="200"
                               value="<?= set_value('meta_url', get_if_exist($setting, 'meta_url')) ?>">
                        <?= form_error('meta_url') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meta_keywords">Keywords</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                               placeholder="Enter application keywords" maxlength="300"
                               value="<?= set_value('meta_keywords', get_if_exist($setting, 'meta_keywords', 'purchasing,finance,goods,services')) ?>">
                        <?= form_error('meta_keywords') ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="meta_author">Author</label>
                        <input type="text" class="form-control" id="meta_author" name="meta_author"
                               placeholder="Application author" maxlength="50"
                               value="<?= set_value('meta_author', get_if_exist($setting, 'meta_author')) ?>">
                        <?= form_error('meta_author') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="meta_description">Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description"
                          placeholder="Application description" maxlength="300"><?= set_value('meta_description', get_if_exist($setting, 'meta_description')) ?></textarea>
                <?= form_error('meta_description') ?>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Emails</h4>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email_bug_report">Bug Report</label>
                        <input type="email" class="form-control" id="email_bug_report" name="email_bug_report"
                               placeholder="Enter email bug reports" maxlength="50"
                               value="<?= set_value('email_bug_report', get_if_exist($setting, 'email_bug_report', 'bug@purchasing.app')) ?>">
                        <?= form_error('email_bug_report') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="email_support">Support</label>
                        <input type="email" class="form-control" id="email_support" name="email_support"
                               placeholder="Enter email bug reports" maxlength="50"
                               value="<?= set_value('email_support', get_if_exist($setting, 'email_support', 'support@purchasing.app')) ?>">
                        <?= form_error('email_support') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">Purchasing</h4>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="purchasing_admin">Purchasing Admin</label>
                        <input type="text" class="form-control" id="purchasing_admin" name="purchasing_admin"
                               placeholder="Enter purchasing admin name" maxlength="50"
                               value="<?= set_value('purchasing_admin', get_if_exist($setting, 'purchasing_admin')) ?>">
                        <?= form_error('purchasing_admin') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="purchasing_supervisor">Purchasing SPV</label>
                        <input type="text" class="form-control" id="purchasing_supervisor" name="purchasing_supervisor"
                               placeholder="Enter purchasing SPV name" maxlength="100"
                               value="<?= set_value('purchasing_supervisor', get_if_exist($setting, 'purchasing_supervisor')) ?>">
                        <?= form_error('purchasing_supervisor') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                               placeholder="Enter company name" maxlength="50"
                               value="<?= set_value('company_name', get_if_exist($setting, 'company_name')) ?>">
                        <?= form_error('company_name') ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="company_contact">Contact Label</label>
                        <input type="text" class="form-control" id="company_contact" name="company_contact"
                               placeholder="Company contact label" maxlength="100"
                               value="<?= set_value('company_contact', get_if_exist($setting, 'company_contact')) ?>">
                        <?= form_error('company_contact') ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="company_address">Company Address</label>
                <textarea class="form-control" id="company_address" name="company_address"
                          placeholder="Application description" maxlength="300"><?= set_value('company_address', get_if_exist($setting, 'company_address')) ?></textarea>
                <?= form_error('company_address') ?>
            </div>
			<div class="form-group">
				<label for="top_manager">Top Manager</label>
				<select class="form-control select2" name="top_manager" id="top_manager" data-placeholder="Select user manager" style="width: 100%" required>
					<option value="">-- Select Manager --</option>
					<?php foreach ($quotationManageUsers as $user): ?>
						<option value="<?= $user['id'] ?>"<?= set_select('top_manager', $user['id'], get_if_exist($setting, 'top_manager') == $user['id']) ?>>
							<?= $user['name'] ?> - <?= $user['email'] ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?= form_error('top_manager') ?>
			</div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body">
            <h4 class="card-title">WA Group</h4>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="wa_purchasing">WA Purchasing</label>
                        <input type="text" class="form-control" id="wa_purchasing" name="wa_purchasing"
                               placeholder="Enter wa number" maxlength="50"
                               value="<?= set_value('wa_purchasing', get_if_exist($setting, 'wa_purchasing')) ?>">
                        <?= form_error('wa_purchasing') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card grid-margin">
        <div class="card-body d-flex justify-content-between">
            <button onclick="history.back()" type="button" class="btn btn-light">Back</button>
            <button type="submit" class="btn btn-success" data-toggle="one-touch" data-touch-message="Updating...">
                Update
            </button>
        </div>
    </div>
</form>
