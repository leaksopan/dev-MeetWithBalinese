-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 03:37 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meetwithbalinese`
--

-- --------------------------------------------------------

--
-- Table structure for table `answeroptions`
--

CREATE TABLE IF NOT EXISTS `answeroptions` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `option_text` text NOT NULL,
  `kasta_indicator` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `question_id` (`question_id`),
  KEY `kasta_indicator` (`kasta_indicator`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `kastacategory`
--

CREATE TABLE IF NOT EXISTS `kastacategory` (
  `kasta_id` int(11) NOT NULL,
  `kasta_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`kasta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id_1` int(11) DEFAULT NULL,
  `user_id_2` int(11) DEFAULT NULL,
  `match_score` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_best_match` tinyint(1) DEFAULT 0,
  `viewed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`match_id`),
  KEY `user_id_1` (`user_id_1`),
  KEY `user_id_2` (`user_id_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_text` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `useranswers`
--

CREATE TABLE IF NOT EXISTS `useranswers` (
  `user_answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_answer_id`),
  KEY `user_id` (`user_id`),
  KEY `question_id` (`question_id`),
  KEY `selected_option_id` (`selected_option_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `userkastaresult`
--

CREATE TABLE IF NOT EXISTS `userkastaresult` (
  `result_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `kasta_id` int(11) DEFAULT NULL,
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`result_id`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `kasta_id` (`kasta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answeroptions`
--
ALTER TABLE `answeroptions`
  ADD CONSTRAINT `answeroptions_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`),
  ADD CONSTRAINT `answeroptions_ibfk_2` FOREIGN KEY (`kasta_indicator`) REFERENCES `kastacategory` (`kasta_id`);

--
-- Constraints for table `useranswers`
--
ALTER TABLE `useranswers`
  ADD CONSTRAINT `useranswers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`),
  ADD CONSTRAINT `useranswers_ibfk_3` FOREIGN KEY (`selected_option_id`) REFERENCES `answeroptions` (`option_id`);

--
-- Constraints for table `userkastaresult`
--
ALTER TABLE `userkastaresult`
  ADD CONSTRAINT `userkastaresult_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `userkastaresult_ibfk_2` FOREIGN KEY (`kasta_id`) REFERENCES `kastacategory` (`kasta_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Insert data kasta
INSERT INTO `kastacategory` (`kasta_id`, `kasta_name`, `description`) VALUES
(1, 'Brahmana', 'Kasta tertinggi - berkaitan dengan spiritual dan pendidikan'),
(2, 'Ksatria', 'Kasta yang berkaitan dengan kepemimpinan dan kekuasaan'),
(3, 'Waisya', 'Kasta yang berkaitan dengan perdagangan dan ekonomi'),
(4, 'Sudra', 'Kasta yang berkaitan dengan pekerja dan masyarakat umum');

-- Pertanyaan Quiz 
INSERT INTO `questions` (`question_id`, `question_text`, `is_active`) VALUES
(1, 'Kalau kamu jadi makanan Bali, kamu lebih mirip apa nih?', 1),
(2, 'Di dunia pewayangan, kamu bakal jadi tokoh mana yang paling nyambung sama personality-mu?', 1),
(3, 'Pas ke pura, biasanya kamu tuh tipe yang gimana sih?', 1),
(4, 'Kalau diundang makan di rumah temen, posisi duduk kamu biasanya di mana?', 1),
(5, 'Kalau punya anak, nama yang paling kamu suka apa?', 1),
(6, 'Kalau tiba-tiba dapat modal dan harus buka warung, kamu jualan apa hayoo?', 1),
(7, 'Tengah malem ketemu Leak di jalan sepi, kamu bakal ngapain?', 1),
(8, 'Alien mendarat di Bali, dan kamu jadi duta bumi. Jabatan apa yang kamu minta?', 1),
(9, 'Lagi ada drama di grup WhatsApp keluarga besar, kamu ngapain?', 1),
(10, 'Kalau ada waktu luang sehari full, kamu bakal ngapain aja?', 1),
(11, 'Apa sih yang bikin kamu semangat bangun pagi?', 1),
(12, 'Gimana sih pandangan kamu soal spiritualitas dan ritual-ritual gitu?', 1),
(13, 'Menurut temen-temen deketmu, kelebihan kamu tuh apa?', 1),
(14, 'Soal duit nih, menurut kamu gimana?', 1),
(15, 'Yang paling kamu hargai dari hubungan sama orang lain tuh apa?', 1),
(16, 'Kalau bisa milih kerjaan apapun tanpa mikirin gaji, kamu mau jadi apa?', 1);

-- Opsi Jawaban
INSERT INTO `answeroptions` (`question_id`, `option_text`, `kasta_indicator`, `weight`) VALUES
(1, 'Lawar yang dipersiapkan dengan detail dan penuh perhatian', 1, 5),
(1, 'Babi guling yang selalu jadi pusat perhatian dan bikin orang ngiler', 2, 5),
(1, 'Sate lilit yang praktis tapi tetep bikin orang ketagihan', 3, 5),
(1, 'Tipat cantok sederhana yang enak dan disukai semua kalangan', 4, 5),
(2, 'Pendeta bijak yang selalu kasih wejangan sambil senyum misterius', 1, 5),
(2, 'Raja yang gagah dan selalu jadi panutan rakyatnya', 2, 5),
(2, 'Pedagang cerdik yang bisa jualan apapun', 3, 5),
(2, 'Rakyat biasa yang setia dan punya banyak teman', 4, 5),
(3, 'Yang dipanggil buat mimpin doa dan dikagumi semua orang', 1, 4),
(3, 'Yang sibuk koordinasi acara dan kasih arahan sana-sini', 2, 4),
(3, 'Yang bawa sumbangan paling spesial dan dibicarakan semua orang', 3, 4),
(3, 'Yang rajin bantu-bantu dan disukai semua orang', 4, 4),
(4, 'VIP section! Dipersilakan duduk di tempat spesial', 1, 3),
(4, 'Deket tuan rumah, biar bisa ngobrol penting-penting', 2, 3),
(4, 'Bebas pilih tempat tapi tetap jaga etika', 3, 3),
(4, 'Santuy aja, di mana ada space ya di situ', 4, 3),
(5, 'Ida Bagus/Ida Ayu yang elegan', 1, 5),
(5, 'Anak Agung/Cokorda yang berwibawa', 2, 5),
(5, 'I Gusti yang terhormat', 3, 5),
(5, 'I/Ni dengan nama Bali yang catchy', 4, 5),
(6, 'Konsultasi spiritual dan yoga retreat untuk turis bule', 1, 4),
(6, 'Kerajinan pusaka Bali dengan sentuhan modern', 2, 4),
(6, 'Bisnis kuliner fusion atau perhiasan kekinian', 3, 4),
(6, 'Warung makan sederhana tapi dijamin bikin nagih', 4, 4),
(7, 'Kasih mantra perlindungan sambil tetep kalem', 1, 3),
(7, 'Teriak tegas "Pergi kamu!" dengan gaya commander', 2, 3),
(7, 'Coba nego: "Om Leak, saya ada duit 50rb, jangan ganggu ya?"', 3, 3),
(7, 'Lari secepet Naruto sambil teriak "Toloooong!"', 4, 3),
(8, 'Penasihat spiritual alien (plus jadi influencer spiritualitas intergalaksi)', 1, 3),
(8, 'Kapten pasukan pertahanan bumi (dengan seragam keren)', 2, 3),
(8, 'Menteri perdagangan antar galaksi (biar bisa impor teknologi alien)', 3, 3),
(8, 'Tour guide ramah untuk alien-alien (plus jadi YouTuber alien content)', 4, 3),
(9, 'Jadi penengah bijak yang kasih wejangan plus quote dari kitab kuno', 1, 3),
(9, 'Ambil alih grup dan langsung kasih solusi tegas', 2, 3),
(9, 'Diem-diem amati, trus cari peluang untung dari situasi', 3, 3),
(9, 'Matiin notifikasi, balik lagi besok kalau udah adem', 4, 3),
(10, 'Me-time: baca buku, meditasi, atau nulis jurnal spiritual', 1, 2),
(10, 'Olahraga seru atau ngumpul bareng temen-temen', 2, 2),
(10, 'Browsing peluang investasi atau belajar skill baru yang menghasilkan', 3, 2),
(10, 'DIY project atau nge-game seharian tanpa diganggu', 4, 2),
(11, 'Mencari pencerahan baru dan berbagi kebijaksanaan', 1, 4),
(11, 'Jadi inspirasi dan pemimpin buat orang-orang sekitar', 2, 4),
(11, 'Target finansial yang belum tercapai dan ambisi sukses', 3, 4),
(11, 'Hidup sederhana tapi bahagia dengan skill yang kumiliki', 4, 4),
(12, 'Sangat penting! Itu pusat kehidupanku dan sumber energi', 1, 3),
(12, 'Penting sebagai panduan moral dan leadership value', 2, 3),
(12, 'Respect sih, tapi yang penting praktis dan menguntungkan', 3, 3),
(12, 'Yang penting happy dan ga merugikan orang lain', 4, 3),
(13, 'Kebijaksanaan dan kemampuan memberi nasihat yang pas', 1, 2),
(13, 'Keberanian, tegas, dan jago bikin keputusan cepat', 2, 2),
(13, 'Insting bisnis tajam dan networking yang luas', 3, 2),
(13, 'Kreativitas dan kemampuan bikin apapun jadi seru', 4, 2),
(14, 'Kekayaan spiritual jauh lebih penting dari materi, bro!', 1, 3),
(14, 'Duit penting untuk bikin perubahan dan memimpin', 2, 3),
(14, 'Duit adalah tujuan dan bukti kesuksesan, dong!', 3, 3),
(14, 'Yang penting cukup, ga perlu banyak-banyak', 4, 3),
(15, 'Koneksi spiritual dan kedalaman percakapan', 1, 2),
(15, 'Loyalitas dan kejujuran tanpa tedeng aling-aling', 2, 2),
(15, 'Hubungan yang saling menguntungkan dan grow together', 3, 2),
(15, 'Kesetiaan dan saling support dalam suka duka', 4, 2),
(16, 'Guru spiritual, peneliti filosofi, atau pemimpin komunitas meditasi', 1, 3),
(16, 'Pemimpin yang disegani, politisi, atau tentara elite', 2, 3),
(16, 'CEO startup, investor, atau influencer bisnis', 3, 3),
(16, 'Seniman, koki, atau pekerja kreatif yang bebas', 4, 3); 