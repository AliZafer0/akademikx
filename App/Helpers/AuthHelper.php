<?php
namespace App\Helpers;

class AuthHelper
{
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function getUsername()
    {
        return $_SESSION['username'] ?? 'Misafir';
    }

    public static function getRole()
    {
        return $_SESSION['role'] ?? null;
    }
    public static function getLastUrlSegment() {
        $path = $_SERVER['REQUEST_URI'];           // Örnek: /akademikx/public/teacher-panel/course-detail/8
        $parts = explode('/', trim($path, '/'));
        $last = end($parts);
        return $last;
    }

}
