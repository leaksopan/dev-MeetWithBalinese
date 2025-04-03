<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card mt-4">
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0">Login</h4>
            </div>
            <div class="card-body p-4">
                
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
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                
                <?php echo form_close(); ?>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Belum punya akun? <a href="<?= base_url('auth/register') ?>" class="text-decoration-none">Daftar sekarang</a></p>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <h5>MeetWithBalinese</h5>
            <p class="text-muted">Temukan pasangan yang sesuai dengan kasta Anda di Bali.</p>
        </div>
    </div>
</div> 