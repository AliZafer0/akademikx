<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Core\Router;

$router = new Router();

/**
 * Home routes
 */
$router->add('GET', '/',             'HomeController',   'index');
$router->add('GET', '/home',         'HomeController',   'index');
$router->add('GET', '/courses-json', 'HomeController',   'getCourses');

/**
 * Auth routes
 */
$router->add('GET',  '/login',                'AuthController',  'loginIndex');
$router->add('POST', '/login_check.php',      'AuthController',  'login_check');
$router->add('GET',  '/logout',               'AuthController',  'logout');
$router->add('GET',  '/register',             'AuthController',  'registerIndex');
$router->add('POST', '/register_student.php', 'AuthController',  'register');

/**
 * Settings & About
 */
$router->add('GET', '/settings',  'SettingsController', 'settingsIndex');
$router->add('GET', '/about-us',  'AboutUsController',  'aboutUsIndex');

/**
 * Lesson routes
 */
$router->add('GET', '/lesson/{id}',               'LessonController', 'lessonDetailsIndex');
$router->add('GET', '/course-detail-json/{id}',   'LessonController', 'lessonDetails');
$router->add('GET', '/lesson_menu/{id}',          'LessonController', 'lessonMenuIndex');

/**
 * Weekly Schedule
 */
$router->add('GET', '/weekly-schedule',       'WeeklyScheduleController', 'WeeklyScheduleIndex');
$router->add('GET', '/get-schedules/{id}',    'WeeklyScheduleController', 'getSchedules');

/**
 * Media routes
 */
$router->add('GET',  '/get-lesson-contents/{id}',       'MediaController', 'getLessonContents');
$router->add('GET',  '/get-lesson-schedule/{id}',       'MediaController', 'getLessonSchedule');
$router->add('GET',  '/lesson/play_lesson_video/{filename}', 'MediaController', 'playVideoIndex');
$router->add('GET',  '/file-details/{filename}',        'MediaController', 'videoDetailsByFileName');
$router->add('GET',  '/lesson/view_lesson_image/{filename}',   'MediaController', 'viewImageIndex');
$router->add('GET',  '/lesson/view_lesson_document/{filename}', 'MediaController', 'viewDocumentIndex');
$router->add('POST', '/api/add-media',               'UploadController', 'addMedia');

/**
 * Enrollment routes
 */
$router->add('GET',  '/Student-Enrolled/{studentId}/{lessonId}', 'EnrollmentController', 'checkEnrollmentStatus');
$router->add('POST', '/lesson/addEnrollment',                    'EnrollmentController', 'addEnrollments');
$router->add('GET',  '/my-lessons',                              'EnrollmentController', 'myLessonsIndex');
$router->add('GET',  '/Get-Enrolled-User/{id}',                  'EnrollmentController', 'getEnrolledCourses');

/**
 * Teacher routes
 */
$router->add('GET',  '/teacher-panel',                  'TeacherController', 'teacherPanelIndex');
$router->add('GET',  '/api/teacher-courses/{id}',       'TeacherController', 'getTeacherCourses');
$router->add('GET',  '/api/student-count/{id}',         'TeacherController', 'getStudentCount');
$router->add('GET',  '/teacher-panel/course-detail/{id}','TeacherController', 'teacherCourseDetailIndex');
$router->add('POST', '/api/add-schedule',               'TeacherController', 'teacherAddSchedule');
$router->add('GET',  '/get-schedules-teachers/{id}',    'TeacherController', 'getsTeacherSchedule');
$router->add('GET',  '/weekly-schedule-teacher',        'TeacherController', 'TeacherScheduleIndex');

/**
 * Test content routes
 */
$router->add('GET',  '/api/tests/by-content', 'TestsController', 'getTestsByContent');
$router->add('GET',  '/api/tests/structure',  'TestsController', 'getTestStructure');
$router->add('POST', '/api/tests/start',      'TestsController', 'startTest');
$router->add('POST', '/api/tests/submit-answer','TestsController', 'submitAnswer');
$router->add('POST', '/api/tests/finish',     'TestsController', 'finishTest');
$router->add('GET',  '/api/tests/answers',    'TestsController', 'getUserAnswers');

/**
 * Test CRUD
 */
$router->add('POST', '/api/tests/create', 'TestsController', 'createTest');
$router->add('POST', '/api/tests/update', 'TestsController', 'updateTest');
$router->add('POST', '/api/tests/delete', 'TestsController', 'deleteTest');

/**
 * Question CRUD
 */
$router->add('POST', '/api/questions/create', 'TestsController', 'addQuestion');
$router->add('POST', '/api/questions/update', 'TestsController', 'updateQuestion');
$router->add('POST', '/api/questions/delete', 'TestsController', 'deleteQuestion');

/**
 * Option CRUD
 */
$router->add('POST', '/api/options/create',     'TestsController', 'addOption');
$router->add('POST', '/api/options/delete-all', 'TestsController', 'deleteOptionsByQuestion');

/**
 * Teacher panel views
 */
$router->add('GET', '/teacher/tests/create',     'TeacherController', 'createTestPage');
$router->add('GET', '/teacher/questions/create', 'TeacherController', 'addQuestionPage');
$router->add('GET', '/teacher/options/create',   'TeacherController', 'addOptionPage');

/**
 * Additional test routes
 */
$router->add('GET', '/api/tests/by-lesson/{id}', 'TestsController', 'getTestsByLesson');
$router->add('GET', '/lesson/quiz_detail/{id}',  'TeacherController', 'detailView');
$router->add('GET', '/api/tests/{id}',           'TeacherController', 'getDetail');
$router->add('POST','/api/tests/{id}/submit',    'TeacherController', 'submit');
$router->add('GET', '/api/tests/{id}/status',    'TeacherController', 'status');

/**
 * Admin test routes
 */
$router->add('GET',    '/api/admin/get-tests',           'AdminController', 'getTests');
$router->add('GET',    '/admin/lessons/tests',           'AdminController', 'testsAdminIndex');
$router->add('DELETE', '/api/tests/{id}',               'AdminController', 'deleteTest');

/**
 * Admin panel routes
 */
$router->add('GET', '/admin',                          'AdminController', 'adminIndex');
$router->add('GET', '/admin/lessons',                  'AdminController', 'lessonIndex');
$router->add('GET', '/admin/lessons/add_lessons',      'AdminController', 'addLessonIndex');
$router->add('GET', '/admin/lessons/add_category',     'AdminController', 'addCategoryIndex');
$router->add('GET', '/admin/lessons/add_teacher',      'AdminController', 'addTeacherIndex');
$router->add('GET', '/admin/users/summary',            'AdminController', 'UserSummaryIndex');
$router->add('GET', '/admin/users/manage',             'AdminController', 'UserManageIndex');
$router->add('GET', '/admin/logs',                     'AdminController', 'AdminLogsIndex');

/**
 * Admin API routes
 */
$router->add('GET',  '/api/admin/statistics',          'AdminController', 'getUsersStat');
$router->add('GET',  '/api/admin/get-lessons.php',     'AdminController', 'getLessonsAPI');
$router->add('GET',  '/api/admin/get-teachers',        'AdminController', 'getTeachersApi');
$router->add('GET',  '/api/admin/get-categories',      'AdminController', 'getCategoriesApi');
$router->add('GET',  '/api/admin/get-user',            'AdminController', 'getUsersApi');
$router->add('GET',  '/api/admin/dell-user/{id}',      'AdminController', 'DelUserApi');
$router->add('POST', '/api/admin/add-lesson',          'AdminController', 'addLessonApi');
$router->add('POST', '/api/admin/add-category',        'AdminController', 'addCategoryApi');
$router->add('POST', '/api/admin/add-teacher',         'AdminController', 'addTeacherApi');

/**
 * Dispatch request
 */
$method   = $_SERVER['REQUEST_METHOD'];
$basePath = '/akademikx/public';
$uri      = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$router->dispatch($method, $uri);
