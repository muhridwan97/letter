<div class="auth-form-light text-left p-5">
    <div class="brand-logo mb-3">
        <h3>Reset Password</h3>
    </div>
    <?php $this->load->view('partials/_alert') ?>

    <h6 class="font-weight-light">
        Enter your email address that you used to register. We'll send you an email with your username and a
        link to reset your password.
    </h6>

    <form action="<?= site_url('auth/password/forgot-password') ?>" method="post" class="pt-3">
        <?= _csrf() ?>
        <div class="form-group">
            <input type="email" class="form-control form-control-lg" id="email" name="email"
                   value="<?= set_value('email') ?>" placeholder="Registered email">
            <?= form_error('email') ?>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                RESET PASSWORD
            </button>
        </div>
        <div class="text-center mt-4 font-weight-light">
            Remember password? <a href="<?= site_url('auth/login') ?>" class="text-primary">Login</a>
        </div>
    </form>
</div>