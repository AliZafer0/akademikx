<?php
namespace App\Helpers;

class AuthHelper
{
    /**
     * Oturum açılıp açılmadığını kontrol eder.
     *
     * @return bool Oturum açılmışsa true, aksi halde false
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Mevcut kullanıcının kullanıcı adını döner.
     *
     * @return string Kullanıcı adı veya 'Misafir' (oturum yoksa)
     */
    public static function getUsername(): string
    {
        return $_SESSION['username'] ?? 'Misafir';
    }

    /**
     * Mevcut kullanıcının rolünü döner.
     *
     * @return string|null Rol adı veya null (oturum yoksa)
     */
    public static function getRole(): ?string
    {
        return $_SESSION['role'] ?? null;
    }

    /**
     * İstek URI'sinin son segmentini döner.
     *
     * @return string Son URI segmenti
     */
    public static function getLastUrlSegment(): string
    {
        $path  = $_SERVER['REQUEST_URI']; // Örnek: /akademikx/public/teacher-panel/course-detail/8
        $parts = explode('/', trim($path, '/'));
        return end($parts);
    }
}
