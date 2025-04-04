-- Buat tabel sub_kasta untuk menyimpan data sub-kasta
CREATE TABLE IF NOT EXISTS `sub_kasta` (
    `sub_kasta_id` INT PRIMARY KEY,
    `kasta_id` INT NOT NULL,
    `level` INT NOT NULL,
    `sub_kasta_name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    FOREIGN KEY (`kasta_id`) REFERENCES `kastacategory`(`kasta_id`)
);

-- Hapus data sub-kasta jika sudah ada (untuk refresh)
TRUNCATE TABLE `sub_kasta`;

-- Insert data sub-kasta
INSERT INTO `sub_kasta` (`sub_kasta_id`, `kasta_id`, `level`, `sub_kasta_name`, `description`) VALUES
-- Brahmana (3 level)
(1, 1, 1, 'Brahmana Dasar', 'Memiliki kecenderungan spiritual dan pencarian pengetahuan, masih dalam tahap awal pengembangan.'),
(2, 1, 2, 'Brahmana Umum', 'Aktif menerapkan nilai-nilai spiritual dan pendidikan dalam kehidupan sehari-hari.'),
(3, 1, 3, 'Brahmana Utama', 'Mencapai tingkat tinggi dalam spiritualitas dan kebijaksanaan, menjadi panutan bagi orang lain.'),

-- Ksatria (3 level)
(4, 2, 1, 'Ksatria Dasar', 'Memiliki jiwa kepemimpinan dan keberanian yang mulai berkembang.'),
(5, 2, 2, 'Ksatria Umum', 'Menunjukkan kemampuan memimpin dan melindungi dalam berbagai situasi.'),
(6, 2, 3, 'Ksatria Utama', 'Pemimpin sejati dengan integritas tinggi dan kemampuan melindungi yang luar biasa.'),

-- Waisya (3 level)
(7, 3, 1, 'Waisya Dasar', 'Memiliki kecenderungan bisnis dan kemampuan mengelola sumber daya yang sedang berkembang.'),
(8, 3, 2, 'Waisya Umum', 'Terampil dalam perdagangan dan pengembangan bisnis yang berkelanjutan.'),
(9, 3, 3, 'Waisya Utama', 'Sangat sukses dalam bisnis dan perdagangan, mampu menciptakan kesejahteraan bagi banyak orang.'),

-- Sudra (3 level)
(10, 4, 1, 'Sudra Dasar', 'Memiliki keterampilan kerja dan loyalitas yang sedang berkembang.'),
(11, 4, 2, 'Sudra Umum', 'Pekerja terampil dan dapat diandalkan dalam berbagai situasi.'),
(12, 4, 3, 'Sudra Utama', 'Ahli dalam bidangnya dengan kesetiaan dan ketekunan yang luar biasa.');

-- Tambahkan kolom sub_kasta_id pada tabel userkastaresult
ALTER TABLE `userkastaresult` 
ADD COLUMN `sub_kasta_id` INT DEFAULT NULL AFTER `kasta_id`,
ADD FOREIGN KEY (`sub_kasta_id`) REFERENCES `sub_kasta`(`sub_kasta_id`); 