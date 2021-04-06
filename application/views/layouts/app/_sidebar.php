<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
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
			PERMISSION_DEPARTMENT_VIEW, PERMISSION_EMPLOYEE_VIEW,
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
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'department' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/department') ?>">
									<i class="mdi mdi-office-building-outline mr-2"></i>Department
									<?php if($this->config->item('sso_enable')): ?>
										<span class="badge badge-info badge-pill ml-auto">
											HR
										</span>
									<?php endif; ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_EMPLOYEE_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'master' && $segment2 == 'employee' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('master/employee') ?>">
									<i class="mdi mdi-badge-account-horizontal-outline mr-2"></i>Employee
									<?php if($this->config->item('sso_enable')): ?>
										<span class="badge badge-info badge-pill ml-auto">
											HR
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
			PERMISSION_CURRICULUM_VIEW, PERMISSION_COURSE_VIEW, PERMISSION_LESSON_VIEW, PERMISSION_EXERCISE_VIEW
		])):
		?>
			<li class="nav-item<?= $segment1 == 'syllabus' ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#syllabus" aria-expanded="<?= $segment1 == 'syllabus' ? 'true' : 'false' ?>" aria-controls="syllabus">
					<i class="mdi mdi-inbox-full-outline menu-icon"></i>
					<span class="menu-title">Syllabus</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse<?= $segment1 == 'syllabus' ? ' show' : '' ?>" id="syllabus">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_CURRICULUM_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'syllabus' && $segment2 == 'curriculum' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('syllabus/curriculum') ?>">
									<i class="mdi mdi-folder-settings-outline mr-2"></i>Curriculum
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_COURSE_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'syllabus' && $segment2 == 'course' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('syllabus/course') ?>">
									<i class="mdi mdi-form-select mr-2"></i>Course
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_LESSON_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'syllabus' && $segment2 == 'lesson' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('syllabus/lesson') ?>">
									<i class="mdi mdi-book-check-outline mr-2"></i>Lesson
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXERCISE_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'syllabus' && $segment2 == 'exercise' && get_url_param('type') == 'lesson-exercise' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('syllabus/exercise?type=lesson-exercise') ?>">
									<i class="mdi mdi-file-document-edit-outline mr-2"></i>Exercise
								</a>
							</li>
							<li class="nav-item<?= $segment1 == 'syllabus' && $segment2 == 'exercise' && get_url_param('type') == 'curriculum-exam' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('syllabus/exercise?type=curriculum-exam') ?>">
									<i class="mdi mdi-text-box-search-outline mr-2"></i>Exam
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>
		<?php endif; ?>

		<?php
		if (AuthorizationModel::hasPermission([
			PERMISSION_TRAINING_VIEW, PERMISSION_EXAM_VIEW, PERMISSION_EXAM_ASSESS
		])):
		?>
			<li class="nav-item<?= $segment1 == 'training' && !in_array($segment2, ['role', 'user']) ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#training" aria-expanded="<?= $segment1 == 'training' ? 'true' : 'false' ?>" aria-controls="training">
					<i class="mdi mdi-lightbulb-on-outline menu-icon"></i>
					<span class="menu-title">Training</span>
					<i class="menu-arrow"></i>
				</a>
				<div class="collapse<?= $segment1 == 'training' ? ' show' : '' ?>" id="training">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_TRAINING_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'training' && $segment2 == 'class' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('training/class') ?>">
									<i class="mdi mdi-school-outline mr-2"></i>Training Class
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_VIEW)): ?>
							<li class="nav-item<?= $segment1 == 'training' && ($segment2 == 'exam' || $segment2 == 'exam-exercise') ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('training/exam') ?>">
									<i class="mdi mdi-ballot-outline mr-2"></i>Training Exams
								</a>
							</li>
						<?php endif; ?>
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_EXAM_ASSESS)): ?>
							<li class="nav-item<?= $segment1 == 'training' && $segment2 == 'assessment' ? ' active' : '' ?>">
								<a class="nav-link" href="<?= site_url('training/assessment') ?>">
									<i class="mdi mdi-file-document-outline mr-2"></i>Assessment
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>
		<?php endif; ?>

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
