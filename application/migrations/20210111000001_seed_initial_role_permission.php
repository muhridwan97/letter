<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_Seed_initial_role_permission
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
                'module' => 'user-access', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_VIEW,
                'description' => 'View role data'
            ],
            [
                'module' => 'user-access', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_CREATE,
                'description' => 'Create new role'
            ],
            [
                'module' => 'user-access', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_EDIT,
                'description' => 'Edit role'
            ],
            [
                'module' => 'user-access', 'submodule' => 'role', 'permission' => PERMISSION_ROLE_DELETE,
                'description' => 'Delete role'
            ],


            [
                'module' => 'user-access', 'submodule' => 'user', 'permission' => PERMISSION_USER_VIEW,
                'description' => 'View user data'
            ],
            [
                'module' => 'user-access', 'submodule' => 'user', 'permission' => PERMISSION_USER_CREATE,
                'description' => 'Create new user'
            ],
            [
                'module' => 'user-access', 'submodule' => 'user', 'permission' => PERMISSION_USER_EDIT,
                'description' => 'Edit user'
            ],
            [
                'module' => 'user-access', 'submodule' => 'user', 'permission' => PERMISSION_USER_DELETE,
                'description' => 'Delete user'
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
                'module' => 'syllabus', 'submodule' => 'curriculum', 'permission' => PERMISSION_CURRICULUM_VIEW,
                'description' => 'View curriculum data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'curriculum', 'permission' => PERMISSION_CURRICULUM_CREATE,
                'description' => 'Create new curriculum'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'curriculum', 'permission' => PERMISSION_CURRICULUM_EDIT,
                'description' => 'Edit location'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'curriculum', 'permission' => PERMISSION_CURRICULUM_DELETE,
                'description' => 'Delete curriculum'
            ],

            [
                'module' => 'syllabus', 'submodule' => 'course', 'permission' => PERMISSION_COURSE_VIEW,
                'description' => 'View course data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'course', 'permission' => PERMISSION_COURSE_CREATE,
                'description' => 'Create new course'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'course', 'permission' => PERMISSION_COURSE_EDIT,
                'description' => 'Edit course data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'course', 'permission' => PERMISSION_COURSE_DELETE,
                'description' => 'Delete course data'
            ],


            [
                'module' => 'syllabus', 'submodule' => 'lesson', 'permission' => PERMISSION_LESSON_VIEW,
                'description' => 'View lesson data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'lesson', 'permission' => PERMISSION_LESSON_CREATE,
                'description' => 'Create new lesson'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'lesson', 'permission' => PERMISSION_LESSON_EDIT,
                'description' => 'Edit lesson data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'lesson', 'permission' => PERMISSION_LESSON_DELETE,
                'description' => 'Delete lesson data'
            ],


            [
                'module' => 'syllabus', 'submodule' => 'exercise', 'permission' => PERMISSION_EXERCISE_VIEW,
                'description' => 'View exercise data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'exercise', 'permission' => PERMISSION_EXERCISE_CREATE,
                'description' => 'Create new exercise'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'exercise', 'permission' => PERMISSION_EXERCISE_EDIT,
                'description' => 'Edit exercise data'
            ],
            [
                'module' => 'syllabus', 'submodule' => 'exercise', 'permission' => PERMISSION_EXERCISE_DELETE,
                'description' => 'Delete exercise data'
            ],


            [
                'module' => 'training', 'submodule' => 'training', 'permission' => PERMISSION_TRAINING_VIEW,
                'description' => 'View training data'
            ],
            [
                'module' => 'training', 'submodule' => 'training', 'permission' => PERMISSION_TRAINING_CREATE,
                'description' => 'Create new training'
            ],
            [
                'module' => 'training', 'submodule' => 'training', 'permission' => PERMISSION_TRAINING_EDIT,
                'description' => 'Edit training data'
            ],
            [
                'module' => 'training', 'submodule' => 'training', 'permission' => PERMISSION_TRAINING_DELETE,
                'description' => 'Delete training data'
            ],
            [
                'module' => 'training', 'submodule' => 'training', 'permission' => PERMISSION_TRAINING_MANAGE,
                'description' => 'Manage training data'
            ],


            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_VIEW,
                'description' => 'View exam data'
            ],
            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_CREATE,
                'description' => 'Create new exam'
            ],
            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_EDIT,
                'description' => 'Edit exam data'
            ],
            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_DELETE,
                'description' => 'Delete exam data'
            ],
            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_ASSESS,
                'description' => 'Assess exam data'
            ],
            [
                'module' => 'training', 'submodule' => 'exam', 'permission' => PERMISSION_EXAM_MANAGE,
                'description' => 'Manage exam data'
            ],
        ]);

        echo '--Seeding Migration_Seed_initial_role_permission' . PHP_EOL;
    }

    public function down()
    {
        $this->db->delete('prv_permissions', ['module' => 'setting']);
        $this->db->delete('prv_permissions', ['module' => 'user-access']);
        $this->db->delete('prv_permissions', ['module' => 'master']);
        $this->db->delete('prv_permissions', ['module' => 'syllabus']);
        $this->db->delete('prv_permissions', ['module' => 'training']);

        echo 'Rollback Migration_Seed_initial_role_permission' . PHP_EOL;
    }
}
