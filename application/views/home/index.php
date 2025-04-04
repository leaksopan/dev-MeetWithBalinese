<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="hero-section mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold text-primary mb-3">Meet With Balinese</h1>
                <div class="balinese-divider mb-4"></div>
                <p class="lead mb-4">Platform kencan pertama di Bali yang memperhatikan nilai-nilai tradisional dan kepribadian.</p>
                <p class="mb-4">Kami membantu Anda menemukan pasangan yang sesuai dengan kasta dan kepribadian Anda melalui metode yang menyenangkan dan tidak menghakimi.</p>
                
                <?php if (!isset($has_taken_quiz) || !$has_taken_quiz): ?>
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="<?= base_url('quiz') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-question-circle me-2"></i> Ikuti Quiz Sekarang
                        </a>
                    </div>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i> Ikuti quiz untuk mengetahui kasta Anda dan menemukan kecocokan.
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
            <div class="col-lg-6">
                <div class="hero-image-container">
                    <img src="<?= base_url('assets/images/Città - Living Room.png') ?>" alt="Meet With Balinese" class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="section-title text-center mb-5">
                <h2 class="fw-bold">Bagaimana Cara Kerjanya?</h2>
                <div class="balinese-divider mx-auto my-3"></div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <h4>Buat Profil</h4>
                            <p>Daftar dan buat profil Anda dengan informasi dasar untuk mulai mencari kecocokan.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <h4>Ikuti Quiz</h4>
                            <p>Jawab pertanyaan fun untuk mengidentifikasi kasta dan kepribadian Anda.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="feature-card h-100">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon mb-3">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4>Temukan Kecocokan</h4>
                            <p>Kami akan mencocokkan Anda dengan orang yang sesuai berdasarkan kasta dan kepribadian.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section Styles */
.hero-section {
    padding: 60px 0;
    background-color: #FFF8E5;
    position: relative;
    overflow: hidden;
}

.hero-section:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 40%;
    height: 100%;
    background-color: #FFFAF0;
    z-index: 0;
    border-top-left-radius: 50% 80%;
    border-bottom-left-radius: 50% 80%;
}

.hero-image-container {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-image {
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(140, 74, 30, 0.15);
}

.text-primary {
    color: #8C4A1E !important;
}

/* Feature Cards */
.feature-card {
    border: 2px solid #BCA888;
    border-radius: 8px;
    background-color: #FFFAF0;
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 25px rgba(140, 74, 30, 0.15);
}

.feature-icon {
    font-size: 48px;
    color: #E9B44C;
}

.section-title h2 {
    color: #8C4A1E;
    font-family: 'Philosopher', serif;
}

.alert-info {
    background-color: #FFFAF0;
    border-color: #BCA888;
    color: #8C4A1E;
}
</style> 