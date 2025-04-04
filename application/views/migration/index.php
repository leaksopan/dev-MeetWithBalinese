<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <h1>Database Migration</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5>Migrasi Database</h5>
        </div>
        <div class="card-body">
            <p>Halaman ini digunakan untuk menjalankan migrasi database. Migrasi database akan mengubah struktur database seperti menambah kolom, mengubah tipe data, dll.</p>
            <p>Silahkan pilih migrasi yang ingin dijalankan:</p>
            
            <div class="list-group">
                <a href="<?= base_url('migration/add_updated_at') ?>" class="list-group-item list-group-item-action">
                    <strong>add_updated_at_column.sql</strong>
                    <p class="mb-0 text-muted">Tambahkan kolom updated_at dan created_at ke tabel userkastaresult</p>
                </a>
            </div>
            
            <?php if (!empty($migrations)): ?>
                <h5 class="mt-4">Migrasi Lainnya:</h5>
                <div class="list-group">
                    <?php foreach ($migrations as $migration): ?>
                        <a href="<?= base_url('migration/run/' . $migration) ?>" class="list-group-item list-group-item-action">
                            <?= $migration ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="<?= base_url('matches/debug') ?>" class="btn btn-secondary">Kembali ke Debug</a>
        <a href="<?= base_url('matches') ?>" class="btn btn-primary">Kembali ke Matches</a>
    </div>
</div> 