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

// Master lecturer permission
defined('PERMISSION_LECTURER_VIEW') OR define('PERMISSION_LECTURER_VIEW', 'lecturer-view');
defined('PERMISSION_LECTURER_CREATE') OR define('PERMISSION_LECTURER_CREATE', 'lecturer-create');
defined('PERMISSION_LECTURER_EDIT') OR define('PERMISSION_LECTURER_EDIT', 'lecturer-edit');
defined('PERMISSION_LECTURER_DELETE') OR define('PERMISSION_LECTURER_DELETE', 'lecturer-delete');

// Master student permission
defined('PERMISSION_STUDENT_VIEW') OR define('PERMISSION_STUDENT_VIEW', 'student-view');
defined('PERMISSION_STUDENT_CREATE') OR define('PERMISSION_STUDENT_CREATE', 'student-create');
defined('PERMISSION_STUDENT_EDIT') OR define('PERMISSION_STUDENT_EDIT', 'student-edit');
defined('PERMISSION_STUDENT_DELETE') OR define('PERMISSION_STUDENT_DELETE', 'student-delete');

// Skripsi  permission
defined('PERMISSION_SKRIPSI_VIEW') OR define('PERMISSION_SKRIPSI_VIEW', 'skripsi-view');
defined('PERMISSION_SKRIPSI_CREATE') OR define('PERMISSION_SKRIPSI_CREATE', 'skripsi-create');
defined('PERMISSION_SKRIPSI_EDIT') OR define('PERMISSION_SKRIPSI_EDIT', 'skripsi-edit');
defined('PERMISSION_SKRIPSI_DELETE') OR define('PERMISSION_SKRIPSI_DELETE', 'skripsi-delete');

// Logbook  permission
defined('PERMISSION_LOGBOOK_VIEW') OR define('PERMISSION_LOGBOOK_VIEW', 'logbook-view');
defined('PERMISSION_LOGBOOK_CREATE') OR define('PERMISSION_LOGBOOK_CREATE', 'logbook-create');
defined('PERMISSION_LOGBOOK_EDIT') OR define('PERMISSION_LOGBOOK_EDIT', 'logbook-edit');
defined('PERMISSION_LOGBOOK_DELETE') OR define('PERMISSION_LOGBOOK_DELETE', 'logbook-delete');
defined('PERMISSION_LOGBOOK_VALIDATE') OR define('PERMISSION_LOGBOOK_VALIDATE', 'logbook-validate');

// Curriculum permission
defined('PERMISSION_CURRICULUM_VIEW') OR define('PERMISSION_CURRICULUM_VIEW', 'curriculum-view');
defined('PERMISSION_CURRICULUM_CREATE') OR define('PERMISSION_CURRICULUM_CREATE', 'curriculum-create');
defined('PERMISSION_CURRICULUM_EDIT') OR define('PERMISSION_CURRICULUM_EDIT', 'curriculum-edit');
defined('PERMISSION_CURRICULUM_DELETE') OR define('PERMISSION_CURRICULUM_DELETE', 'curriculum-delete');

// Course permission
defined('PERMISSION_COURSE_VIEW') OR define('PERMISSION_COURSE_VIEW', 'course-view');
defined('PERMISSION_COURSE_CREATE') OR define('PERMISSION_COURSE_CREATE', 'course-create');
defined('PERMISSION_COURSE_EDIT') OR define('PERMISSION_COURSE_EDIT', 'course-edit');
defined('PERMISSION_COURSE_DELETE') OR define('PERMISSION_COURSE_DELETE', 'course-delete');

// Lesson permission
defined('PERMISSION_LESSON_VIEW') OR define('PERMISSION_LESSON_VIEW', 'lesson-view');
defined('PERMISSION_LESSON_CREATE') OR define('PERMISSION_LESSON_CREATE', 'lesson-create');
defined('PERMISSION_LESSON_EDIT') OR define('PERMISSION_LESSON_EDIT', 'lesson-edit');
defined('PERMISSION_LESSON_DELETE') OR define('PERMISSION_LESSON_DELETE', 'lesson-delete');

// Exercise permission
defined('PERMISSION_EXERCISE_VIEW') OR define('PERMISSION_EXERCISE_VIEW', 'exercise-view');
defined('PERMISSION_EXERCISE_CREATE') OR define('PERMISSION_EXERCISE_CREATE', 'exercise-create');
defined('PERMISSION_EXERCISE_EDIT') OR define('PERMISSION_EXERCISE_EDIT', 'exercise-edit');
defined('PERMISSION_EXERCISE_DELETE') OR define('PERMISSION_EXERCISE_DELETE', 'exercise-delete');

// Training permission
defined('PERMISSION_TRAINING_MANAGE') OR define('PERMISSION_TRAINING_MANAGE', 'training-manage');
defined('PERMISSION_TRAINING_VIEW') OR define('PERMISSION_TRAINING_VIEW', 'training-view');
defined('PERMISSION_TRAINING_CREATE') OR define('PERMISSION_TRAINING_CREATE', 'training-create');
defined('PERMISSION_TRAINING_EDIT') OR define('PERMISSION_TRAINING_EDIT', 'training-edit');
defined('PERMISSION_TRAINING_DELETE') OR define('PERMISSION_TRAINING_DELETE', 'training-delete');

// Exam permission
defined('PERMISSION_EXAM_MANAGE') OR define('PERMISSION_EXAM_MANAGE', 'exam-manage');
defined('PERMISSION_EXAM_ASSESS') OR define('PERMISSION_EXAM_ASSESS', 'exam-assess');
defined('PERMISSION_EXAM_VIEW') OR define('PERMISSION_EXAM_VIEW', 'exam-view');
defined('PERMISSION_EXAM_CREATE') OR define('PERMISSION_EXAM_CREATE', 'exam-create');
defined('PERMISSION_EXAM_EDIT') OR define('PERMISSION_EXAM_EDIT', 'exam-edit');
defined('PERMISSION_EXAM_DELETE') OR define('PERMISSION_EXAM_DELETE', 'exam-delete');
