<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;
use App\Models\AdminModel;
use App\Models\Users;
use App\Models\CourseContents;
use App\Core\Auth;

class AdminController {

    private $courses;
    private $Auth;
    private $Admin;
    private $Users;
    private $CourseContents;

    /**
     * Sınıfı başlatır, oturumu açar ve gerekli modelleri yükler.
     *
     * @return void
     */
    public function __construct()
    {
        session_start();
        $this->courses        = new Courses();
        $this->Auth           = new Auth();
        $this->Admin          = new AdminModel();
        $this->Users          = new Users();
        $this->CourseContents = new CourseContents();

        $this->Auth->isLogin();

        if (!$this->Users->hasRole('admin', $_SESSION['user_id'])) {
            require_once __DIR__ . '/../Views/Error/401.php';
            exit; // Hata sayfasından sonra script devam etmesin diye
        }
    }

    /**
     * API: Test listesini JSON olarak döner.
     *
     * Sorgu parametreleri: page, limit, search
     * Döner: { tests: [...], totalPages: N }
     *
     * @link GET /api/admin/get-tests
     * @return void
     */
    public function getTests() {
        header('Content-Type: application/json; charset=utf-8');

        $page   = max(1, (int)($_GET['page']   ?? 1));
        $limit  = max(1, (int)($_GET['limit']  ?? 10));
        $search = trim((string)($_GET['search'] ?? ''));

        $offset     = ($page - 1) * $limit;
        $totalCount = $this->CourseContents->countTests($search);
        $tests      = $this->CourseContents->getTests($limit, $offset, $search);
        $totalPages = $limit ? (int)ceil($totalCount / $limit) : 1;

        echo json_encode([
            'tests'      => $tests,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * API: Tek bir test kaydını siler ve JSON mesaj döner.
     *
     * @link DELETE /api/tests/{id}
     * @param int $id
     * @return void
     */
    public function deleteTest(int $id) {
        header('Content-Type: application/json; charset=utf-8');

        if ($this->CourseContents->deleteTest($id)) {
            echo json_encode(['message' => 'Test silindi.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Test silinirken hata oluştu.']);
        }
    }

    /**
     * Admin paneli – test yönetim sayfasını görüntüler.
     *
     * @return void
     */
    public function testsAdminIndex()
    {
        require_once __DIR__ . '/../Views/Admin/tests.php';
    }

    /**
     * Admin paneli anasayfasını görüntüler.
     *
     * @return void
     */
    public function adminIndex()
    {
        require_once __DIR__ . '/../Views/Admin/index.php';
    }

    /**
     * Admin paneli ders listesini görüntüler.
     *
     * @return void
     */
    public function lessonIndex()
    {
        require_once __DIR__ . '/../Views/Admin/lessons.php';
    }

    /**
     * Admin paneli ders ekleme sayfasını görüntüler.
     *
     * @return void
     */
    public function addLessonIndex()
    {
        require_once __DIR__ . '/../Views/Admin/add-lesson.php';
    }

    /**
     * Admin paneli kategori ekleme sayfasını görüntüler.
     *
     * @return void
     */
    public function addCategoryIndex()
    {
        require_once __DIR__ . '/../Views/Admin/add-category.php';
    }

    /**
     * Admin paneli öğretmen ekleme sayfasını görüntüler.
     *
     * @return void
     */
    public function addTeacherIndex()
    {
        require_once __DIR__ . '/../Views/Admin/add-teacher.php';
    }

    /**
     * Admin paneli kullanıcı özet sayfasını görüntüler.
     *
     * @return void
     */
    public function UserSummaryIndex()
    {
        require_once __DIR__ . '/../Views/Admin/user-summary.php';
    }

    /**
     * Admin paneli kullanıcı yönetim sayfasını görüntüler.
     *
     * @return void
     */
    public function UserManageIndex()
    {
        require_once __DIR__ . '/../Views/Admin/user-manage.php';
    }

    /**
     * Admin paneli loglar sayfasını görüntüler.
     *
     * @return void
     */
    public function AdminLogsIndex()
    {
        require_once __DIR__ . '/../Views/Admin/admin-logs.php';
    }

    /**
     * API: Kullanıcı istatistiklerini JSON olarak döner.
     *
     * @link GET /api/admin/statistics
     * @return void
     */
    public function getUsersStat()
    {
        header('Content-Type: application/json');
        echo json_encode($this->Admin->getUserStat());
    }

    /**
     * API: Yeni kategori ekler ve sayfayı yönlendirir.
     *
     * @link POST /api/admin/add-category
     * @return void
     */
    public function addCategoryApi()
    {
        $title = $_POST['title'];

        if ($this->courses->addCategory($title)) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
            exit;
        }
    }

    /**
     * API: Ders listesini JSON olarak döner.
     *
     * @link GET /api/admin/get-lessons.php
     * @return void
     */
    public function getLessonsAPI()
    {
        $limit  = isset($_GET['limit'])  ? (int)$_GET['limit']  : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        $lessons = $this->courses->getLessons($limit, $offset);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data'   => $lessons
        ]);
        exit;
    }

    /**
     * API: Öğretmen listesini JSON olarak döner.
     *
     * @link GET /api/admin/get-teachers
     * @return void
     */
    public function getTeachersApi()
    {
        header('Content-Type: application/json');
        echo json_encode($this->Users->getUserByRole('teacher'));
    }

    /**
     * API: Kategori listesini JSON olarak döner.
     *
     * @link GET /api/admin/get-categories
     * @return void
     */
    public function getCategoriesApi()
    {
        header('Content-Type: application/json');
        echo json_encode($this->courses->getCategories());
    }

    /**
     * API: Yeni ders ekler ve sayfayı yönlendirir.
     *
     * @link POST /api/admin/add-lesson
     * @return void
     */
    public function addLessonApi()
    {
        $title       = $_POST['title'];
        $description = $_POST['description'];
        $category    = $_POST['category'];
        $teacher     = $_POST['teacher'];

        if ($this->courses->addCourse($title, $description, $category, $teacher)) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
            exit;
        }
    }

    /**
     * API: Yeni öğretmen ekler ve sayfayı yönlendirir.
     *
     * @link POST /api/admin/add-teacher
     * @return void
     */
    public function addTeacherApi()
    {
        $showModal      = false;
        $createdTeacher = [];

        if (isset($_POST['username'], $_POST['password'], $_POST['approv'])) {
            $username      = trim($_POST['username']);
            $plainPassword = $_POST['password'];
            $password      = password_hash($plainPassword, PASSWORD_DEFAULT);
            $approv        = (int)$_POST['approv'];
            $role          = "teacher";

            if ($this->Users->usernameExists($username)) {
                echo "❌ Bu kullanıcı adı zaten alınmış.";
                return;
            }

            $data = [
                'username' => $username,
                'password' => $password,
                'approv'   => $approv,
                'role'     => $role
            ];

            if ($this->Users->addTeacher($data)) {
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
                exit;
            } else {
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
                exit;
            }
        }
    }

    /**
     * API: Tüm kullanıcıları JSON olarak döner.
     *
     * @link GET /api/admin/get-user
     * @return void
     */
    public function getUsersApi()
    {
        header('Content-Type: application/json');
        echo json_encode($this->Users->getUsers());
    }

    /**
     * API: Belirtilen ID'li kullanıcıyı siler ve sayfayı yönlendirir.
     *
     * @link GET /api/admin/dell-user/{id}
     * @param int $id
     * @return void
     */
    public function DelUserApi($id)
    {
        if ($this->Users->DelUserById($id)) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
            exit;
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
            exit;
        }
    }
}
