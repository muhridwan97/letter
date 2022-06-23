<nav class="navbar navbar-expand navbar-dark bg-primary border-bottom">
	<a class="show-res-logo" href="<?= base_url() ?>">
	<img src="<?= base_url('assets/dist/img/logo-uin.png') ?>" alt="logo" class="img-responsive" style="max-height: 40px; width: auto;" ></a>    
	
	<ul class="navbar-nav align-items-center ml-auto mt-2 mt-lg-0">
	<?php if(!UserModel::isLoggedIn()): ?>
		<a href="<?= site_url('dashboard') ?>" class="btn btn-lg p-0 btn-link text-white">
        	<span class="">Login</span>
    	</a>
	<?php else: ?>
		<li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle text-white" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
	<?php endif; ?>
	</ul>
</nav>

