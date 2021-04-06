
<nav class="navbar navbar-expand">
    <button class="btn btn-lg py-0 px-1 btn-link" id="menu-toggle">
        <span class="mdi mdi-menu lead"></span>
    </button>
    <img src="<?= base_url('assets/dist/img/icon.png') ?>" alt="Logo"
         class="d-sm-none ml-2 rounded" height="35px" style="opacity: 0.95">

    <ul class="navbar-nav align-items-center ml-auto mt-2 mt-lg-0">
        <li class="nav-item dropdown mr-1">
            <a class="nav-link" href="<?= site_url('search') ?>">
                <i class="mdi mdi-magnify lead"></i>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="<?= base_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/no-avatar.png', 'uploads/')) ?>"
                     alt="Profile" class="img-fluid rounded-circle mr-2" style="width: 30px; height: 30px">
                <p class="d-none d-sm-inline-block mb-0">
                    <?= UserModel::loginData('name') ?>
                </p>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="<?= site_url('dashboard') ?>">
                    <i class="mdi mdi-speedometer-slow mr-1"></i> Dashboard
                </a>
				<?php if($this->config->item('sso_enable')): ?>
					<a class="dropdown-item" href="<?= sso_url('app') ?>">
						<i class="mdi mdi-backburger mr-1"></i>
						Switch App
					</a>
					<a class="dropdown-item" href="<?= sso_url('account') ?>">
						<i class="mdi mdi-account-outline mr-1"></i> My Account
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?= sso_url('auth/logout') ?>">
						<i class="mdi mdi-logout mr-1"></i> Logout
					</a>
				<?php else: ?>
					<a class="dropdown-item" href="<?= site_url('account') ?>">
						<i class="mdi mdi-account-outline mr-1"></i> My Account
					</a>
					<a class="dropdown-item" href="<?= site_url('auth/logout') ?>"
					   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
						<i class="mdi mdi-logout-variant mr-1"></i> Logout
					</a>
					<form id="logout-form" action="<?= site_url('auth/logout') ?>" method="POST" style="display: none;">
						<?= _csrf() ?>
					</form>
				<?php endif; ?>
            </div>
        </li>
    </ul>
</nav>
