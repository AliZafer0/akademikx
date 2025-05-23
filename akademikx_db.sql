-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 23 May 2025, 00:27:20
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `akademikx_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(5, 'Yazılım'),
(6, 'Matematik'),
(7, 'Fizik'),
(8, 'Temel'),
(9, 'Kimya'),
(10, 'Tarih'),
(11, 'Edebiyat'),
(12, 'Biyoloji'),
(13, 'Felsefe'),
(14, 'Coğrafya');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `teacher_id` int(11) DEFAULT NULL,
  `img_url` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `category_id`, `created_by`, `created_at`, `teacher_id`, `img_url`) VALUES
(16, 'Kimya 101', 'Kimyaya giriş', 9, 24, '2025-05-23 00:56:16', 24, 'default.jpg'),
(17, 'Modern Tarih', '20. yüzyıl tarihi', 10, 25, '2025-05-23 00:56:16', 25, 'default.jpg'),
(18, 'Türk Edebiyatı', 'Türk edebiyatının önemli eserleri', 11, 25, '2025-05-23 00:56:16', 25, 'default.jpg'),
(19, 'Biyoloji Temelleri', 'Canlıların yapısı ve fonksiyonları', 12, 24, '2025-05-23 00:56:16', 24, 'default.jpg'),
(20, 'Felsefe Giriş', 'Felsefenin temel kavramları', 13, 24, '2025-05-23 00:56:16', 24, 'default.jpg'),
(21, 'Coğrafya Dersleri', 'Dünya coğrafyasına genel bakış', 14, 24, '2025-05-23 00:56:16', 24, 'default.jpg');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `course_contents`
--

CREATE TABLE `course_contents` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `type` enum('video','document','question','image') DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_url` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `course_contents`
--

INSERT INTO `course_contents` (`id`, `course_id`, `teacher_id`, `type`, `title`, `description`, `file_url`, `created_at`) VALUES
(48, 16, 24, 'video', 'Kimya Tanıtımı', 'Kimya dersine giriş videosu', 'chem_intro.mp4', '2025-05-23 00:56:16'),
(49, 16, 24, 'document', 'Atom Yapısı', 'Atom ve bileşenleri', 'atom_structure.pdf', '2025-05-23 00:56:16'),
(50, 17, 25, 'video', 'Modern Tarih 1', '20. yüzyıl başları', 'modern_history1.mp4', '2025-05-23 00:56:16'),
(51, 18, 25, 'document', 'Edebiyat Tarihi', 'Türk Edebiyatı kronolojisi', 'literature_history.pdf', '2025-05-23 00:56:16'),
(52, 19, 24, 'video', 'Biyoloji Giriş', 'Biyoloji temel kavramları', 'bio_intro.mp4', '2025-05-23 00:56:16'),
(53, 20, 24, 'image', 'Felsefe Temelleri', 'Felsefenin ana konuları', 'philosophy_basics.png', '2025-05-23 00:56:16'),
(54, 21, 24, 'video', 'Coğrafya Tanıtımı', 'Dünya haritası ve coğrafya', 'geography_intro.mp4', '2025-05-23 00:56:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `course_schedule`
--

CREATE TABLE `course_schedule` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `course_schedule`
--

INSERT INTO `course_schedule` (`id`, `course_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(20, 16, 'Monday', '09:00:00', '11:00:00'),
(21, 17, 'Wednesday', '14:00:00', '16:00:00'),
(22, 18, 'Friday', '10:00:00', '12:00:00'),
(23, 19, 'Tuesday', '13:00:00', '15:00:00'),
(24, 20, 'Thursday', '15:00:00', '17:00:00'),
(25, 21, 'Saturday', '10:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `enrolled_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enrolled_at`) VALUES
(14, 26, 16, '2025-05-23 00:56:16'),
(15, 26, 17, '2025-05-23 00:56:16'),
(16, 27, 18, '2025-05-23 00:56:16'),
(17, 27, 19, '2025-05-23 00:56:16'),
(18, 1, 20, '2025-05-23 00:56:16'),
(19, 26, 21, '2025-05-23 00:56:16'),
(20, 1, 16, '2025-05-23 01:05:25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `role`, `created_at`, `approved`) VALUES
(1, 'AliZafer', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'admin', '2025-05-07 09:55:34', 1),
(24, 'teacher2', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'teacher', '2025-05-22 21:56:16', 1),
(25, 'teacher3', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'teacher', '2025-05-22 21:56:16', 1),
(26, 'student2', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'student', '2025-05-22 21:56:16', 1),
(27, 'student3', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'student', '2025-05-22 21:56:16', 1),
(28, 'student4', '$2y$10$K5Cv6aeUhqJhAUC0nxu74uiwvQ5aUOh3aUQbiAaI5zBcI1eiF4Ys.', 'student', '2025-05-22 21:56:16', 0);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `courses_ibfk_3` (`teacher_id`);

--
-- Tablo için indeksler `course_contents`
--
ALTER TABLE `course_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Tablo için indeksler `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Tablo için indeksler `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `course_contents`
--
ALTER TABLE `course_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- Tablo için AUTO_INCREMENT değeri `course_schedule`
--
ALTER TABLE `course_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `courses_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `courses_ibfk_3` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `course_contents`
--
ALTER TABLE `course_contents`
  ADD CONSTRAINT `course_contents_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `course_contents_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`);

--
-- Tablo kısıtlamaları `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD CONSTRAINT `course_schedule_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
