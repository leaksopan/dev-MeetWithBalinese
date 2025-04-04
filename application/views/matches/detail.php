<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container mt-4">
    <h1 class="mb-4">Detail Kecocokan</h1>
    
    <?php if(isset($match_user)): ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <?php
                        // Tentukan avatar berdasarkan gender
                        $gender = ($match_user->gender == 'male') ? 'male' : 'female';
                        
                        if ($match_user->profile_image) {
                            $avatar_path = 'assets/images/avatar/' . $gender . '/' . $match_user->profile_image;
                        } else {
                            // Coba gunakan avatar berdasarkan gender
                            if ($gender == 'male') {
                                $avatar_path = 'assets/images/avatar/male/Città - Avatar.png';
                            } else {
                                $avatar_path = 'assets/images/avatar/female/Allura - Avatar.png';
                            }
                        }
                        ?>
                        <img src="<?= base_url($avatar_path) ?>" 
                             class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                        <h3><?= html_escape($match_user->name) ?></h3>
                        <p class="text-muted">
                            <?= html_escape($match_user->gender == 'male' ? 'Laki-laki' : 'Perempuan') ?>, 
                            <?= html_escape($match_user->age ?: 'Usia tidak disebutkan') ?>
                        </p>
                        
                        <?php if(isset($match) && $match): ?>
                            <?php 
                            $match_score = $match->match_score;
                            $match_percentage = number_format($match_score, 1) . '%';
                            // Jika nilainya bulat (contoh: 85.0), hilangkan desimal
                            if ($match_score == round($match_score)) {
                                $match_percentage = round($match_score) . '%';
                            }
                            $match_color = 'success';
                            
                            if($match_score == 0) {
                                $match_color = 'warning';
                                $match_percentage = 'Belum ada skor';
                            } else if($match_score < 50) {
                                $match_color = 'danger';
                            } else if($match_score < 75) {
                                $match_color = 'warning';
                            }
                            ?>
                            
                            <div class="mt-3">
                                <h4>Kecocokan: <span class="text-<?= $match_color ?>"><?= $match_percentage ?></span></h4>
                            </div>
                            
                            <?php if($match->status == 'pending'): ?>
                                <div class="mt-3">
                                    <a href="<?= site_url('matches/accept/' . $match->match_id) ?>" 
                                       class="btn btn-success btn-sm me-2">Terima</a>
                                    <a href="<?= site_url('matches/reject/' . $match->match_id) ?>" 
                                       class="btn btn-danger btn-sm">Tolak</a>
                                </div>
                            <?php else: ?>
                                <div class="mt-3">
                                    <span class="badge bg-<?= $match->status == 'accepted' ? 'success' : 'danger' ?>">
                                        <?= $match->status == 'accepted' ? 'Diterima' : 'Ditolak' ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informasi Profil</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Bio:</strong> <?= html_escape($match_user->bio ?: 'Tidak ada bio') ?></p>
                        <p><strong>Lokasi:</strong> <?= html_escape($match_user->location ?: 'Tidak disebutkan') ?></p>
                        <p><strong>Minat:</strong> <?= html_escape($match_user->interests ?: 'Tidak disebutkan') ?></p>
                    </div>
                </div>
                
                <?php 
                // Dapatkan hasil kepribadian pengguna
                $this->load->model('quiz_model');
                $kasta = $this->quiz_model->get_kasta_detail($match_user->kasta_id ?? 1);
                ?>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Kepribadian Bali</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3">
                            <?= html_escape($kasta->kasta_name) ?>
                        </h4>
                        <p><?= html_escape($kasta->description) ?></p>
                        
                        <?php if(isset($match_user_linear_position)): ?>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Posisi Kepribadian</strong>
                                <div class="progress mt-2" style="height: 10px;">
                                    <?php 
                                    // Hitung persentase dari posisi untuk progress bar
                                    $progress_percent = (($match_user_linear_position - 1) / 11) * 100;
                                    ?>
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: <?= $progress_percent ?>%" 
                                         aria-valuenow="<?= $match_user_linear_position ?>" 
                                         aria-valuemin="1" aria-valuemax="12">
                                    </div>
                                </div>
                            </div>
                            
                            <?php if(isset($user_linear_position)): ?>
                            <div class="alert alert-secondary mt-3">
                                <strong>Perbandingan Kepribadian</strong>
                                <?php if ($match_score == 0): ?>
                                <div class="alert alert-warning mt-2 mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Belum ada skor kecocokan. Salah satu dari Anda belum mengisi quiz.
                                </div>
                                <?php else: ?>
                                <div class="progress mt-3" style="height: 15px;">
                                    <?php 
                                    // Posisi marker pada skala 0-100%
                                    $user_marker = (($user_linear_position - 1) / 11) * 100;
                                    $match_marker = (($match_user_linear_position - 1) / 11) * 100;
                                    ?>
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: 2%; position: absolute; left: <?= $user_marker ?>%">
                                    </div>
                                    <div class="progress-bar bg-warning" role="progressbar" 
                                         style="width: 2%; position: absolute; left: <?= $match_marker ?>%">
                                    </div>
                                </div>
                                <p class="small mt-2 mb-0">Kecocokan kepribadian Anda: <strong class="text-primary"><?= number_format($match_score, 1) ?>%</strong></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if(!empty($match_user->instagram)): ?>
                <div class="mt-3">
                    <a href="https://www.instagram.com/<?= html_escape($match_user->instagram) ?>/" class="btn btn-primary w-100" target="_blank">
                        <i class="fab fa-instagram me-2"></i> Kunjungi Instagram
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            Data pengguna tidak ditemukan.
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="<?= site_url('matches') ?>" class="btn btn-secondary">Kembali ke Daftar</a>
    </div>
</div> 