<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0">Hasil Quiz Anda</h4>
            </div>
            <div class="card-body p-4 text-center">
                <div class="display-1 text-primary mb-3">
                    <i class="fas fa-trophy"></i>
                </div>
                
                <h3 class="fw-bold mb-3">Kasta Anda: <?= ucfirst($kasta->kasta_name) ?></h3>
                
                <div class="mb-4">
                    <span class="badge bg-primary p-2">Tingkat Kecocokan: <?= round($confidence_score) ?>%</span>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>Tentang Kasta <?= ucfirst($kasta->kasta_name) ?></h5>
                        <p><?= $kasta->description ?></p>
                    </div>
                </div>
                
                <div class="alert alert-info mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Hasil ini hanya untuk kesenangan dan tidak dimaksudkan sebagai penilaian sosial yang serius.
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="<?= base_url('matches') ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-heart me-2"></i> Lihat Matches
                    </a>
                    <a href="<?= base_url('quiz') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-2"></i> Ambil Quiz Lagi
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sesuai dengan Kasta -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Karakteristik <?= ucfirst($kasta->kasta_name) ?></h5>
            </div>
            <div class="card-body">
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
                
                <?php if (!empty($current_karakteristik)): ?>
                    <ul class="mb-0">
                        <?php foreach ($current_karakteristik as $trait): ?>
                            <li class="mb-2"><?= $trait ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">Informasi karakteristik belum tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 