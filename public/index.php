<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();

//
// Home Routes
//
$router->add('GET', '/',                        'HomeController', 'index');
$router->add('GET', '/home',                    'HomeController', 'index');
$router->add('GET', '/courses-json',            'HomeController', 'getCourses');

//
// Auth Routes
//
$router->add('GET',  '/login',                  'AuthController', 'loginIndex');
$router->add('POST', '/login_check.php',        'AuthController', 'login_check');
$router->add('GET',  '/logout',                 'AuthController', 'logout');
$router->add('GET',  '/register',               'AuthController', 'registerIndex');
$router->add('POST', '/register_student.php',   'AuthController', 'register');

//
// Settings & About
//
$router->add('GET', '/settings',                'SettingsController', 'settingsIndex');
$router->add('GET', '/about-us',                'AboutUsController',   'aboutUsIndex');

//
// Lesson Routes
//
$router->add('GET', '/lesson/{id}',                 'LessonController', 'lessonDetailsIndex');
$router->add('GET', '/course-detail-json/{id}',     'LessonController', 'lessonDetails');
$router->add('GET', '/lesson_menu/{id}',            'LessonController', 'lessonMenuIndex');

//
// Weekly Schedule
//
$router->add('GET', '/weekly-schedule',             'WeeklyScheduleController', 'WeeklyScheduleIndex');
$router->add('GET', '/get-schedules/{id}',          'WeeklyScheduleController', 'getSchedules');

//
// Media Routes
//
$router->add('GET',  '/get-lesson-contents/{id}',        'MediaController', 'getLessonContents');
$router->add('GET',  '/get-lesson-schedule/{id}',        'MediaController', 'getLessonSchedule');
$router->add('GET',  '/lesson/play_lesson_video/{filename}',   'MediaController', 'playVideoIndex');
$router->add('GET',  '/file-details/{filename}',         'MediaController', 'videoDetailsByFileName');
$router->add('GET',  '/lesson/view_lesson_image/{filename}',   'MediaController', 'viewImageIndex');
$router->add('GET',  '/lesson/view_lesson_document/{filename}', 'MediaController', 'viewDocumentIndex');
$router->add('POST', '/api/add-media',                   'UploadController', 'addMedia');

//
// Enrollment Routes
//
$router->add('GET',  '/Student-Enrolled/{studentId}/{lessonId}', 'EnrollmentController', 'checkEnrollmentStatus');
$router->add('POST', '/lesson/addEnrollment',                     'EnrollmentController', 'addEnrollments');
$router->add('GET',  '/my-lessons',                               'EnrollmentController', 'myLessonsIndex');
$router->add('GET',  '/Get-Enrolled-User/{id}',                   'EnrollmentController', 'getEnrolledCourses');

//
// Teacher Routes
//
$router->add('GET',  '/teacher-panel',                           'TeacherController', 'teacherPanelIndex');
$router->add('GET',  '/api/teacher-courses/{id}',                'TeacherController', 'getTeacherCourses');
$router->add('GET',  '/api/student-count/{id}',                  'TeacherController', 'getStudentCount');
$router->add('GET',  '/teacher-panel/course-detail/{id}',        'TeacherController', 'teacherCourseDetailIndex');
$router->add('POST', '/api/add-schedule',                        'TeacherController', 'teacherAddSchedule');
$router->add('GET',  '/get-schedules-teachers/{id}',             'TeacherController', 'getsTeacherSchedule');
$router->add('GET',  '/weekly-schedule-teacher',                 'TeacherController', 'TeacherScheduleIndex');

//
// Admin Routes
//
$router->add('GET',  '/admin',                                   'AdminController', 'adminIndex');
$router->add('GET',  '/admin/lessons',                           'AdminController', 'lessonIndex');
$router->add('GET',  '/admin/lessons/add_lessons',              'AdminController', 'addLessonIndex');
$router->add('GET',  '/admin/lessons/add_category',             'AdminController', 'addCategoryIndex');
$router->add('GET',  '/admin/lessons/add_teacher',              'AdminController', 'addTeacherIndex');
$router->add('GET',  '/admin/users/summary',                    'AdminController', 'UserSummaryIndex');
$router->add('GET',  '/admin/users/manage',                     'AdminController', 'UserManageIndex');
$router->add('GET',  '/admin/logs',                             'AdminController', 'AdminLogsIndex');

$router->add('GET',  '/api/admin/statistics',                   'AdminController', 'getUsersStat');
$router->add('GET',  '/api/admin/get-lessons.php',              'AdminController', 'getLessonsAPI');
$router->add('GET',  '/api/admin/get-teachers',                 'AdminController', 'getTeachersApi');
$router->add('GET',  '/api/admin/get-categories',               'AdminController', 'getCategoriesApi');
$router->add('GET',  '/api/admin/get-user',                     'AdminController', 'getUsersApi');
$router->add('GET',  '/api/admin/dell-user/{id}',               'AdminController', 'DelUserApi');
$router->add('POST', '/api/admin/add-lesson',                   'AdminController', 'addLessonApi');
$router->add('POST', '/api/admin/add-category',                 'AdminController', 'addCategoryApi');
$router->add('POST', '/api/admin/add-teacher',                  'AdminController', 'addTeacherApi');

//
// Dispatch
//
$method    = $_SERVER['REQUEST_METHOD'];
$basePath  = '/akademikx/public';
$uri       = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$router->dispatch($method, $uri);
