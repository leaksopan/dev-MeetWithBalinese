<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0">Quiz Fun - Temukan Kasta Anda</h4>
            </div>
            <div class="card-body p-4">
                <p class="lead">Jawab pertanyaan-pertanyaan berikut dengan jujur untuk menemukan kasta yang paling sesuai dengan kepribadian Anda.</p>
                <p class="text-muted mb-4">Tidak ada jawaban benar atau salah. Ini hanya untuk kesenangan dan menemukan kecocokan yang lebih baik!</p>
                
                <?php echo form_open('quiz/submit', ['id' => 'quizForm']); ?>
                    
                    <?php if (empty($questions)): ?>
                        <div class="alert alert-warning">
                            Belum ada pertanyaan quiz yang tersedia.
                        </div>
                    <?php else: ?>
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="mb-4 p-3 bg-light rounded">
                                <h5 class="mb-3"><?= ($index + 1) . '. ' . $question->question_text ?></h5>
                                
                                <?php if (!empty($question->options)): ?>
                                    <div class="d-flex flex-column gap-2">
                                        <?php foreach ($question->options as $option): ?>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" 
                                                       name="answers[<?= $question->question_id ?>]" 
                                                       id="option_<?= $option->option_id ?>" 
                                                       value="<?= $option->option_id ?>" required>
                                                <label class="form-check-label" for="option_<?= $option->option_id ?>">
                                                    <?= $option->option_text ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        Tidak ada opsi jawaban untuk pertanyaan ini.
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Lihat Hasil</button>
                        </div>
                    <?php endif; ?>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            // Cek apakah semua pertanyaan sudah dijawab
            const questions = document.querySelectorAll('[required]');
            let allAnswered = true;
            
            questions.forEach(function(question) {
                const name = question.getAttribute('name');
                const answered = document.querySelector(`input[name="${name}"]:checked`);
                
                if (!answered) {
                    allAnswered = false;
                }
            });
            
            if (!allAnswered) {
                e.preventDefault();
                alert('Harap jawab semua pertanyaan sebelum melihat hasil.');
            }
        });
    });
</script>