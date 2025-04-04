-- Script untuk menambahkan kolom timestamp di tabel userkastaresult
-- Jalankan script ini untuk update struktur database

-- Cek apakah kolom updated_at sudah ada, jika belum tambahkan
ALTER TABLE `userkastaresult` 
ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Waktu terakhir update hasil kasta';

-- Cek apakah kolom created_at sudah ada, jika belum tambahkan
ALTER TABLE `userkastaresult` 
ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu pembuatan hasil kasta';

-- Update nilai kolom yang sudah ada untuk isi nilai updated_at dengan calculated_at
UPDATE `userkastaresult` SET `updated_at` = `calculated_at` WHERE `updated_at` IS NULL;

-- Update nilai kolom yang sudah ada untuk isi nilai created_at dengan calculated_at jika NULL
UPDATE `userkastaresult` SET `created_at` = `calculated_at` WHERE `created_at` IS NULL;

-- Tambahkan kolom linear_position jika belum ada
ALTER TABLE `userkastaresult` 
ADD COLUMN IF NOT EXISTS `linear_position` INT NULL DEFAULT NULL COMMENT 'Posisi linear dalam skala 1-12';

-- Commit perubahan
COMMIT; 