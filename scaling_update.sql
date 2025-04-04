-- Script untuk menambahkan kolom scaling di tabel matches
-- Jalankan script ini untuk update struktur database

-- Cek apakah kolom scaling_factor sudah ada, jika belum tambahkan
ALTER TABLE `matches` 
ADD COLUMN IF NOT EXISTS `scaling_factor` FLOAT DEFAULT NULL COMMENT 'Faktor perkalian untuk match score berdasarkan kasta';

-- Cek apakah kolom scaling_description sudah ada, jika belum tambahkan
ALTER TABLE `matches` 
ADD COLUMN IF NOT EXISTS `scaling_description` TEXT DEFAULT NULL COMMENT 'Deskripsi faktor scaling dalam bentuk teks';

-- Update nilai di kolom yang sudah ada (optional, hanya jika ingin mengisi data lama)
-- UPDATE `matches` SET `scaling_factor` = 1.0 WHERE `scaling_factor` IS NULL;

-- Buat trigger untuk otomatis memperbarui tabel matches saat ada perubahan di tabel userkastaresult
DELIMITER //

-- Hapus trigger jika sudah ada
DROP TRIGGER IF EXISTS after_update_kasta //
DROP TRIGGER IF EXISTS after_insert_kasta //

-- Trigger untuk update otomatis saat kasta user diperbarui
CREATE TRIGGER after_update_kasta
AFTER UPDATE ON userkastaresult
FOR EACH ROW
BEGIN
    -- Deklarasi variabel
    DECLARE user_gender VARCHAR(20);
    
    -- Ambil gender user
    SELECT gender INTO user_gender FROM users WHERE user_id = NEW.user_id;
    
    -- Perbarui semua match yang terkait dengan user ini
    UPDATE matches
    SET scaling_factor = 1.0, -- Nilai default, akan dihitung ulang oleh aplikasi nanti
        scaling_description = CONCAT('Kasta user diperbarui pada: ', NOW())
    WHERE user_id_1 = NEW.user_id OR user_id_2 = NEW.user_id;
END //

-- Trigger untuk otomatis saat kasta user baru ditambahkan
CREATE TRIGGER after_insert_kasta
AFTER INSERT ON userkastaresult
FOR EACH ROW
BEGIN
    -- Deklarasi variabel
    DECLARE user_gender VARCHAR(20);
    
    -- Ambil gender user
    SELECT gender INTO user_gender FROM users WHERE user_id = NEW.user_id;
    
    -- Perbarui semua match yang terkait dengan user ini (jika ada)
    UPDATE matches
    SET scaling_factor = 1.0, -- Nilai default, akan dihitung ulang oleh aplikasi nanti
        scaling_description = CONCAT('Kasta user ditambahkan pada: ', NOW())
    WHERE user_id_1 = NEW.user_id OR user_id_2 = NEW.user_id;
END //

DELIMITER ;

-- Commit perubahan
COMMIT;

-- Tampilkan struktur tabel setelah perubahan
DESCRIBE `matches`; 