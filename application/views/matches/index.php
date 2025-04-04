<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Temukan Kecocokan Anda</h3>
            <a href="<?= base_url('quiz') ?>" class="btn btn-outline-primary">
                <i class="fas fa-sync-alt me-2"></i> Ambil Quiz Lagi
            </a>
        </div>
        <p class="text-muted">Berdasarkan jawaban dari quiz yang telah Anda isi, kami menemukan beberapa orang yang cocok dengan kepribadian Anda.</p>
        <hr>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Kecocokan Berdasarkan Kepribadian Bali <strong>(V1.0 hanya untuk orang bali asli)</strong></h5>
        </div>
        <div class="card-body">
           
        </div>
    </div>
    
    <?php if (empty($matches)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Belum ada kecocokan yang ditemukan untuk Anda. Silakan mencoba kembali quiz atau menunggu pengguna lain yang melakukan quiz.
            </div>
            <div class="text-center my-4">
                <a href="<?= base_url('quiz') ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-sync-alt me-2"></i> Ambil Quiz Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($matches as $match): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 <?= isset($match->is_best_match) && $match->is_best_match ? 'border-warning' : '' ?>">
                    <?php if (isset($match->is_best_match) && $match->is_best_match): ?>
                        <div class="position-absolute top-0 start-0 bg-warning text-dark px-3 py-1 m-2 rounded-pill">
                            <i class="fas fa-crown me-1"></i> Best Match
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <?php
                                // Tentukan avatar berdasarkan gender dengan case insensitive
                                $gender_lower = strtolower($match->gender);
                                $gender = ($gender_lower == 'male') ? 'male' : 'female';
                                
                                // Array avatar untuk masing-masing gender
                                $male_avatars = [
                                    'assets/images/avatar/male/Città - Avatar.png',
                                    'assets/images/avatar/male/Città - Avatar (1).png',
                                    'assets/images/avatar/male/Friendly Ones - Avatar.png',
                                    'assets/images/avatar/male/Friendly Ones - Avatar and Backdrop.png'
                                ];
                                
                                $female_avatars = [
                                    'assets/images/avatar/female/Allura - Avatar.png',
                                    'assets/images/avatar/female/Allura - Avatar (1).png',
                                    'assets/images/avatar/female/Allura - Avatar (2).png',
                                    'assets/images/avatar/female/Allura - Avatar (3).png',
                                    'assets/images/avatar/female/Città - Avatar.png',
                                    'assets/images/avatar/female/Friendly Ones - Avatar.png',
                                    'assets/images/avatar/female/Friendly Ones - Avatar (1).png',
                                    'assets/images/avatar/female/Friendly Ones - Avatar (2).png'
                                ];
                                
                                // Pilih avatar secara acak berdasarkan gender
                                if ($gender == 'male') {
                                    $avatar_path = $male_avatars[array_rand($male_avatars)];
                                } else {
                                    $avatar_path = $female_avatars[array_rand($female_avatars)];
                                }
                                ?>
                                <img src="<?= base_url($avatar_path) ?>" class="rounded-circle" width="70" height="70" alt="<?= htmlspecialchars($match->username) ?>">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1"><?= htmlspecialchars($match->username) ?></h5>
                                <p class="mb-0 text-muted">@<?= htmlspecialchars($match->instagram) ?></p>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Umur</span>
                                <span class="fw-bold"><?= $match->age ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Gender</span>
                                <span class="fw-bold"><?= $match->gender ?></span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Kecocokan</span>
                                <?php if($match->match_score > 0): ?>
                                    <span class="fw-bold text-primary">
                                        <?php 
                                        $match_score = $match->match_score;
                                        if ($match_score == round($match_score)) {
                                            echo round($match_score) . '%';
                                        } else {
                                            echo number_format($match_score, 1) . '%';
                                        }
                                        ?>
                                    </span>
                                <?php else: ?>
                                    <span class="fw-bold text-warning">Belum ada skor</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="progress mb-4">
                            <?php if($match->match_score > 0): ?>
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $match->match_score ?>%"></div>
                                <?php if(isset($match->linear_position)): ?>
                                    <?php
                                    // Tambahkan marker untuk menunjukkan posisi
                                    $marker_position = min(95, (($match->linear_position - 1) / 11) * 100);
                                    ?>
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: 2%; position: absolute; left: <?= $marker_position ?>%">
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <a href="<?= base_url('matches/detail/' . $match->user_id) ?>" class="btn btn-primary">
                                <i class="fas fa-user me-2"></i> Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div> 