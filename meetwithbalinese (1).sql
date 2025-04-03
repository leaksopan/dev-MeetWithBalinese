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

CREATE TABLE `answeroptions` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) DEFAULT NULL,
  `option_text` text NOT NULL,
  `kasta_indicator` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answeroptions`
--

INSERT INTO `answeroptions` (`option_id`, `question_id`, `option_text`, `kasta_indicator`, `weight`) VALUES
(1, 1, 'Lawar yang dipersiapkan dengan teliti', 1, 5),
(2, 1, 'Babi guling yang jadi pusat perhatian', 2, 5),
(3, 1, 'Sate lilit yang praktis tapi istimewa', 3, 5),
(4, 1, 'Tipat cantok yang sederhana tapi disukai semua orang', 4, 5),
(5, 2, 'Pendeta bijak penasihat raja', 1, 5),
(6, 2, 'Raja yang gagah berani', 2, 5),
(7, 2, 'Pedagang kaya yang dermawan', 3, 5),
(8, 2, 'Rakyat biasa yang setia', 4, 5),
(9, 3, 'Yang dipanggil untuk memimpin doa', 1, 4),
(10, 3, 'Yang sibuk koordinasi acara', 2, 4),
(11, 3, 'Yang bawa sumbangan paling spesial', 3, 4),
(12, 3, 'Yang rajin bantu-bantu semua orang', 4, 4),
(13, 4, 'Dipersilakan duduk di tempat spesial', 1, 3),
(14, 4, 'Di dekat tuan rumah', 2, 3),
(15, 4, 'Bebas pilih tempat tapi tetap sopan', 3, 3),
(16, 4, 'Dimana aja bisa, santai aja!', 4, 3),
(17, 5, 'Ida Bagus/Ida Ayu', 1, 5),
(18, 5, 'Anak Agung/Cokorda', 2, 5),
(19, 5, 'I Gusti', 3, 5),
(20, 5, 'I/Ni (diikuti nama Bali populer)', 4, 5),
(21, 6, 'Jasa konsultasi spiritual', 1, 4),
(22, 6, 'Kerajinan pusaka Bali', 2, 4),
(23, 6, 'Bisnis kuliner/perhiasan', 3, 4),
(24, 6, 'Warung makan sederhana dengan rasa juara', 4, 4),
(25, 7, 'Kasih mantra perlindungan', 1, 3),
(26, 7, 'Memerintahkan dengan tegas \"Pergi kamu!\"', 2, 3),
(27, 7, 'Negosiasi biaya untuk tidak diganggu', 3, 3),
(28, 7, 'Lari secepat kilat sambil teriak \"Tolong!\"', 4, 3),
(29, 8, 'Penasihat spiritual alien', 1, 3),
(30, 8, 'Kapten pasukan pertahanan bumi', 2, 3),
(31, 8, 'Menteri perdagangan antar galaksi', 3, 3),
(32, 8, 'Tour guide ramah untuk alien-alien', 4, 3),
(33, 9, 'Saya menjadi penengah dan memberikan nasihat untuk menyelesaikan masalah', 1, 3),
(34, 9, 'Saya mengambil peran memimpin dan mengatur situasi', 2, 3),
(35, 9, 'Saya mencari cara untuk memperoleh keuntungan dari situasi tersebut', 3, 3),
(36, 9, 'Saya fokus pada pekerjaan saya dan tidak terlibat dalam masalah orang lain', 4, 3),
(37, 10, 'Membaca buku, belajar, atau bermeditasi', 1, 2),
(38, 10, 'Berolahraga atau mengikuti kegiatan sosial', 2, 2),
(39, 10, 'Mencari peluang bisnis atau investasi', 3, 2),
(40, 10, 'Membuat kerajinan atau melakukan hobi kreatif', 4, 2),
(41, 11, 'Mencari kebijaksanaan dan pencerahkan spiritual', 1, 4),
(42, 11, 'Memimpin dan melindungi orang lain', 2, 4),
(43, 11, 'Mencapai kesuksesan materi dan finansial', 3, 4),
(44, 11, 'Hidup sederhana dan bahagia dengan keterampilan saya', 4, 4),
(45, 12, 'Sangat penting dan menjadi fokus utama hidup saya', 1, 3),
(46, 12, 'Penting sebagai panduan moral dalam kepemimpinan', 2, 3),
(47, 12, 'Saya menghormatinya tetapi fokus pada hal praktis', 3, 3),
(48, 12, 'Saya lebih fokus pada kehidupan sehari-hari', 4, 3),
(49, 13, 'Kebijaksanaan dan kemampuan intelektual', 1, 2),
(50, 13, 'Keberanian dan ketegasan', 2, 2),
(51, 13, 'Keterampilan bisnis dan negosiasi', 3, 2),
(52, 13, 'Keterampilan teknis dan kreativitas', 4, 2),
(53, 14, 'Kekayaan spiritual lebih penting daripada materi', 1, 3),
(54, 14, 'Kekayaan adalah alat untuk melayani dan memimpin masyarakat', 2, 3),
(55, 14, 'Kekayaan adalah tujuan dan ukuran kesuksesan', 3, 3),
(56, 14, 'Kekayaan bukanlah prioritas utama selama kebutuhan terpenuhi', 4, 3),
(57, 15, 'Kedamaian dan harmoni spiritual', 1, 2),
(58, 15, 'Loyalitas dan keadilan', 2, 2),
(59, 15, 'Hubungan yang saling menguntungkan', 3, 2),
(60, 15, 'Kesetiaan dan kerja sama praktis', 4, 2),
(61, 16, 'Guru, peneliti, atau pemimpin spiritual', 1, 3),
(62, 16, 'Pemimpin, politisi, atau tentara', 2, 3),
(63, 16, 'Pengusaha, pedagang, atau bankir', 3, 3),
(64, 16, 'Seniman, pengrajin, atau pekerja terampil', 4, 3),
(65, 17, 'Melalui meditasi dan refleksi mendalam', 1, 2),
(66, 17, 'Dengan tegas berdasarkan prinsip dan nilai', 2, 2),
(67, 17, 'Dengan mempertimbangkan untung rugi', 3, 2),
(68, 17, 'Secara praktis berdasarkan pengalaman', 4, 2),
(69, 18, 'Memimpin doa dan ritual keagamaan', 1, 3),
(70, 18, 'Mengorganisir dan memimpin kegiatan', 2, 3),
(71, 18, 'Menyumbang dana dan sumber daya', 3, 3),
(72, 18, 'Membantu persiapan dan pelaksanaan praktis', 4, 3),
(73, 19, 'Sangat menjunjung tinggi dan menafsirkan tradisi', 1, 2),
(74, 19, 'Menjaga dan menegakkan tradisi', 2, 2),
(75, 19, 'Menghormati tradisi tetapi adaptif dengan perubahan', 3, 2),
(76, 19, 'Mengikuti tradisi sebagai bagian dari kehidupan sehari-hari', 4, 2),
(77, 20, 'Mencari kebijaksanaan melalui meditasi dan refleksi', 1, 2),
(78, 20, 'Menghadapinya dengan keberanian dan ketegasan', 2, 2),
(79, 20, 'Mencari peluang dan keuntungan dari tantangan', 3, 2),
(80, 20, 'Beradaptasi dan menemukan solusi praktis', 4, 2),
(81, 21, 'Pendidikan spiritual dan filosofis sangat penting', 1, 2),
(82, 21, 'Pendidikan tentang kepemimpinan dan strategi', 2, 2),
(83, 21, 'Pendidikan praktis untuk kesuksesan bisnis', 3, 2),
(84, 21, 'Pendidikan keterampilan dan keahlian praktis', 4, 2),
(85, 22, 'Memberikan bimbingan spiritual dan moral', 1, 3),
(86, 22, 'Memimpin dan melindungi komunitas', 2, 3),
(87, 22, 'Menciptakan lapangan kerja dan kesejahteraan ekonomi', 3, 3),
(88, 22, 'Menyediakan layanan dan keterampilan praktis', 4, 3),
(89, 23, 'Diskusi filosofis dan bertukar pengetahuan', 1, 2),
(90, 23, 'Kegiatan kompetitif dan memimpin kelompok', 2, 2),
(91, 23, 'Networking dan membangun koneksi bisnis', 3, 2),
(92, 23, 'Aktivitas praktis dan saling membantu', 4, 2),
(93, 24, 'Pencapaian spiritual dan intelektual', 1, 2),
(94, 24, 'Kemampuan memimpin dan menginspirasi orang lain', 2, 2),
(95, 24, 'Kesuksesan finansial dan status sosial', 3, 2),
(96, 24, 'Keterampilan dan kreasi yang saya hasilkan', 4, 2),
(97, 25, 'Sebagai orang yang bijaksana dan mencerahkan', 1, 3),
(98, 25, 'Sebagai pemimpin yang adil dan pelindung', 2, 3),
(99, 25, 'Sebagai orang sukses dan berprestasi', 3, 3),
(100, 25, 'Sebagai pekerja keras dan terampil', 4, 3),
(101, 1, 'Tetap tenang, duduk di depan dan pura-pura sudah dari tadi di sana', 1, 3),
(102, 1, 'Masuk dengan gagah, memberi salam kepada semua orang dan langsung ambil alih acara', 2, 3),
(103, 1, 'Telepon teman di dalam biar siapkan kursi VIP untukmu', 3, 3),
(104, 1, 'Masuk lewat belakang dan bantu nyiapin makanan biar dapet jatah duluan', 4, 3),
(105, 2, 'Meditasi sambil memberi pencerahan pada sopir tentang filosofi \"jalan yang tersesat\"', 1, 2),
(106, 2, 'Ambil alih kemudi dan tunjukkan jalan yang benar dengan penuh wibawa', 2, 2),
(107, 2, 'Nego harga karena perjalanan jadi lebih jauh, sambil telpon rekan bisnis', 3, 2),
(108, 2, 'Santai saja, ngobrol dengan sopir dan berbagi cerita lucu', 4, 2),
(109, 3, '\"Makanan adalah cerminan jiwa yang suci, cobalah Lawar dengan penuh penghayatan\"', 1, 2),
(110, 3, '\"Babi Guling dari warung langganan, saya bisa antar kamu ke sana\"', 2, 2),
(111, 3, '\"Bebek Bengil, tapi hati-hati harganya bisa bikin bengong\"', 3, 2),
(112, 3, '\"Nasi campur Gang Lusuh, murah tapi bikin nagih!\"', 4, 2),
(113, 4, 'Membaca pikiran untuk mencerahkan orang-orang yang galau', 1, 3),
(114, 4, 'Super kekuatan untuk memberantas kejahatan dan melindungi tradisi', 2, 3),
(115, 4, 'Mengubah sampah jadi emas, biar Bali makin kaya', 3, 3),
(116, 4, 'Bisa menggandakan diri untuk kerja sambil liburan sekaligus', 4, 3),
(117, 5, 'Retreat meditasi di puncak gunung selama sebulan tanpa internet', 1, 2),
(118, 5, 'Tour mengunjungi istana-istana kuno dan belajar sejarah kepemimpinan', 2, 2),
(119, 5, 'Yacht party di Nusa Penida dengan jaringan bisnis internasional', 3, 2),
(120, 5, 'Festival kuliner sambil hunting spot foto Instagramable', 4, 2),
(121, 6, 'Memberikan doa dan puisi spiritual sebagai kado tak ternilai', 1, 2),
(122, 6, 'Pidato mendadak yang mengesankan semua tamu dan selamatkan muka', 2, 2),
(123, 6, 'Transfer uang digital di tempat, sekalian promosi bisnis fintech-mu', 3, 2),
(124, 6, 'Bantu-bantu di dapur dan antar makanan ke tamu agar tidak ketahuan', 4, 2),
(125, 7, 'Duduk bersila di pinggir jalan, meditasi sambil nunggu pencerahan datang', 1, 2),
(126, 7, 'Berteriak dengan berwibawa agar ada yang berhenti membantu', 2, 2),
(127, 7, 'Telpon supir pribadi atau ojek online premium, sambil balas email', 3, 2),
(128, 7, 'Tambal sendiri pakai kit darurat yang selalu kamu bawa', 4, 2),
(129, 8, 'Air putih dan buah segar untuk menjaga kesucian pikiran', 1, 2),
(130, 8, 'Nasi goreng komplit dengan telur mata sapi yang sempurna di atas', 2, 2),
(131, 8, 'Kopi mahal dan roti impor sambil cek saham', 3, 2),
(132, 8, 'Bubur ayam atau jajananan pasar apa aja yang penting kenyang', 4, 2),
(133, 9, 'Burung hantu yang bijaksana dan penuh kesabaran', 1, 3),
(134, 9, 'Singa yang gagah sebagai raja hutan Bali (walaupun tidak ada singa di Bali)', 2, 3),
(135, 9, 'Monyet cerdik yang bisa mengumpulkan banyak pisang', 3, 3),
(136, 9, 'Anjing Bali yang setia dan dicintai semua orang', 4, 3),
(137, 10, '\"Pakaian hanya pembungkus jiwa, yang penting hatimu indah\"', 1, 2),
(138, 10, '\"Terus terang saja, ini kurang cocok untukmu, coba yang lain\"', 2, 2),
(139, 10, '\"Berapa harganya? Aku bisa kasih tahu tempat yang lebih murah dengan kualitas lebih bagus\"', 3, 2),
(140, 10, '\"Wah keren! Aku juga mau beli ah, di mana belinya?\"', 4, 2),
(141, 11, 'Berdoa agar jiwa sampah tersebut dibersihkan oleh alam', 1, 3),
(142, 11, 'Memungutnya sambil ceramah pada orang-orang tentang kebersihan', 2, 3),
(143, 11, 'Lapor ke aplikasi smart city dan posting di sosmed tentang masalah kebersihan', 3, 3),
(144, 11, 'Ambil dan buang ke tempat sampah terdekat tanpa banyak komentar', 4, 3),
(145, 12, 'Sumbang untuk pembangunan pura dan upacara keagamaan', 1, 3),
(146, 12, 'Buat acara makan-makan untuk warga kampung', 2, 3),
(147, 12, 'Investasi di crypto atau saham potensial', 3, 3),
(148, 12, 'Belanja kebutuhan dan sisanya ditabung', 4, 3),
(149, 13, 'Film dokumenter tentang spiritualitas dan makna hidup', 1, 2),
(150, 13, 'Film action dan perang yang penuh dengan kepahlawanan', 2, 2),
(151, 13, 'Film tentang kesuksesan pengusaha besar seperti \"Wolf of Wall Street\"', 3, 2),
(152, 13, 'Komedi romantis atau film Indonesia yang relate dengan kehidupan sehari-hari', 4, 2),
(153, 14, 'Lelucon cerdas bermuatan filosofis yang perlu direnungkan', 1, 2),
(154, 14, 'Cerita heroik yang berakhir dengan kemenangan', 2, 2),
(155, 14, 'Stand up comedy tentang absurditas kehidupan kantor dan bisnis', 3, 2),
(156, 14, 'Video lucu orang jatuh atau hewan melakukan hal konyol', 4, 2),
(157, 15, '\"Maaf, aku sedang mendalami teks kuno yang sangat penting\"', 1, 2),
(158, 15, '\"Ada rapat mendadak dengan tokoh masyarakat\"', 2, 2),
(159, 15, '\"Harus mengecek investasi dulu, saham lagi turun nih\"', 3, 2),
(160, 15, '\"Motorku masih di bengkel, besok aja ya?\"', 4, 2),
(161, 16, 'Lagu mantra suci untuk membersihkan aura', 1, 2),
(162, 16, 'Soundtrack epik seperti \"We Are The Champions\"', 2, 2),
(163, 16, '\"Money Money Money\" dari ABBA', 3, 2),
(164, 16, 'Lagu dangdut atau pop Indonesia yang lagi viral', 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `kastacategory`
--

CREATE TABLE `kastacategory` (
  `kasta_id` int(11) NOT NULL,
  `kasta_name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kastacategory`
--

INSERT INTO `kastacategory` (`kasta_id`, `kasta_name`, `description`) VALUES
(1, 'Brahmana', 'Kasta tertinggi - berkaitan dengan spiritual dan pendidikan'),
(2, 'Ksatria', 'Kasta yang berkaitan dengan kepemimpinan dan kekuasaan'),
(3, 'Waisya', 'Kasta yang berkaitan dengan perdagangan dan ekonomi'),
(4, 'Sudra', 'Kasta yang berkaitan dengan pekerja dan masyarakat umum');

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `match_id` int(11) NOT NULL,
  `user_id_1` int(11) DEFAULT NULL,
  `user_id_2` int(11) DEFAULT NULL,
  `match_score` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_best_match` tinyint(1) DEFAULT 0,
  `viewed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `question_text`, `is_active`) VALUES
(1, 'Kalau kamu jadi makanan Bali, kamu lebih merasa seperti:', 1),
(2, 'Kalau hidup di dunia pewayangan, kamu bakal jadi tokoh mana nih?', 1),
(3, 'Pas pergi ke pura, kamu biasanya:', 1),
(4, 'Kalau kamu diundang makan di rumah teman, posisi duduk kamu biasanya:', 1),
(5, 'Pilih nama anak yang paling kamu suka:', 1),
(6, 'Kalau buka warung, kamu jualan apa?', 1),
(7, 'Pas kamu ketemu Leak tengah malam, reaksi pertamamu:', 1),
(8, 'Alien mendarat di Bali dan memintamu jadi duta bumi. Jabatan yang kamu minta:', 1),
(9, 'Bagaimana perilaku Anda ketika berada dalam situasi konflik sosial?', 1),
(10, 'Apa yang Anda lakukan ketika memiliki waktu luang?', 1),
(11, 'Apa motivasi utama Anda dalam hidup?', 1),
(12, 'Bagaimana Anda memandang kehidupan spiritual?', 1),
(13, 'Apa kekuatan terbesar Anda?', 1),
(14, 'Bagaimana pendapat Anda tentang kekayaan?', 1),
(15, 'Apa yang paling Anda hargai dalam hubungan?', 1),
(16, 'Karir ideal apa yang Anda inginkan?', 1),
(17, 'Bagaimana Anda biasanya membuat keputusan penting?', 1),
(18, 'Apa peran Anda dalam upacara adat?', 1),
(19, 'Bagaimana Anda menyikapi tradisi?', 1),
(20, 'Apa yang Anda lakukan ketika menghadapi tantangan?', 1),
(21, 'Bagaimana pendekatan Anda terhadap pendidikan?', 1),
(22, 'Bagaimana Anda berkontribusi pada masyarakat?', 1),
(23, 'Apa yang paling Anda nikmati saat berkumpul dengan teman?', 1),
(24, 'Apa aspek paling memuaskan dalam hidup Anda?', 1),
(25, 'Bagaimana Anda ingin diingat?', 1),
(26, 'Jika acara adat dimulai dan kamu datang terlambat, apa yang terjadi?', 1),
(27, 'Saat kamu naik taksi online dan sopirnya nyasar, apa yang kamu lakukan?', 1),
(28, 'Kalau orang asing tanya \"masakan Bali yang paling enak apa?\", kamu bakal jawab:', 1),
(29, 'Kalau kamu jadi superhero Bali, kekuatan apa yang kamu pilih?', 1),
(30, 'Liburan impianmu adalah:', 1),
(31, 'Kamu diundang ke pesta pernikahan tapi lupa bawa kado, apa yang kamu lakukan?', 1),
(32, 'Kalau ban motormu kempes di jalan sepi, gimana?', 1),
(33, 'Menu sarapan favoritmu adalah:', 1),
(34, 'Kalau kamu bisa pilih jadi hewan apa yang ada di Bali, kamu pilih jadi:', 1),
(35, 'Saat temanmu minta pendapat tentang pakaian barunya yang jelek, kamu bilang:', 1),
(36, 'Ketika sampah di jalan, kamu akan:', 1),
(37, 'Kalau kamu dapat hadiah 5 juta rupiah, kamu akan:', 1),
(38, 'Film apa yang paling kamu suka?', 1),
(39, 'Apa yang bikin kamu tertawa paling keras?', 1),
(40, 'Saat teman mengajakmu pergi tapi kamu malas, alasan yang kamu berikan:', 1),
(41, 'Lagu apa yang sering kamu nyanyikan di kamar mandi?', 1);

-- --------------------------------------------------------

--
-- Table structure for table `useranswers`
--

CREATE TABLE `useranswers` (
  `user_answer_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userkastaresult`
--

CREATE TABLE `userkastaresult` (
  `result_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `kasta_id` int(11) DEFAULT NULL,
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `instagram`, `created_at`, `username`, `age`, `gender`) VALUES
(1, 'valorantdiva1@gmail.com', '$2y$10$/.cJLfzxIzjRSdMLXMthGex4nFjBBJdyxNIjVpn8ZWvDT05qCbTlG', 'asd', '2025-04-03 12:25:47', 'grenery', 19, 'Male'),
(2, 'nolsatubeat@gmail.com', '$2y$10$.3TjpFPjcegQrVTwqXa/8OtxvN04LoWzDNk3WBZammGz/nLUHdr02', 'grace', '2025-04-03 12:38:39', 'grace', 21, 'Female'),
(3, 'admin@academys.com', '$2y$10$76KldbKFIQHQ7PoU4n0N4.YaiLbZk9mtx7SsYj03OnOdZPYWL6Q6y', 'gekayu', '2025-04-03 12:39:36', 'gekayu', 23, 'Female'),
(4, 'cipta5772@gmail.com', '$2y$10$UQ.4bfjK2IbUdekgcb3/POwIdt3KQ/zWF30A1Dg0gAST1soyD/Awa', 'dira', '2025-04-03 12:40:57', 'dira', 21, 'Female');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answeroptions`
--
ALTER TABLE `answeroptions`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `kasta_indicator` (`kasta_indicator`);

--
-- Indexes for table `kastacategory`
--
ALTER TABLE `kastacategory`
  ADD PRIMARY KEY (`kasta_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `user_id_1` (`user_id_1`),
  ADD KEY `user_id_2` (`user_id_2`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `useranswers`
--
ALTER TABLE `useranswers`
  ADD PRIMARY KEY (`user_answer_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `selected_option_id` (`selected_option_id`);

--
-- Indexes for table `userkastaresult`
--
ALTER TABLE `userkastaresult`
  ADD PRIMARY KEY (`result_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `kasta_id` (`kasta_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answeroptions`
--
ALTER TABLE `answeroptions`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `useranswers`
--
ALTER TABLE `useranswers`
  MODIFY `user_answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userkastaresult`
--
ALTER TABLE `userkastaresult`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
