<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_role_base
 * @property CI_DB_query_builder $db
 */
class Migration_Seed_initial_role_permission extends CI_Migration
{
    public function up()
    {
        $this->db->insert_batch('prv_permissions', [
            [
                'module' => 'setting', 'submodule' => 'account', 'permission' => PERMISSION_ACCOUNT_EDIT,
                'description' => 'Setting account profile'
            ],
            [
                'module' => 'setting', 'submodule' => 'application', 'permission' => PERMISSION_SETTING_EDIT,
                'description' => 'Setting application preference'
            ],

            [
                'module' => 'master', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_VIEW,
                'description' => 'View role data'
            ],
            [
                'module' => 'master', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_CREATE,
                'description' => 'Create new role'
            ],
            [
                'module' => 'master', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_EDIT,
                'description' => 'Edit role'
            ],
            [
                'module' => 'master', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_DELETE,
                'description' => 'Delete role'
            ],


            [
                'module' => 'master', 'submodule' => 'user', 'permission' => PERMISSION_USER_VIEW,
                'description' => 'View user data'
            ],
            [
                'module' => 'master', 'submodule' => 'user', 'permission' => PERMISSION_USER_CREATE,
                'description' => 'Create new user'
            ],
            [
                'module' => 'master', 'submodule' => 'user', 'permission' => PERMISSION_USER_EDIT,
                'description' => 'Edit user'
            ],
            [
                'module' => 'master', 'submodule' => 'user', 'permission' => PERMISSION_USER_DELETE,
                'description' => 'Delete user'
            ],

            [
                'module' => 'master', 'submodule' => 'category', 'permission' => PERMISSION_CATEGORY_VIEW,
                'description' => 'View category data'
            ],
            [
                'module' => 'master', 'submodule' => 'category', 'permission' => PERMISSION_CATEGORY_CREATE,
                'description' => 'Create new category'
            ],
            [
                'module' => 'master', 'submodule' => 'category', 'permission' => PERMISSION_CATEGORY_EDIT,
                'description' => 'Edit category'
            ],
            [
                'module' => 'master', 'submodule' => 'category', 'permission' => PERMISSION_CATEGORY_DELETE,
                'description' => 'Delete category'
            ],

            [
                'module' => 'master', 'submodule' => 'vendor', 'permission' => PERMISSION_VENDOR_VIEW,
                'description' => 'View vendor data'
            ],
            [
                'module' => 'master', 'submodule' => 'vendor', 'permission' => PERMISSION_VENDOR_CREATE,
                'description' => 'Create new vendor'
            ],
            [
                'module' => 'master', 'submodule' => 'vendor', 'permission' => PERMISSION_VENDOR_EDIT,
                'description' => 'Edit vendor'
            ],
            [
                'module' => 'master', 'submodule' => 'vendor', 'permission' => PERMISSION_VENDOR_DELETE,
                'description' => 'Delete vendor'
            ],


            [
                'module' => 'master', 'submodule' => 'department', 'permission' => PERMISSION_DEPARTMENT_VIEW,
                'description' => 'View department data'
            ],
            [
                'module' => 'master', 'submodule' => 'department', 'permission' => PERMISSION_DEPARTMENT_CREATE,
                'description' => 'Create new department'
            ],
            [
                'module' => 'master', 'submodule' => 'department', 'permission' => PERMISSION_DEPARTMENT_EDIT,
                'description' => 'Edit department'
            ],
            [
                'module' => 'master', 'submodule' => 'department', 'permission' => PERMISSION_DEPARTMENT_DELETE,
                'description' => 'Delete department'
            ],

            [
                'module' => 'master', 'submodule' => 'employee', 'permission' => PERMISSION_EMPLOYEE_VIEW,
                'description' => 'View employee data'
            ],
            [
                'module' => 'master', 'submodule' => 'employee', 'permission' => PERMISSION_EMPLOYEE_CREATE,
                'description' => 'Create new employee'
            ],
            [
                'module' => 'master', 'submodule' => 'employee', 'permission' => PERMISSION_EMPLOYEE_EDIT,
                'description' => 'Edit employee'
            ],
            [
                'module' => 'master', 'submodule' => 'employee', 'permission' => PERMISSION_EMPLOYEE_DELETE,
                'description' => 'Delete employee'
            ],

            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_VIEW,
                'description' => 'View requisition'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_CREATE,
                'description' => 'Create new requisition'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_EDIT,
                'description' => 'Edit requisition data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_DELETE,
                'description' => 'Delete requisition data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_VALIDATE,
                'description' => 'Validate requisition data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_MANAGE,
                'description' => 'Manage all requisition data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'requisition', 'permission' => PERMISSION_REQUISITION_REVERT,
                'description' => 'Revert requisition data'
            ],


            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_VIEW,
                'description' => 'View quotation'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_CREATE,
                'description' => 'Create new quotation'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_EDIT,
                'description' => 'Edit vendor quotation data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_DELETE,
                'description' => 'Delete vendor quotation data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_SELECT,
                'description' => 'Select vendor quotation data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'offer', 'permission' => PERMISSION_QUOTATION_MANAGE,
                'description' => 'Manage vendor quotation data'
            ],


            [
                'module' => 'purchasing', 'submodule' => 'order', 'permission' => PERMISSION_ORDER_VIEW,
                'description' => 'View order'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'order', 'permission' => PERMISSION_ORDER_CREATE,
                'description' => 'Create new order'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'order', 'permission' => PERMISSION_ORDER_EDIT,
                'description' => 'Edit order data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'order', 'permission' => PERMISSION_ORDER_DELETE,
                'description' => 'Delete order data'
            ],
            [
                'module' => 'purchasing', 'submodule' => 'order', 'permission' => PERMISSION_ORDER_MANAGE,
                'description' => 'Manage order data'
            ],

            [
                'module' => 'report', 'submodule' => 'report', 'permission' => PERMISSION_REPORT_GENERAL_VIEW,
                'description' => 'View report general'
            ],
        ]);

        echo '--Seeding Migration_Seed_initial_role_permission' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'setting']);
        $this->db->delete('prv_permissions', ['module' => 'master']);
        $this->db->delete('prv_permissions', ['module' => 'purchasing']);
        $this->db->delete('prv_permissions', ['module' => 'report']);
        echo 'Rollback Migration_Seed_initial_role_permission' . PHP_EOL;
    }
}