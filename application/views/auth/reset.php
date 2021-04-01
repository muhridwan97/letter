<div class="auth-form-light text-left p-5">
    <div class="brand-logo mb-3">
        <h3>Password Recovery</h3>
    </div>
    <?php $this->load->view('partials/_alert') ?>

    <form action="<?= site_url('auth/password/reset/' . $token) ?>" method="post" class="pt-3">
        <?= _csrf() ?>
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="email" name="email"
                   value="<?= set_value('email', $email) ?>" placeholder="Registered email" required readonly>
            <?= form_error('email') ?>
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="new_password" name="new_password"
                   placeholder="New password" required minlength="6" maxlength="20">
            <?= form_error('password') ?>
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password"
                   placeholder="Confirm the password" required>
            <?= form_error('confirm_password') ?>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                RESET MY PASSWORD
            </button>
        </div>
        <div class="text-center mt-4 font-weight-light">
            Remember password? <a href="<?= site_url('auth/login') ?>" class="text-primary">Login</a>
        </div>
    </form>
</div>