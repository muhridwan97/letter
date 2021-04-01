<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



// Super admin permission
defined('PERMISSION_ALL_ACCESS') OR define('PERMISSION_ALL_ACCESS', 'all-access');

// Account setting permission
defined('PERMISSION_ACCOUNT_EDIT') OR define('PERMISSION_ACCOUNT_EDIT', 'account-edit');
defined('PERMISSION_SETTING_EDIT') OR define('PERMISSION_SETTING_EDIT', 'setting-edit');

// Master role permission
defined('PERMISSION_ROLE_VIEW') OR define('PERMISSION_ROLE_VIEW', 'role-view');
defined('PERMISSION_ROLE_CREATE') OR define('PERMISSION_ROLE_CREATE', 'role-create');
defined('PERMISSION_ROLE_EDIT') OR define('PERMISSION_ROLE_EDIT', 'role-edit');
defined('PERMISSION_ROLE_DELETE') OR define('PERMISSION_ROLE_DELETE', 'role-delete');

// Master user permission
defined('PERMISSION_USER_VIEW') OR define('PERMISSION_USER_VIEW', 'user-view');
defined('PERMISSION_USER_CREATE') OR define('PERMISSION_USER_CREATE', 'user-create');
defined('PERMISSION_USER_EDIT') OR define('PERMISSION_USER_EDIT', 'user-edit');
defined('PERMISSION_USER_DELETE') OR define('PERMISSION_USER_DELETE', 'user-delete');

// Master category permission
defined('PERMISSION_CATEGORY_VIEW') OR define('PERMISSION_CATEGORY_VIEW', 'category-view');
defined('PERMISSION_CATEGORY_CREATE') OR define('PERMISSION_CATEGORY_CREATE', 'category-create');
defined('PERMISSION_CATEGORY_EDIT') OR define('PERMISSION_CATEGORY_EDIT', 'category-edit');
defined('PERMISSION_CATEGORY_DELETE') OR define('PERMISSION_CATEGORY_DELETE', 'category-delete');

// Master bill category permission
defined('PERMISSION_BILL_CATEGORY_VIEW') OR define('PERMISSION_BILL_CATEGORY_VIEW', 'bill-category-view');
defined('PERMISSION_BILL_CATEGORY_CREATE') OR define('PERMISSION_BILL_CATEGORY_CREATE', 'bill-category-create');
defined('PERMISSION_BILL_CATEGORY_EDIT') OR define('PERMISSION_BILL_CATEGORY_EDIT', 'bill-category-edit');
defined('PERMISSION_BILL_CATEGORY_DELETE') OR define('PERMISSION_BILL_CATEGORY_DELETE', 'bill-category-delete');

// Master item category permission
defined('PERMISSION_ITEM_CATEGORY_VIEW') OR define('PERMISSION_ITEM_CATEGORY_VIEW', 'item-category-view');
defined('PERMISSION_ITEM_CATEGORY_CREATE') OR define('PERMISSION_ITEM_CATEGORY_CREATE', 'item-category-create');
defined('PERMISSION_ITEM_CATEGORY_EDIT') OR define('PERMISSION_ITEM_CATEGORY_EDIT', 'item-category-edit');
defined('PERMISSION_ITEM_CATEGORY_DELETE') OR define('PERMISSION_ITEM_CATEGORY_DELETE', 'item-category-delete');

// Master item permission
defined('PERMISSION_ITEM_VIEW') OR define('PERMISSION_ITEM_VIEW', 'item-view');
defined('PERMISSION_ITEM_CREATE') OR define('PERMISSION_ITEM_CREATE', 'item-create');
defined('PERMISSION_ITEM_EDIT') OR define('PERMISSION_ITEM_EDIT', 'item-edit');
defined('PERMISSION_ITEM_DELETE') OR define('PERMISSION_ITEM_DELETE', 'item-delete');

// Master vendor permission
defined('PERMISSION_VENDOR_VIEW') OR define('PERMISSION_VENDOR_VIEW', 'vendor-view');
defined('PERMISSION_VENDOR_CREATE') OR define('PERMISSION_VENDOR_CREATE', 'vendor-create');
defined('PERMISSION_VENDOR_EDIT') OR define('PERMISSION_VENDOR_EDIT', 'vendor-edit');
defined('PERMISSION_VENDOR_DELETE') OR define('PERMISSION_VENDOR_DELETE', 'vendor-delete');

// Master department permission
defined('PERMISSION_DEPARTMENT_VIEW') OR define('PERMISSION_DEPARTMENT_VIEW', 'department-view');
defined('PERMISSION_DEPARTMENT_CREATE') OR define('PERMISSION_DEPARTMENT_CREATE', 'department-create');
defined('PERMISSION_DEPARTMENT_EDIT') OR define('PERMISSION_DEPARTMENT_EDIT', 'department-edit');
defined('PERMISSION_DEPARTMENT_DELETE') OR define('PERMISSION_DEPARTMENT_DELETE', 'department-delete');

// Master employee permission
defined('PERMISSION_EMPLOYEE_VIEW') OR define('PERMISSION_EMPLOYEE_VIEW', 'employee-view');
defined('PERMISSION_EMPLOYEE_CREATE') OR define('PERMISSION_EMPLOYEE_CREATE', 'employee-create');
defined('PERMISSION_EMPLOYEE_EDIT') OR define('PERMISSION_EMPLOYEE_EDIT', 'employee-edit');
defined('PERMISSION_EMPLOYEE_DELETE') OR define('PERMISSION_EMPLOYEE_DELETE', 'employee-delete');

// Master city permission
defined('PERMISSION_CITY_VIEW') OR define('PERMISSION_CITY_VIEW', 'city-view');
defined('PERMISSION_CITY_CREATE') OR define('PERMISSION_CITY_CREATE', 'city-create');
defined('PERMISSION_CITY_EDIT') OR define('PERMISSION_CITY_EDIT', 'city-edit');
defined('PERMISSION_CITY_DELETE') OR define('PERMISSION_CITY_DELETE', 'city-delete');

// Requisition permission
defined('PERMISSION_REQUISITION_VIEW') OR define('PERMISSION_REQUISITION_VIEW', 'requisition-view');
defined('PERMISSION_REQUISITION_CREATE') OR define('PERMISSION_REQUISITION_CREATE', 'requisition-create');
defined('PERMISSION_REQUISITION_EDIT') OR define('PERMISSION_REQUISITION_EDIT', 'requisition-edit');
defined('PERMISSION_REQUISITION_DELETE') OR define('PERMISSION_REQUISITION_DELETE', 'requisition-delete');
defined('PERMISSION_REQUISITION_VALIDATE') OR define('PERMISSION_REQUISITION_VALIDATE', 'requisition-validate');
defined('PERMISSION_REQUISITION_MANAGE') OR define('PERMISSION_REQUISITION_MANAGE', 'requisition-manage');
defined('PERMISSION_REQUISITION_REVERT') OR define('PERMISSION_REQUISITION_REVERT', 'requisition-revert');
defined('PERMISSION_REQUISITION_ADMIN_VIEW') OR define('PERMISSION_REQUISITION_ADMIN_VIEW', 'requisition-admin-view');
defined('PERMISSION_REQUISITION_SET_CHECK') OR define('PERMISSION_REQUISITION_SET_CHECK', 'requisition-set-check');

// Quotation permission
defined('PERMISSION_QUOTATION_VIEW') OR define('PERMISSION_QUOTATION_VIEW', 'quotation-view');
defined('PERMISSION_QUOTATION_CREATE') OR define('PERMISSION_QUOTATION_CREATE', 'quotation-create');
defined('PERMISSION_QUOTATION_EDIT') OR define('PERMISSION_QUOTATION_EDIT', 'quotation-edit');
defined('PERMISSION_QUOTATION_DELETE') OR define('PERMISSION_QUOTATION_DELETE', 'quotation-delete');
defined('PERMISSION_QUOTATION_SELECT') OR define('PERMISSION_QUOTATION_SELECT', 'quotation-select');
defined('PERMISSION_QUOTATION_MANAGE') OR define('PERMISSION_QUOTATION_MANAGE', 'quotation-manage');

// Order permission
defined('PERMISSION_ORDER_VIEW') OR define('PERMISSION_ORDER_VIEW', 'order-view');
defined('PERMISSION_ORDER_CREATE') OR define('PERMISSION_ORDER_CREATE', 'order-create');
defined('PERMISSION_ORDER_EDIT') OR define('PERMISSION_ORDER_EDIT', 'order-edit');
defined('PERMISSION_ORDER_DELETE') OR define('PERMISSION_ORDER_DELETE', 'order-delete');
defined('PERMISSION_ORDER_MANAGE') OR define('PERMISSION_ORDER_MANAGE', 'order-manage');

// Report permission
defined('PERMISSION_REPORT_GENERAL_VIEW') OR define('PERMISSION_REPORT_GENERAL_VIEW', 'report-general-view');

// Payment Handover
defined('PERMISSION_PAYMENT_HANDOVER_VIEW') OR define('PERMISSION_PAYMENT_HANDOVER_VIEW', 'payment-handover-view');
defined('PERMISSION_PAYMENT_HANDOVER_CREATE') OR define('PERMISSION_PAYMENT_HANDOVER_CREATE', 'payment-handover-create');
defined('PERMISSION_PAYMENT_HANDOVER_EDIT') OR define('PERMISSION_PAYMENT_HANDOVER_EDIT', 'payment-handover-edit');
defined('PERMISSION_PAYMENT_HANDOVER_DELETE') OR define('PERMISSION_PAYMENT_HANDOVER_DELETE', 'payment-handover-delete');
