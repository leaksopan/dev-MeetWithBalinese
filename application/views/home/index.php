<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h2 class="fw-bold mb-3">Selamat Datang di MeetWithBalinese!</h2>
                        <p class="lead">Platform kencan pertama di Bali yang memperhatikan nilai-nilai tradisional dan kepribadian.</p>
                        <p class="mb-4">Kami membantu Anda menemukan pasangan yang sesuai dengan kepribadian Anda melalui metode yang menyenangkan dan tidak langsung.</p>
                        
                        <?php 
                        // Cek apakah user sudah mengikuti quiz
                        $CI =& get_instance();
                        $CI->load->database();
                        $result = $CI->db->get_where('userkastaresult', ['user_id' => $user['user_id']])->row();
                        
                        if (!$result): ?>
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="<?= base_url('quiz') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-question-circle me-2"></i> Ikuti Quiz Sekarang
                                </a>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i> Ikuti quiz untuk mengetahui kepribadian Anda dan menemukan kecocokan.
                            </div>
                        <?php else: ?>
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="<?= base_url('matches') ?>" class="btn btn-primary btn-lg">
                                    <i class="fas fa-heart me-2"></i> Lihat Matches
                                </a>
                                <a href="<?= base_url('quiz') ?>" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-sync-alt me-2"></i> Ambil Quiz Lagi
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-5 mt-4 mt-md-0">
                        <img src="https://placekitten.com/600/400" alt="MeetWithBalinese" class="img-fluid rounded">
                    </div>
                </div>
            </div>
        </div>
        
        <h4 class="mb-3">Cara Kerja</h4>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h5>Buat Profil</h5>
                        <p class="text-muted">Daftar dan buat profil Anda dengan informasi dasar.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h5>Ikuti Quiz</h5>
                        <p class="text-muted">Menjawab pertanyaan fun untuk mengidentifikasi kepribadian Anda.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center p-4">
                        <div class="display-4 text-primary mb-3">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h5>Temukan Kecocokan</h5>
                        <p class="text-muted">Kami akan mencocokkan Anda dengan orang yang sesuai.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 