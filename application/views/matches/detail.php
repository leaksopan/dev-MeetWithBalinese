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
                        <img src="<?= base_url('assets/img/profiles/' . ($match_user->profile_image ?: 'default.jpg')) ?>" 
                             class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                        <h3><?= html_escape($match_user->name) ?></h3>
                        <p class="text-muted">
                            <?= html_escape($match_user->gender == 'male' ? 'Laki-laki' : 'Perempuan') ?>, 
                            <?= html_escape($match_user->age ?: 'Usia tidak disebutkan') ?>
                        </p>
                        
                        <?php if(isset($match) && $match): ?>
                            <?php 
                            $match_score = $match->match_score;
                            $match_percentage = $match_score . '%';
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
                // Dapatkan hasil kasta pengguna
                $this->load->model('quiz_model');
                $kasta = $this->quiz_model->get_kasta_detail($match_user->kasta_id ?? 1);
                ?>
                
                <div class="card">
                    <div class="card-header">
                        <h5>Kepribadian Bali</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3"><?= html_escape($kasta->kasta_name) ?></h4>
                        <p><?= html_escape($kasta->description) ?></p>
                    </div>
                </div>
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