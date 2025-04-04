<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <h1 class="display-4">Meet With Balinese</h1>
            <p class="lead">Temukan pasangan yang sesuai kasta di Pulau Dewata</p>
            <div class="balinese-divider"></div>
        </div>
        
        <div class="auth-form">
            <div class="auth-form-content">
                <div class="text-center mb-4">
                    <h2 class="auth-title">Masuk</h2>
                </div>
                
                <?php echo form_open('auth/login'); ?>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" required>
                        <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Masuk</button>
                    </div>
                
                <?php echo form_close(); ?>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Belum punya akun? <a href="<?= base_url('auth/register') ?>" class="text-decoration-none">Daftar sekarang</a></p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p class="text-muted">© <?= date('Y') ?> MeetWithBalinese - Temukan pasangan yang sesuai dengan kasta Anda di Bali.</p>
        </div>
    </div>
</div> 