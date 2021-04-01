<?php
$segment1 = $this->uri->segment(1);
$segment2 = $this->uri->segment(2);
?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="<?= $this->config->item('sso_enable') ? sso_url('account') : site_url('account') ?>" class="nav-link">
                <div class="nav-profile-image">
                    <?php if($this->config->item('sso_enable')): ?>
                        <img src="<?= sso_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/no-avatar.png', 'uploads/')) ?>" alt="avatar">
                    <?php else: ?>
                        <img src="<?= base_url(if_empty(UserModel::loginData('avatar'), 'assets/dist/img/layouts/no-avatar.png', 'uploads/')) ?>" alt="profile">
                    <?php endif; ?>
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column text-truncate">
                    <span class="font-weight-bold mb-2"><?= UserModel::loginData('name') ?></span>
                    <span class="text-secondary text-small"><?= UserModel::loginData('user_type') ?></span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>

        <li class="nav-item<?= empty($segment1) || $segment1 == 'dashboard' ? ' active' : '' ?>">
            <a class="nav-link" href="<?= site_url('dashboard') ?>">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        <?php
            if(AuthorizationModel::hasPermission([
                PERMISSION_ROLE_VIEW, PERMISSION_USER_VIEW, PERMISSION_CATEGORY_VIEW,
                PERMISSION_VENDOR_VIEW, PERMISSION_DEPARTMENT_VIEW, PERMISSION_EMPLOYEE_VIEW,
                PERMISSION_CITY_VIEW
            ])):
        ?>
            <li class="nav-item<?= $segment1 == 'master' ? ' active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#master" aria-expanded="<?= $segment1 == 'master' ? 'true' : 'false' ?>" aria-controls="master">
                    <span class="menu-title">Master</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-cube-outline menu-icon"></i>
                </a>
                <div class="collapse<?= $segment1 == 'master' ? ' show' : '' ?>" id="master">
                    <ul class="nav flex-column sub-menu">
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ROLE_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'role' ? ' active' : '' ?>"
                                   href="<?= site_url('master/role') ?>">Role
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_USER_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'user' ? ' active' : '' ?>"
                                   href="<?= site_url('master/user') ?>">User
                                    <?php if($this->config->item('sso_enable')): ?>
                                        <span class="badge badge-success badge-pill ml-auto">SSO</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_CATEGORY_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'category' ? ' active' : '' ?>"
                                   href="<?= site_url('master/category') ?>">Category
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_CATEGORY_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'item-category' ? ' active' : '' ?>"
                                   href="<?= site_url('master/item-category') ?>">Item Category
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ITEM_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'item' ? ' active' : '' ?>"
                                   href="<?= site_url('master/item') ?>">Item
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_CITY_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'city' ? ' active' : '' ?>"
                                   href="<?= site_url('master/city') ?>">City
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_VENDOR_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'vendor' ? ' active' : '' ?>"
                                   href="<?= site_url('master/vendor') ?>">All Vendor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'vendor-active' ? ' active' : '' ?>"
                                   href="<?= site_url('master/vendor-active') ?>">Active Vendor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'vendor-inactive' ? ' active' : '' ?>"
                                   href="<?= site_url('master/vendor-inactive') ?>">Inactive Vendor
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_DEPARTMENT_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'department' ? ' active' : '' ?>"
                                   href="<?= site_url('master/department') ?>">Department
                                    <?php if($this->config->item('sso_enable')): ?>
                                        <span class="badge badge-primary badge-pill ml-auto">HR</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_EMPLOYEE_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'master' && $segment2 == 'employee' ? ' active' : '' ?>"
                                   href="<?= site_url('master/employee') ?>">Employee
                                    <?php if($this->config->item('sso_enable')): ?>
                                        <span class="badge badge-primary badge-pill ml-auto">HR</span>
                                    <?php endif; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <?php if(AuthorizationModel::hasPermission([PERMISSION_REQUISITION_VIEW])): ?>
			<li class="nav-item<?= $segment1 == 'requisition' ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#requisition" aria-expanded="false" aria-controls="requisition">
					<span class="menu-title">Requisition</span>
					<i class="menu-arrow"></i>
					<i class="mdi mdi-file-replace-outline menu-icon"></i>
				</a>
				<div class="collapse<?= $segment1 == 'requisition' ? ' show' : '' ?>" id="requisition">
					<ul class="nav flex-column sub-menu">
						<?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_VIEW)): ?>
							<li class="nav-item">
								<a class="nav-link<?= $segment1 == 'requisition' && $segment2 != 'outstanding' && $segment2 != 'cancelled' ? ' active' : '' ?>"
								   href="<?= site_url('requisition') ?>">
									Requisition
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link<?= $segment1 == 'requisition' && $segment2 == 'outstanding' ? ' active' : '' ?>"
								   href="<?= site_url('requisition/outstanding') ?>">
									Outstanding
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link<?= $segment1 == 'requisition' && $segment2 == 'cancelled' ? ' active' : '' ?>"
								   href="<?= site_url('requisition/cancelled') ?>">
									Cancelled
								</a>
							</li>
						<?php endif; ?>
					</ul>
				</div>
			</li>
        <?php endif; ?>

        <?php if(AuthorizationModel::hasPermission([PERMISSION_QUOTATION_VIEW, PERMISSION_QUOTATION_SELECT, PERMISSION_ORDER_VIEW, PERMISSION_REQUISITION_CREATE])): ?>
            <li class="nav-item<?= $segment1 == 'purchasing' ? ' active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#purchasing" aria-expanded="false" aria-controls="purchasing">
                    <span class="menu-title">Purchasing</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-cart-outline menu-icon"></i>
                </a>
                <div class="collapse<?= $segment1 == 'purchasing' ? ' show' : '' ?>" id="purchasing">
                    <ul class="nav flex-column sub-menu">
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_QUOTATION_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'purchasing' && $segment2 == 'offer' ? ' active' : '' ?>"
                                   href="<?= site_url('purchasing/offer') ?>">Vendor Quotation</a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized([PERMISSION_QUOTATION_SELECT])): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'purchasing' && $segment2 == 'selection' ? ' active' : '' ?>"
                                   href="<?= site_url('purchasing/selection') ?>">Vendor Selection</a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ORDER_VIEW)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'purchasing' && $segment2 == 'order' ? ' active' : '' ?>"
                                   href="<?= site_url('purchasing/order') ?>">Purchase Order</a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_CREATE)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'purchasing' && $segment2 == 'payment-handover' ? ' active' : '' ?>"
                                   href="<?= site_url('purchasing/payment-handover') ?>">Payment Handover</a>
                            </li>
                        <?php endif; ?>
                        <?php if(AuthorizationModel::isAuthorized(PERMISSION_REQUISITION_CREATE)): ?>
                            <li class="nav-item">
                                <a class="nav-link<?= $segment1 == 'purchasing' && $segment2 == 'handover' ? ' active' : '' ?>"
                                   href="<?= site_url('purchasing/handover') ?>">Handover</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <?php if(AuthorizationModel::isAuthorized(PERMISSION_REPORT_GENERAL_VIEW)): ?>
            <li class="nav-item<?= $segment1 == 'report' ? ' active' : '' ?>">
                <a class="nav-link" data-toggle="collapse" href="#report" aria-expanded="false" aria-controls="report">
                    <span class="menu-title">Report</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-file-document-outline menu-icon"></i>
                </a>
                <div class="collapse<?= $segment1 == 'report' ? ' show' : '' ?>" id="report">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && ($segment2 == 'summary' || $segment2 == 'summary-detail') ? ' active' : '' ?>"
                               href="<?= site_url('report/summary') ?>">Purchase Summary</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'satisfied-recipient' ? ' active' : '' ?>"
                               href="<?= site_url('report/satisfied-recipient') ?>">Satisfied Recipient</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && ($segment2 == 'reorder-item' || $segment2 == 'reorder-item-detail') ? ' active' : '' ?>"
                               href="<?= site_url('report/reorder-item') ?>">Reorder Item</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'category-period' ? ' active' : '' ?>"
                               href="<?= site_url('report/category-period') ?>">Category Period</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'item-prices' ? ' active' : '' ?>"
                               href="<?= site_url('report/item-prices') ?>">Item Prices</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'control' ? ' active' : '' ?>"
                               href="<?= site_url('report/control') ?>">Control Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'statistic' ? ' active' : '' ?>"
                               href="<?= site_url('report/statistic') ?>">Statistic</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?= $segment1 == 'report' && $segment2 == 'po_invoice' ? ' active' : '' ?>"
                               href="<?= site_url('report/po_invoice') ?>">PO - Invoice</a>
                        </li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>

        <?php if(AuthorizationModel::isAuthorized(PERMISSION_SETTING_EDIT)): ?>
			<li class="nav-item<?= $segment1 == 'utility' ? ' active' : '' ?>">
				<a class="nav-link" data-toggle="collapse" href="#utility" aria-expanded="false" aria-controls="utility">
					<span class="menu-title">Utility</span>
					<i class="menu-arrow"></i>
					<i class="mdi mdi-toolbox-outline menu-icon"></i>
				</a>
				<div class="collapse<?= $segment1 == 'utility' ? ' show' : '' ?>" id="utility">
					<ul class="nav flex-column sub-menu">
						<li class="nav-item">
							<a class="nav-link<?= $segment1 == 'utility' && $segment2 == 'backup' ? ' active' : '' ?>" href="<?= site_url('utility/backup') ?>">Backup</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?= $segment1 == 'utility' && $segment2 == 'system-log' ? ' active' : '' ?>" href="<?= site_url('utility/system-log') ?>">System Log</a>
						</li>
						<li class="nav-item">
							<a class="nav-link<?= $segment1 == 'utility' && $segment2 == 'access-log' ? ' active' : '' ?>" href="<?= site_url('utility/access-log') ?>">Access Log</a>
						</li>
					</ul>
				</div>
			</li>
			<li class="nav-item<?= $segment1 == 'setting' ? ' active' : '' ?>">
                <a class="nav-link" href="<?= site_url('setting') ?>">
                    <span class="menu-title">Setting</span>
                    <i class="mdi mdi-settings-outline menu-icon"></i>
                </a>
            </li>
        <?php endif; ?>

        <?php if(AuthorizationModel::isAuthorized(PERMISSION_ACCOUNT_EDIT)): ?>
            <?php if($this->config->item('sso_enable')): ?>
                <li class="nav-item<?= $segment1 == 'account' ? ' active' : '' ?>">
                    <a class="nav-link" href="<?= sso_url('account') ?>">
                        <span class="menu-title">Account</span>
                        <i class="mdi mdi-account-outline menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item<?= $segment1 == 'help' ? ' active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('help') ?>">
                        <span class="menu-title">Help & Glossary</span>
                        <i class="mdi mdi-help-circle-outline menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= sso_url('auth/logout') ?>">
                        <span class="menu-title">Sign Out</span>
                        <i class="mdi mdi-logout menu-icon"></i>
                    </a>
                </li>
            <?php else: ?>
                <li class="nav-item<?= $segment1 == 'account' ? ' active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('account') ?>">
                        <span class="menu-title">Account</span>
                        <i class="mdi mdi-account-outline menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item<?= $segment1 == 'help' ? ' active' : '' ?>">
                    <a class="nav-link" href="<?= site_url('help') ?>">
                        <span class="menu-title">Help & Glossary</span>
                        <i class="mdi mdi-help-circle-outline menu-icon"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('auth/logout') ?>">
                        <span class="menu-title">Sign Out</span>
                        <i class="mdi mdi-logout menu-icon"></i>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

    </ul>
</nav>
