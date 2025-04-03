<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card mt-4">
            <div class="card-header bg-white text-center py-3">
                <h4 class="mb-0">Registrasi Akun Baru</h4>
            </div>
            <div class="card-body p-4">
                
                <?php echo form_open('auth/process_register'); ?>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= set_value('email') ?>" required>
                            <?php echo form_error('email', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="instagram" class="form-label">Instagram</label>
                            <input type="text" class="form-control" id="instagram" name="instagram" value="<?= set_value('instagram') ?>" required>
                            <?php echo form_error('instagram', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= set_value('username') ?>" required>
                            <?php echo form_error('username', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Umur</label>
                            <input type="number" class="form-control" id="age" name="age" value="<?= set_value('age') ?>" min="18" max="100" required>
                            <?php echo form_error('age', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="" disabled <?= set_select('gender', '', TRUE) ?>>Pilih...</option>
                                <option value="Male" <?= set_select('gender', 'Male') ?>>Pria</option>
                                <option value="Female" <?= set_select('gender', 'Female') ?>>Wanita</option>
                            </select>
                            <?php echo form_error('gender', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <?php echo form_error('password', '<small class="text-danger">', '</small>'); ?>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="passconf" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="passconf" name="passconf" required>
                            <?php echo form_error('passconf', '<small class="text-danger">', '</small>'); ?>
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a></label>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Daftar</button>
                    </div>
                
                <?php echo form_close(); ?>
                
                <div class="text-center mt-4">
                    <p class="mb-0">Sudah punya akun? <a href="<?= base_url('auth') ?>" class="text-decoration-none">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div> 