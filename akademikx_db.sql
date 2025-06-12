-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 12 Haz 2025, 18:12:31
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
(17, 'Modern Tarih', '20. yüzyıl tarihi', 10, 25, '2025-05-23 00:56:16', 24, 'default.jpg'),
(18, 'Türk Edebiyatı', 'Türk edebiyatının önemli eserleri', 11, 25, '2025-05-23 00:56:16', 24, 'default.jpg'),
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
(54, 21, 24, 'video', 'Coğrafya Tanıtımı', 'Dünya haritası ve coğrafya', 'geography_intro.mp4', '2025-05-23 00:56:16'),
(66, 16, 24, 'question', 'Örnek Test – Kurs 16', 'Bu test, Kurs 16 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(67, 17, 24, 'question', 'Örnek Test – Kurs 17', 'Bu test, Kurs 17 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(68, 18, 24, 'question', 'Örnek Test – Kurs 18', 'Bu test, Kurs 18 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(69, 19, 24, 'question', 'Örnek Test – Kurs 19', 'Bu test, Kurs 19 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(70, 20, 24, 'question', 'Örnek Test – Kurs 20', 'Bu test, Kurs 20 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(71, 21, 24, 'question', 'Örnek Test – Kurs 21', 'Bu test, Kurs 21 için örnek sorulardan oluşur.', NULL, '2025-06-11 08:00:37'),
(72, 18, 24, '', 'Test -1', NULL, NULL, '2025-06-11 08:47:06');

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
(20, 1, 16, '2025-05-23 01:05:25'),
(21, 26, 19, '2025-06-11 10:56:19');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `student_test_status`
--

CREATE TABLE `student_test_status` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `attempted_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `student_test_status`
--

INSERT INTO `student_test_status` (`id`, `user_id`, `test_id`, `attempted_at`) VALUES
(1, 24, 18, '2025-06-11 08:26:18'),
(3, 26, 18, '2025-06-11 08:56:03'),
(4, 26, 23, '2025-06-11 08:56:27');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tests`
--

CREATE TABLE `tests` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `tests`
--

INSERT INTO `tests` (`id`, `content_id`, `title`, `duration`, `created_at`) VALUES
(18, 66, 'Örnek Test – Kurs 16', 30, '2025-06-11 08:00:37'),
(19, 67, 'Örnek Test – Kurs 17', 25, '2025-06-11 08:00:37'),
(20, 68, 'Örnek Test – Kurs 18', 20, '2025-06-11 08:00:37'),
(21, 69, 'Örnek Test – Kurs 19', 35, '2025-06-11 08:00:37'),
(22, 70, 'Örnek Test – Kurs 20', 15, '2025-06-11 08:00:37'),
(23, 71, 'Örnek Test – Kurs 21', 30, '2025-06-11 08:00:37');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_questions`
--

CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `type` enum('multiple','open') NOT NULL,
  `points` int(11) DEFAULT 1,
  `order_no` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_questions`
--

INSERT INTO `test_questions` (`id`, `test_id`, `question_text`, `type`, `points`, `order_no`) VALUES
(29, 18, '2 + 2 kaç eder?', 'multiple', 1, 1),
(30, 18, 'Bu derste öğrendiğiniz en önemli kavramı kısaca açıklayın.', 'open', 2, 2),
(31, 19, 'Türkiye’nin nüfusu 2025 yılında yaklaşık kaç milyondur?', 'multiple', 1, 1),
(32, 19, 'Coğrafyada öğrendiğiniz en ilginç bilgiyi paylaşın.', 'open', 2, 2),
(33, 20, '10 x 3 kaçtır?', 'multiple', 1, 1),
(34, 20, 'Bu derste ele alınan temel teoriyi açıklayın.', 'open', 2, 2),
(35, 21, 'pH değeri 7 nötr mü asidik mi?', 'multiple', 1, 1),
(36, 21, 'Kimyada öğrendiğiniz en yararlı kavram nedir?', 'open', 2, 2),
(37, 22, 'Dünya\'nın en büyük okyanusu hangisidir?', 'multiple', 1, 1),
(38, 22, 'Bu derste en çok hangi konuda zorlandınız?', 'open', 2, 2),
(39, 23, 'HTML5 hangi yıl yayınlandı?', 'multiple', 1, 1),
(40, 23, 'Web teknolojilerinde gelecekte neyi öğrenmek istersiniz?', 'open', 2, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `test_question_options`
--

CREATE TABLE `test_question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `test_question_options`
--

INSERT INTO `test_question_options` (`id`, `question_id`, `option_text`, `is_correct`) VALUES
(54, 29, '4', 1),
(55, 29, '3', 0),
(56, 29, '5', 0),
(57, 29, '2', 0),
(58, 31, '85', 1),
(59, 31, '75', 0),
(60, 31, '95', 0),
(61, 31, '65', 0),
(62, 33, '30', 1),
(63, 33, '20', 0),
(64, 33, '40', 0),
(65, 33, '10', 0),
(66, 35, 'Nötr', 1),
(67, 35, 'Asidik', 0),
(68, 35, 'Bazik', 0),
(69, 35, 'Pasif', 0),
(70, 37, 'Pasifik', 1),
(71, 37, 'Atlantik', 0),
(72, 37, 'Hint', 0),
(73, 37, 'Arktik', 0),
(74, 39, '2014', 1),
(75, 39, '2012', 0),
(76, 39, '2016', 0),
(77, 39, '2010', 0);

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
(1, 'AliZafer', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'admin', '2025-05-07 09:55:34', 1),
(24, 'teacher2', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'teacher', '2025-05-22 21:56:16', 1),
(25, 'teacher3', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'teacher', '2025-05-22 21:56:16', 1),
(26, 'student2', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'student', '2025-05-22 21:56:16', 1),
(27, 'student3', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'student', '2025-05-22 21:56:16', 1),
(28, 'student4', '$2y$10$lE.fjWxFXolkLYldCJ9evOevi9IPfL9ZZ8qsFGnN25M/OYr4r669q', 'student', '2025-05-22 21:56:16', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_answers`
--

INSERT INTO `user_answers` (`id`, `attempt_id`, `question_id`, `selected_option_id`, `answer_text`, `is_correct`, `score`) VALUES
(1, 1, 29, 55, NULL, 0, 0),
(2, 1, 30, NULL, 'asd', 1, 2),
(3, 2, 29, 55, NULL, 0, 0),
(4, 2, 30, NULL, 'denemer', 1, 2),
(8, 4, 29, 54, NULL, 1, 1),
(9, 4, 30, NULL, 'deneme', 1, 2),
(10, 5, 39, 74, NULL, 1, 1),
(11, 5, 40, NULL, 'deneme', 1, 2);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user_test_attempts`
--

CREATE TABLE `user_test_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL,
  `total_score` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `user_test_attempts`
--

INSERT INTO `user_test_attempts` (`id`, `user_id`, `test_id`, `started_at`, `completed_at`, `total_score`) VALUES
(1, 24, 18, '2025-06-11 08:22:13', '2025-06-11 08:22:13', 2),
(2, 24, 18, '2025-06-11 08:26:18', '2025-06-11 08:26:18', 2),
(4, 26, 18, '2025-06-11 08:56:03', '2025-06-11 08:56:03', 3),
(5, 26, 23, '2025-06-11 08:56:27', '2025-06-11 08:56:27', 3);

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
-- Tablo için indeksler `student_test_status`
--
ALTER TABLE `student_test_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_user_test` (`user_id`,`test_id`),
  ADD KEY `fk_sts_test` (`test_id`);

--
-- Tablo için indeksler `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `content_id` (`content_id`);

--
-- Tablo için indeksler `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_id` (`test_id`);

--
-- Tablo için indeksler `test_question_options`
--
ALTER TABLE `test_question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Tablo için indeksler `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `selected_option_id` (`selected_option_id`);

--
-- Tablo için indeksler `user_test_attempts`
--
ALTER TABLE `user_test_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Tablo için AUTO_INCREMENT değeri `course_schedule`
--
ALTER TABLE `course_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `student_test_status`
--
ALTER TABLE `student_test_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `tests`
--
ALTER TABLE `tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Tablo için AUTO_INCREMENT değeri `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Tablo için AUTO_INCREMENT değeri `test_question_options`
--
ALTER TABLE `test_question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Tablo için AUTO_INCREMENT değeri `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Tablo için AUTO_INCREMENT değeri `user_test_attempts`
--
ALTER TABLE `user_test_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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

--
-- Tablo kısıtlamaları `student_test_status`
--
ALTER TABLE `student_test_status`
  ADD CONSTRAINT `fk_sts_test` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `course_contents` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `test_question_options`
--
ALTER TABLE `test_question_options`
  ADD CONSTRAINT `test_question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `test_questions` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `user_test_attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `test_questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_3` FOREIGN KEY (`selected_option_id`) REFERENCES `test_question_options` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `user_test_attempts`
--
ALTER TABLE `user_test_attempts`
  ADD CONSTRAINT `user_test_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_test_attempts_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
