CREATE TABLE IF NOT EXISTS `questions` (
    `question_id` INT AUTO_INCREMENT PRIMARY KEY,
    `question_text` TEXT NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Buat tabel kastacategory jika belum ada
CREATE TABLE IF NOT EXISTS `kastacategory` (
    `kasta_id` INT PRIMARY KEY,
    `kasta_name` VARCHAR(50) NOT NULL,
    `description` TEXT DEFAULT NULL
);

-- Buat tabel answeroptions jika belum ada
CREATE TABLE IF NOT EXISTS `answeroptions` (
    `option_id` INT AUTO_INCREMENT PRIMARY KEY,
    `question_id` INT DEFAULT NULL,
    `option_text` TEXT NOT NULL,
    `kasta_indicator` INT DEFAULT NULL,
    `weight` INT DEFAULT NULL,
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`question_id`) ON DELETE CASCADE,
    FOREIGN KEY (`kasta_indicator`) REFERENCES `kastacategory`(`kasta_id`)
);

-- Buat tabel users jika belum ada
CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `instagram` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `username` VARCHAR(50) DEFAULT NULL,
    `age` INT DEFAULT NULL,
    `gender` ENUM('Male','Female') NOT NULL
);

-- Buat tabel useranswers jika belum ada
CREATE TABLE IF NOT EXISTS `useranswers` (
    `user_answer_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `question_id` INT DEFAULT NULL,
    `selected_option_id` INT DEFAULT NULL,
    `answered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`question_id`) REFERENCES `questions`(`question_id`),
    FOREIGN KEY (`selected_option_id`) REFERENCES `answeroptions`(`option_id`)
);

-- Buat tabel userkastaresult jika belum ada
CREATE TABLE IF NOT EXISTS `userkastaresult` (
    `result_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `kasta_id` INT DEFAULT NULL,
    `confidence_score` DECIMAL(5,2) DEFAULT NULL,
    `calculated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`kasta_id`) REFERENCES `kastacategory`(`kasta_id`)
);

-- Buat tabel matches jika belum ada
CREATE TABLE IF NOT EXISTS `matches` (
    `match_id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id_1` INT DEFAULT NULL,
    `user_id_2` INT DEFAULT NULL,
    `match_score` DECIMAL(5,2) DEFAULT NULL,
    `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `is_best_match` TINYINT(1) DEFAULT 0,
    `viewed` TINYINT(1) DEFAULT 0,
    FOREIGN KEY (`user_id_1`) REFERENCES `users`(`user_id`),
    FOREIGN KEY (`user_id_2`) REFERENCES `users`(`user_id`)
);

-- Hapus data yang sudah ada (jika diperlukan untuk reset)
-- Perhatikan urutan TRUNCATE untuk mengatasi foreign key constraints
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `useranswers`;
TRUNCATE TABLE `userkastaresult`;
TRUNCATE TABLE `matches`;
TRUNCATE TABLE `answeroptions`;
TRUNCATE TABLE `questions`;
TRUNCATE TABLE `kastacategory`;
SET FOREIGN_KEY_CHECKS = 1;

-- Insert data kasta
INSERT INTO `kastacategory` (`kasta_id`, `kasta_name`, `description`) VALUES
(1, 'Brahmana', 'Kasta tertinggi - berkaitan dengan spiritual dan pendidikan'),
(2, 'Ksatria', 'Kasta yang berkaitan dengan kepemimpinan dan kekuasaan'),
(3, 'Waisya', 'Kasta yang berkaitan dengan perdagangan dan ekonomi'),
(4, 'Sudra', 'Kasta yang berkaitan dengan pekerja dan masyarakat umum');

-- Perbaikan nilai confidence_score dan match_score yang salah
-- Update nilai confidence_score menjadi 0 untuk user baru
UPDATE `userkastaresult` SET `confidence_score` = 0 
WHERE `confidence_score` = 60;

-- Update nilai match_score menjadi 0 untuk matches yang seharusnya 0
UPDATE `matches` m
JOIN `userkastaresult` ukr1 ON m.user_id_1 = ukr1.user_id
JOIN `userkastaresult` ukr2 ON m.user_id_2 = ukr2.user_id
SET m.match_score = 0
WHERE ukr1.confidence_score = 0 OR ukr2.confidence_score = 0;

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