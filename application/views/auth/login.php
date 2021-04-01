<div class="auth-form-light text-left p-5">
    <div class="brand-logo mb-3">
        <h3>PURCHASING</h3>
    </div>
    <?php $this->load->view('partials/_alert') ?>

    <h6 class="font-weight-light">Sign in to continue.</h6>

    <form action="<?= site_url('auth/login') . if_empty($_SERVER['QUERY_STRING'], '', '?') ?>" method="post" class="pt-3">
        <?= _csrf() ?>
        <div class="form-group">
            <input type="text" class="form-control form-control-lg" id="username" name="username"
                   value="<?= set_value('username') ?>" placeholder="Username or email">
            <?= form_error('username') ?>
        </div>
        <div class="form-group">
            <input type="password" class="form-control form-control-lg" id="password" name="password"
                   placeholder="Password">
            <?= form_error('password') ?>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                SIGN IN
            </button>
        </div>
        <div class="my-2 d-flex justify-content-between align-items-center">
            <div class="form-check">
                <label class="form-check-label text-muted">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    Keep me signed in
                </label>
            </div>
            <a href="<?= site_url('auth/password/forgot-password') ?>" class="auth-link text-black">
                Forgot password?
            </a>
        </div>
        <div class="text-center mt-4 font-weight-light">
            Don't have an account? <a href="<?= site_url('auth/register') ?>" class="text-primary">Create</a>
        </div>
    </form>
</div>