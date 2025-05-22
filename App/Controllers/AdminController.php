<?php
namespace App\Controllers;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\Courses;
use App\Models\AdminModel;
use App\Models\Users;
use App\Core\Auth;

class AdminController {

    private $courses;
    private $Auth;
    private $Admin;
    private $Users;

    
    public function __construct()
    {
        session_start();
        $this->courses = new Courses();
        $this->Auth = new Auth();
        $this->Admin = new AdminModel();
        $this->Users = new Users();
    }
   
    public function adminIndex()
    {
        require_once __DIR__ . '/../Views/Admin/index.php';
    }
    
    public function lessonIndex()
    {
        require_once __DIR__ . '/../Views/Admin/lessons.php';
    }
    
    public function addLessonIndex() {
       require_once __DIR__ . '/../Views/Admin/add-lesson.php';
    }

    public function addCategoryIndex() {
       require_once __DIR__ . '/../Views/Admin/add-category.php';
    }

    public function addTeacherIndex() {
        require_once __DIR__ . '/../Views/Admin/add-teacher.php';

    }

    public function UserSummaryIndex() {
        require_once __DIR__ . '/../Views/Admin/user-summary.php';

    }

    public function UserManageIndex() {
         require_once __DIR__ . '/../Views/Admin/user-manage.php';

    }

    public function AdminLogsIndex() {
          require_once __DIR__ . '/../Views/Admin/admin-logs.php';

    }
    public function getUsersStat()
    {
        header('Content-Type: application/json');
        echo json_encode($this->Admin->getUserStat());
    }
    public function addCategoryApi()
    {
        $title = $_POST['title'];

        if($this->courses->addCategory($title))
        {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
            exit;
        }
        else{
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
            exit;
        }
    }
    public function getLessonsAPI()
    {
        // GET parametreleri: limit ve offset, yoksa default 10 ve 0
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

        $lessons = $this->courses->getLessons($limit, $offset);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $lessons
        ]);
        exit;
    }
    public function getTeachersApi()
    {
        header('Content-Type: application/json');
        echo json_encode($this->Users->getUserByRole('teacher'));
    }
    public function getCategoriesApi()
    {
        header('Content-Type: application/json');
        echo json_encode($this->courses->getCategories()); 
    }
    public function addLessonApi()
    {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $teacher = $_POST['teacher'];

        if($this->courses->addCourse($title,$description,$category,$teacher))
        {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
            exit;
        }
        else{
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
            exit;
        }
    }
    public function addTeacherApi()
        {
            $showModal = false;
            $createdTeacher = [];

            if (isset($_POST['username'], $_POST['password'], $_POST['approv'])) {
                $username = trim($_POST['username']);
                $plainPassword = $_POST['password'];
                $password = password_hash($plainPassword, PASSWORD_DEFAULT);
                $approv = (int)$_POST['approv'];
                $role = "teacher";

                // Kullanıcı adı kontrolü
                if ($this->Users->usernameExists($username)) {
                    echo "❌ Bu kullanıcı adı zaten alınmış.";
                    return;
                }

                // Kullanıcıyı ekle
                $data = [
                    'username' => $username,
                    'password' => $password,
                    'approv'   => $approv,
                    'role'     => $role
                ];

                if ($this->Users->addTeacher($data)) {
                    $showModal = true;
                    $createdTeacher = [
                        'username' => $username,
                        'password' => $plainPassword // Modal için düz hali
                    ];
                    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?succsess');
                     exit;
                } else {
                    header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error');
                     exit;
                }
            }

            
        }
            

}
