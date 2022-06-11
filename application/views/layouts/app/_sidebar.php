<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
$segment3 = $this->uri->segment(3);
?>
<div class="bg-white d-flex flex-column" id="sidebar-wrapper">
    <div class="sidebar-heading bg-primary">
        <a href="<?= site_url('/') ?>" class="text-white btn-link">
            <?= get_setting('app_name', env('APP_NAME')) ?>
        </a>
    </div>
    <ul class="nav d-flex flex-column flex-fill pb-4">
        <li class="nav-item mt-1">
            <a href="<?= $this->config->item('sso_enable') ? sso_url('account') : site_url('account') ?>" class="nav-link" style="width: 240px">
                <div class="nav-profile-image" style="width: 50px">
                    <?php if($this->config->item('sso_enable')): ?>
                        <img src="<?= sso_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/no-avatar.png', 'uploads/')) ?>"
                             alt="avatar" class="flex-shrink-0 rounded-circle" style="height: 50px; width: 50px">
                    <?php else: ?>
                        <img src="<?= base_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/no-avatar.png', 'uploads/')) ?>"
                             alt="profile" class="flex-shrink-0 rounded-circle" style="height: 50px; width: 50px">
                    <?php endif; ?>
                </div>
                <div class="d-flex flex-column text-truncate ml-2">
                    <p class="font-weight-bold mb-0"><?= UserModel::loginData('name') ?></p>
                    <small class="text-fade text-truncate"><?= UserModel::loginData('email') ?></small>
                </div>
            </a>
        </li>

        <li class="nav-title">MAIN MENU <i class="mdi mdi-arrow-right ml-auto"></i></li>
        <li class="nav-item<?= $segment1 == 'dashboard' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                <i class="mdi mdi-speedometer-slow menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

		<?php
		if(AuthorizationModel::hasPermission([
			PERMISSION_ROLE_VIEW, PERMISSION_USER_VIEW,
		])):
		?>
			<li class="nav-item<?= $segment1 == 'master' && in_array($segment2, ['role', 'user']) ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#user-access" aria-expanded="<?= $segment1 == 'master' && in_array($segment2, ['role', 'user']) ? 'true' : 'false' ?>" aria-controls="master">
					<i class="mdi mdi-lock-outline menu-icon"></i>
					<span class="menu-title">User Access</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse<?= $segment1 == 'master' && in_array($segment2, ['role', 'user']) ? ' show' : '' ?>" id="user-access">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_ROLE_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'role' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/role') ?>">
									<i class="mdi mdi-shield-account-outline mr-2"></i>Role
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_USER_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'user' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/user') ?>">
									<i class="mdi mdi-account-multiple-outline mr-2"></i>User
									<?php if ($this->config->item('sso_enable')): ?>
										<span class="badge badge-info badge-pill ml-auto">
                                            SSO
                                        </span>
									<?php endif; ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>
		<?php endif; ?>

		<?php
		if (AuthorizationModel::hasPermission([
			PERMISSION_DEPARTMENT_VIEW, PERMISSION_LECTURER_VIEW,
		])):
		?>
			<li class="nav-item<?= $segment1 == 'master' && !in_array($segment2, ['role', 'user']) ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#master" aria-expanded="<?= $segment1 == 'master' && !in_array($segment2, ['role', 'user']) ? 'true' : 'false' ?>" aria-controls="master">
					<i class="mdi mdi-cube-outline menu-icon"></i>
					<span class="menu-title">Master</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse<?= $segment1 == 'master' && !in_array($segment2, ['role', 'user']) ? ' show' : '' ?>" id="master">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_LECTURER_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'lecturer' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/lecturer') ?>">
									<i class="mdi mdi-badge-account-horizontal-outline mr-2"></i>Lecturer
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_STUDENT_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'student' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/student') ?>">
									<i class="mdi mdi-office-building-outline mr-2"></i>Student
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>
		<?php endif; ?>

		<li class="nav-item<?= $segment1 == '' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= base_url('') ?>">
                <i class="mdi mdi-mail menu-icon"></i>
                <span class="menu-title">Letter</span>
            </a>
        </li>

			<li class="nav-item<?= $segment1 == 'skripsi' && !in_array($segment2, ['role', 'user']) ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#skripsi" aria-expanded="<?= $segment1 == 'skripsi' && !in_array($segment2, ['role', 'user']) ? 'true' : 'false' ?>" aria-controls="master">
					<i class="mdi mdi-folder-multiple menu-icon"></i>
					<span class="menu-title">Skripsi</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse<?= $segment1 == 'skripsi' && !in_array($segment2, ['role', 'user']) ? ' show' : '' ?>" id="skripsi">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_SKRIPSI_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'skripsi' && ($segment2 == 'skripsi'||$segment2 == '' || $segment2 != 'logbook') ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('skripsi/skripsi') ?>">
									<i class="mdi mdi-folder-outline mr-2"></i>Skripsi
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_LOGBOOK_VIEW)): ?>
							<?php if(AuthorizationModel::hasRole('lecturer') || AuthorizationModel::isAuthorized(PERMISSION_ALL_ACCESS)): ?>
							<li class="nav-item<?= $segment1 == 'skripsi' && $segment2 == 'logbook' && $segment3 == 'outstanding' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('skripsi/logbook/outstanding') ?>">
									<i class="mdi mdi-content-paste mr-2"></i>Outstanding
									<?php if(LogbookModel::getCountUnvalidate()>1): ?>
										<span class="badge badge-info badge-pill ml-auto">
											<?= LogbookModel::getCountUnvalidate() ?>
										</span>
									<?php endif; ?>
								</a>
							</li>
							<?php endif; ?>
							<li class="nav-item<?= $segment1 == 'skripsi' && $segment2 == 'logbook' && $segment3 != 'outstanding'? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('skripsi/logbook') ?>">
									<i class="mdi mdi-content-paste mr-2"></i>Logbook
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>

		<li class="nav-item<?= $segment1 == 'repository' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= base_url('repository') ?>">
                <i class="mdi mdi-folder-download menu-icon"></i>
                <span class="menu-title">Repository</span>
            </a>
        </li>

		<?php if(AuthorizationModel::isAuthorized(PERMISSION_ACCOUNT_EDIT)): ?>
			<li class="nav-title">ACCOUNT & SETTING <i class="mdi mdi-arrow-right ml-auto"></i></li>
			<li class="nav-item<?= $segment1 == 'account' ? ' active' : '' ?>">
				<a class="nav-link" href="<?= $this->config->item('sso_enable') ? sso_url('account') : site_url('account') ?>">
					<i class="mdi mdi-account-outline menu-icon"></i>
					<span class="menu-title">Account</span>
				</a>
			</li>
		<?php endif; ?>

        <?php if(AuthorizationModel::isAuthorized(PERMISSION_SETTING_EDIT)): ?>
            <li class="nav-item<?= $segment1 == 'utility' ? ' active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#utility" aria-expanded="false" aria-controls="utility">
                    <i class="mdi mdi-toolbox-outline menu-icon"></i>
                    <span class="menu-title">Utility</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse<?= $segment1 == 'utility' ? ' show' : '' ?>" id="utility">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item<?= $segment1 == 'utility' && $segment2 == 'backup' ? ' active' : '' ?>">
                            <a class="nav-link" href="<?= site_url('utility/backup') ?>">Backup</a>
                        </li>
                        <li class="nav-item<?= $segment1 == 'utility' && $segment2 == 'system-log' ? ' active' : '' ?>">
                            <a class="nav-link" href="<?= site_url('utility/system-log') ?>">System Log</a>
                        </li>
                        <li class="nav-item<?= $segment1 == 'utility' && $segment2 == 'access-log' ? ' active' : '' ?>">
                            <a class="nav-link" href="<?= site_url('utility/access-log') ?>">Access Log</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item<?= $segment1 == 'setting' ? ' active' : '' ?>">
                <a class="nav-link" href="<?= site_url('setting') ?>">
                    <i class="mdi mdi-cog-outline menu-icon"></i>
                    <span class="menu-title">Setting</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="nav-item">
            <a class="nav-link" href="<?= site_url('help') ?>">
                <i class="mdi mdi-help-circle-outline menu-icon"></i>
                <span class="menu-title">Help & Support</span>
            </a>
        </li>

		<li class="nav-item">
			<a class="nav-link" href="<?= $this->config->item('sso_enable') ? sso_url('logout') : site_url('logout') ?>">
				<i class="mdi mdi-logout-variant menu-icon"></i>
				<span class="menu-title">Logout</span>
			</a>
		</li>
    </ul>
</div>
