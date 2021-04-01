<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="<?= site_url('/') ?>">
            <?= $this->config->item('app_name') ?>
        </a>
        <a class="navbar-brand brand-logo-mini" href="<?= site_url('/') ?>">
            <span>PC</span>
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <div class="search-field d-none d-md-block">
            <form class="d-flex align-items-center h-100" action="<?= site_url('search') ?>" method="get">
                <div class="input-group">
                    <div class="input-group-prepend bg-transparent">
                        <i class="input-group-text border-0 mdi mdi-magnify"></i>
                    </div>
                    <input type="text" name="q" class="form-control bg-transparent border-0"
                           value="<?= get_url_param('q') ?>" placeholder="Search data">
                </div>
            </form>
        </div>
        <ul class="navbar-nav navbar-nav-right">
            <?php if(UserModel::isLoggedIn()): ?>
                <li class="nav-item dropdown">
                    <?php $stickyNotifications = NotificationModel::getUnreadNotification() ?>
                    <a class="nav-link count-indicator dropdown-toggle mx-2" id="notificationDropdown" href="#"
                       data-toggle="dropdown">
                        <i class="mdi mdi-bell-outline"></i>
                        <?php if(!empty($stickyNotifications)): ?>
                            <span class="count-symbol bg-danger"></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                         aria-labelledby="notificationDropdown">
                        <h6 class="p-3 mb-0">Notifications</h6>
                        <div class="dropdown-divider"></div>
                        <?php
                        $notificationIcons = [
                            NotificationModel::SUBSCRIBE_REQUISITION => 'mdi-file-document-box-multiple',
                            NotificationModel::SUBSCRIBE_OFFER => 'mdi-folder-plus-outline',
                            NotificationModel::SUBSCRIBE_ORDER => 'mdi-cart-outline',
                        ];
                        $notificationColors = [
                            NotificationModel::SUBSCRIBE_REQUISITION => 'bg-info',
                            NotificationModel::SUBSCRIBE_OFFER => 'bg-primary',
                            NotificationModel::SUBSCRIBE_ORDER => 'bg-success',
                        ]
                        ?>
                        <?php foreach ($stickyNotifications as $notify): ?>
                            <a href="<?= site_url('notification/read/' . $notify['id'] .'?redirect='.$notify['data']['url']) ?>" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon <?= get_if_exist($notificationColors, $notify['channel'], 'bg-primary') ?>">
                                        <i class="mdi <?= get_if_exist($notificationIcons, $notify['channel'], 'mdi-file-outline') ?>"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                    <h6 class="preview-subject font-weight-normal mb-1"><?= ucwords($notify['channel']) ?></h6>
                                    <p class="text-gray mb-2" style="white-space: normal; line-height: 1.2; min-width: 220px">
                                        <?= ucfirst($notify['data']['message']) ?>
                                    </p>
                                    <p class="mb-0 small text-muted"><?= relative_time($notify['created_at']) ?></p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endforeach; ?>
                        <?php if(empty($stickyNotifications)): ?>
                            <a class="dropdown-item small text-muted" style="min-width: 220px">
                                Notifications was read.
                            </a>
                            <div class="dropdown-divider"></div>
                        <?php endif; ?>
                        <h6 class="p-3 mb-0 text-center">
                            <a href="<?= site_url('notification') ?>">
                                See all notifications
                            </a>
                        </h6>
                    </div>
                </li>
            <?php endif; ?>
            <li class="nav-item nav-profile dropdown d-none d-sm-inline-block">
                <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown"
                   aria-expanded="false">
                    <div class="nav-profile-img">
                        <?php if($this->config->item('sso_enable')): ?>
                            <img src="<?= sso_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/no-avatar.png', 'uploads/')) ?>" alt="avatar">
                        <?php else: ?>
                            <img src="<?= base_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/layouts/no-avatar.png', 'uploads/')) ?>" alt="profile">
                        <?php endif; ?>
                    </div>
                    <div class="nav-profile-text">
                        <p class="mb-1 text-light"><?= UserModel::loginData('name') ?></p>
                    </div>
                </a>
                <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                    <?php if($this->config->item('sso_enable')): ?>
                        <?php if(UserModel::isLoggedIn()): ?>
                            <a class="dropdown-item" href="<?= sso_url('account') ?>">
                                <i class="mdi mdi-account mr-2 text-success"></i>
                                My Account
                            </a>
                            <a class="dropdown-item" href="<?= sso_url('app') ?>">
                                <i class="mdi mdi-view-dashboard mr-2 text-danger"></i>
                                Switch App
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= sso_url('auth/logout') ?>">
                                <i class="mdi mdi-logout mr-2 text-primary"></i>
                                Sign Out
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?= sso_url('auth/login?redirect=' . get_current_url()) ?>">
                                <i class="mdi mdi-login mr-2 text-primary"></i>
                                Sign In
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if(UserModel::isLoggedIn()): ?>
                            <a class="dropdown-item" href="<?= site_url('account') ?>">
                                <i class="mdi mdi-account mr-2 text-success"></i>
                                My Account
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= site_url('auth/logout') ?>">
                                <i class="mdi mdi-logout mr-2 text-primary"></i>
                                Sign Out
                            </a>
                        <?php else: ?>
                            <a class="dropdown-item" href="<?= site_url('auth/login?redirect=' . get_current_url()) ?>">
                                <i class="mdi mdi-login mr-2 text-primary"></i>
                                Sign In
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>
