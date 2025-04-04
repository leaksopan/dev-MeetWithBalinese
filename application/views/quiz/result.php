<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center my-5">
    <div class="col-lg-10 col-md-11">
        <!-- Header ilustrasi -->
        <div class="text-center mb-4">
            <img src="<?= base_url('assets/images/We Are Women - Lettering.png') ?>" alt="Match Result" class="result-header-image">
        </div>
    
        <div class="row">
            <!-- Kolom utama -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header border-0 text-center py-4">
                        <h3 class="mb-0">Hasil Quiz Kasta Bali</h3>
                        <div class="balinese-divider my-3"></div>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="result-icon mb-3">
                            <span class="kasta-symbol">☸</span>
                        </div>
                        
                        <h2 class="fw-bold mb-3 kasta-title"><?= ucfirst($kasta->kasta_name) ?></h2>
                        
                        <?php if(isset($linear_position_name)): ?>
                        <h5 class="mb-3">Posisi: <span class="position-name"><?= $linear_position_name ?></span></h5>
                        
                        <div class="position-scale mb-4">
                            <?php 
                            // Buat skala posisi 1-12
                            for ($i = 1; $i <= 12; $i++): 
                                $active = ($i == $linear_position) ? 'active' : '';
                            ?>
                            <div class="position-point <?= $active ?>" data-position="<?= $i ?>"></div>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <div class="confidence-meter">
                                <div class="confidence-bar" style="width: <?= $confidence_score ?>%"></div>
                            </div>
                            <span class="confidence-score">Tingkat Kecocokan: <?= round($confidence_score) ?>%</span>
                        </div>
                        
                        <div class="description-card mb-4">
                            <h4>Tentang Kasta <?= ucfirst($kasta->kasta_name) ?></h4>
                            <p><?= $kasta->description ?></p>
                            <?php if(isset($linear_position_name)): ?>
                            <div class="position-info">
                                <h5>Posisi Spesifik: <?= $linear_position_name ?></h5>
                                <p class="mb-0">Posisi ini menentukan kecocokan Anda dengan orang lain. Setiap perbedaan 1 tingkat akan mengurangi kecocokan sebesar 8.33%.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="note mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Hasil ini berdasarkan jawaban Anda dan dapat membantu mencari pasangan yang serasi.
                        </div>
                        
                        <div class="d-grid gap-3 d-md-flex justify-content-center">
                            <a href="<?= base_url('matches') ?>" class="btn btn-primary btn-lg">
                                <i class="fas fa-heart me-2"></i> Lihat Matches
                            </a>
                            <a href="<?= base_url('quiz') ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt me-2"></i> Ambil Quiz Lagi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Kolom samping dengan ilustrasi -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sidecard-container">
                    <div class="illustration-card mb-3">
                        <img src="<?= base_url('assets/images/Big Shoes - Torso.png') ?>" alt="Karakter" class="side-character">
                    </div>
                    
                    <div class="illustration-card motivation-card">
                        <h4>Tips Mencari Pasangan</h4>
                        <ul class="tips-list">
                            <li>Jadikan hasil quiz sebagai panduan, bukan patokan kaku</li>
                            <li>Tetap terbuka pada perbedaan kasta yang tidak terlalu jauh</li>
                            <li>Nilai kepribadian dan kesamaan minat juga penting</li>
                            <li>Perbedaan kasta bisa jadi tantangan menarik dalam hubungan</li>
                        </ul>
                        <div class="text-center mt-3">
                            <img src="<?= base_url('assets/images/Fuzzy Friends - Nature Life.png') ?>" alt="Fuzzy Friends" class="fuzzy-friends">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Karakteristik Kasta -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header border-0 py-3">
                <h4 class="mb-0">Karakteristik <?= ucfirst($kasta->kasta_name) ?></h4>
            </div>
            <div class="card-body p-4">
                <?php
                    $karakteristik = [
                        'Brahmana' => [
                            'Spiritual dan religius',
                            'Tertarik pada pendidikan dan pengetahuan',
                            'Bijaksana dan mampu memberi nasihat',
                            'Tenang dan sabar dalam menghadapi masalah',
                            'Menghargai kebersihan dan kemurnian'
                        ],
                        'Ksatria' => [
                            'Berani dan tegas',
                            'Memiliki jiwa kepemimpinan',
                            'Melindungi orang lain',
                            'Memiliki rasa tanggung jawab tinggi',
                            'Tidak takut menghadapi tantangan'
                        ],
                        'Waisya' => [
                            'Berpikiran bisnis dan praktis',
                            'Kreatif dalam mencari peluang',
                            'Pandai mengelola sumber daya',
                            'Senang berbagi dan bermurah hati',
                            'Pekerja keras dengan visi jangka panjang'
                        ],
                        'Sudra' => [
                            'Sederhana dan tidak rumit',
                            'Pekerja keras dan tekun',
                            'Loyal dan setia',
                            'Suka menolong dan mendukung',
                            'Adaptif terhadap berbagai situasi'
                        ]
                    ];
                    
                    $current_karakteristik = $karakteristik[$kasta->kasta_name] ?? [];
                ?>
                
                <div class="row">
                    <div class="col-md-8">
                        <?php if (!empty($current_karakteristik)): ?>
                            <ul class="trait-list">
                                <?php foreach ($current_karakteristik as $trait): ?>
                                    <li class="trait-item"><?= $trait ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted mb-0">Informasi karakteristik belum tersedia.</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <img src="<?= base_url('assets/images/Fuzzy Friends - Looking for Love (1).png') ?>" alt="Love" class="characteristic-image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.kasta-title {
    color: #8C4A1E;
    letter-spacing: 1px;
    font-family: 'Philosopher', serif;
}

.kasta-symbol {
    display: inline-block;
    font-size: 48px;
    color: #E9B44C;
    background-color: #8C4A1E;
    width: 100px;
    height: 100px;
    line-height: 100px;
    border-radius: 50%;
    margin-bottom: 15px;
}

.position-name {
    color: #8C4A1E;
    font-weight: 600;
}

.position-scale {
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 30px;
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    position: relative;
}

.position-scale:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #BCA888;
    z-index: 0;
}

.position-point {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background-color: #FFF8E5;
    border: 2px solid #BCA888;
    z-index: 1;
    position: relative;
}

.position-point.active {
    width: 24px;
    height: 24px;
    background-color: #E9B44C;
    border: 2px solid #8C4A1E;
}

.confidence-meter {
    height: 20px;
    background-color: #FFF8E5;
    border: 2px solid #BCA888;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 8px;
}

.confidence-bar {
    height: 100%;
    background-color: #8C4A1E;
    border-radius: 8px;
}

.confidence-score {
    font-weight: 600;
    color: #8C4A1E;
}

.description-card {
    background-color: #FFFAF0;
    border: 2px solid #BCA888;
    border-radius: 8px;
    padding: 20px;
    margin-top: 25px;
}

.description-card h4 {
    color: #8C4A1E;
    margin-bottom: 15px;
    font-family: 'Philosopher', serif;
}

.position-info {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px dashed #BCA888;
}

.position-info h5 {
    color: #8C4A1E;
}

.note {
    color: #45322E;
    font-style: italic;
}

.trait-list {
    list-style: none;
    padding-left: 0;
}

.trait-item {
    position: relative;
    padding: 10px 15px 10px 35px;
    margin-bottom: 10px;
    background-color: #FFFAF0;
    border: 1px solid #BCA888;
    border-radius: 4px;
}

.trait-item:before {
    content: '✧';
    position: absolute;
    left: 12px;
    color: #E9B44C;
    font-size: 18px;
}

.btn-primary {
    background-color: #8C4A1E;
    border-color: #8C4A1E;
}

.btn-primary:hover {
    background-color: #6A3816;
    border-color: #6A3816;
}

.btn-outline-secondary {
    color: #8C4A1E;
    border-color: #8C4A1E;
}

.btn-outline-secondary:hover {
    background-color: #8C4A1E;
    color: #FFF8E5;
}

/* Ilustrasi Styles */
.result-header-image {
    max-width: 300px;
    margin-bottom: 25px;
}

.sidecard-container {
    position: sticky;
    top: 30px;
}

.illustration-card {
    background-color: #FFFAF0;
    border: 2px solid #BCA888;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(140, 74, 30, 0.1);
}

.side-character {
    max-width: 100%;
    display: block;
}

.motivation-card {
    padding: 20px;
}

.motivation-card h4 {
    color: #8C4A1E;
    font-family: 'Philosopher', serif;
    margin-bottom: 15px;
    text-align: center;
}

.tips-list {
    padding-left: 20px;
}

.tips-list li {
    margin-bottom: 10px;
    position: relative;
    color: #45322E;
}

.fuzzy-friends {
    max-width: 150px;
    margin-top: 15px;
}

.characteristic-image {
    max-width: 100%;
    max-height: 200px;
}
</style> 