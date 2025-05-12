<?php

    function isUserLoggedIn()
    {
        return isset($_SESSION["user_id"]);
    }
    function idTeacherUsername($pdo, $id)
    {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt_teacher = $pdo->prepare($sql);
        $stmt_teacher->execute([$id]); // execute burada doğru kullanılıyor.
        $teacher = $stmt_teacher->fetch(PDO::FETCH_ASSOC);

        return $teacher['username'] ?? 'Unknown';
    }
    function getStudentCountByCourseId($pdo,$course_id)
    {

        $stmt = $pdo->prepare("SELECT COUNT(*) as student_count FROM enrollments WHERE course_id = :course_id");
        $stmt->execute(['course_id' => $course_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['student_count'] ?? 0;
    }
    function translateDay($day) {
    $days = [
        'Monday'    => 'Pazartesi',
        'Tuesday'   => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday'  => 'Perşembe',
        'Friday'    => 'Cuma',
        'Saturday'  => 'Cumartesi',
        'Sunday'    => 'Pazar',
    ];

    return $days[$day] ?? $day; // Eğer geçerli bir gün yoksa, orijinal değer döner.
    }
    function courseTeacher($pdo, $id, $teacher_id): bool
    {
        $sql = "SELECT COUNT(*) as count FROM courses WHERE id = :id AND teacher_id = :teacher_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'teacher_id' => $teacher_id
        ]);

        $status = $stmt->fetch(PDO::FETCH_ASSOC);
        if($status['count'] > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }
    function documentUrlToCourse($pdo, $url)
    {
        $sql = "SELECT * FROM course_contents WHERE file_url = :url";
        $stmt_url = $pdo->prepare($sql);
        $stmt_url->execute(['url' => $url]);  // Parametreli sorgu
        $url = $stmt_url->fetch(PDO::FETCH_ASSOC);
        return $url['course_id'] ?? null;  // Hata durumunda null döndür
    }

   function courseElm($pdo, $id, $userid)
{
    // Öğretmen kontrolü
    $sql = "SELECT COUNT(*) as count FROM course_contents WHERE course_id = :id AND teacher_id = :userid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'userid' => $userid  // Parametrelerin doğru eşleştiğinden emin ol
    ]);
    $status_teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    // Öğrenci kontrolü
    $sql = "SELECT COUNT(*) as count FROM enrollments WHERE student_id = :id AND course_id = :userid";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $userid,  // Öğrencinin id'sini doğru göndermelisin
        'userid' => $id   // Ders id'sini doğru göndermelisin
    ]);
    $status_student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Sonuç döndürme
    if ($status_student['count'] > 0 || $status_teacher['count'] > 0) {
        return true;
    } else {
        return false;
    }
}
 function studentlesson($pdo,$user_id,$lesson_id)
 {
    $sql = "SELECT COUNT(*) as count FROM enrollments WHERE student_id = '$user_id' and course_id = '$lesson_id'";
    $stmt_user = $pdo->prepare($sql);
    $stmt_user->execute();
    $user  = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if($user['count'] > 0)
    {
        return true;
    }
    else{
        return false;
    }
 }
 function studentlessonfromid($pdo,$user_id,$course_id)
 {
    $sql = "SELECT COUNT(*) as count FROM enrollments WHERE student_id = '$user_id' and course_id = '$course_id'";
    $stmt_user = $pdo->prepare($sql);
    $stmt_user->execute();
    $user  = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if($user['count'] > 0)
    {
        return true;
    }
    else{
        return false;
    }
 }
 function truncate_description($text, $word_limit = 4) {
    // Metni kelimelere ayırıyoruz
    $words = explode(' ', $text);
    
    // Eğer kelime sayısı belirlenen sınırdan fazla ise, sadece ilk $word_limit kadar kelime alıyoruz
    if (count($words) > $word_limit) {
        $words = array_slice($words, 0, $word_limit);
        // Sonra eklemek için '...' ekliyoruz
        return implode(' ', $words) . '...';
    }
    
    // Eğer kelime sayısı limitten küçükse, olduğu gibi geri döndürüyoruz
    return implode(' ', $words);
}

?>