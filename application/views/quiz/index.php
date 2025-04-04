<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center my-5">
    <div class="col-lg-8 col-md-10">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Quiz Kepribadian Bali</h3>
                    <div class="quiz-progress">
                        <span id="currentQuestion">1</span> dari <span id="totalQuestions"><?= count($questions) ?></span>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" style="width: <?= (1 / count($questions)) * 100 ?>%;" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 d-none d-md-block text-center">
                        <div class="quiz-illustration">
                            <!-- Kontainer untuk animasi dan pergantian gambar -->
                            <div id="characterContainer" class="character-container">
                                <!-- Default karakter - akan diganti secara random oleh JS -->
                            </div>
                            <div class="illustration-message" id="quizMessageBox">
                                <p>Pilih jawaban yang paling sesuai dengan diri Anda!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <p class="lead">Jawab pertanyaan-pertanyaan berikut dengan jujur untuk menemukan kepribadian yang paling sesuai dengan diri Anda.</p>
                
                        <?php echo form_open('quiz/submit', ['id' => 'quizForm']); ?>
                            
                            <?php if (empty($questions)): ?>
                                <div class="alert alert-warning">
                                    Belum ada pertanyaan quiz yang tersedia.
                                </div>
                            <?php else: ?>
                                <?php foreach ($questions as $index => $question): ?>
                                    <div class="question-card mb-4 <?= $index === 0 ? 'active' : 'd-none' ?>" data-question="<?= $index + 1 ?>">
                                        <h4 class="question-text mb-4"><?= $question->question_text ?></h4>
                                        
                                        <?php if (!empty($question->options)): ?>
                                            <div class="options-container">
                                                <?php foreach ($question->options as $option): ?>
                                                    <div class="option-item mb-3">
                                                        <input class="option-input" type="radio" 
                                                               name="answers[<?= $question->question_id ?>]" 
                                                               id="option_<?= $option->option_id ?>" 
                                                               value="<?= $option->option_id ?>" required>
                                                        <label class="option-label" for="option_<?= $option->option_id ?>">
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
                                        
                                        <div class="navigation-buttons mt-4 d-flex justify-content-between">
                                            <?php if ($index > 0): ?>
                                                <button type="button" class="btn btn-outline-secondary prev-btn" data-question="<?= $index + 1 ?>">Sebelumnya</button>
                                            <?php else: ?>
                                                <div></div>
                                            <?php endif; ?>
                                            
                                            <?php if ($index < count($questions) - 1): ?>
                                                <button type="button" class="btn btn-primary next-btn" data-question="<?= $index + 1 ?>">Selanjutnya</button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-primary submit-btn">Lihat Hasil</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.option-item {
    position: relative;
}

.option-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.option-label {
    display: block;
    position: relative;
    padding: 15px 20px 15px 50px;
    background-color: #FFFAF0;
    border: 2px solid #BCA888;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.option-label:before {
    content: '';
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 22px;
    height: 22px;
    border: 2px solid #8C4A1E;
    border-radius: 50%;
    background-color: #FFF8E5;
}

.option-input:checked + .option-label {
    background-color: #8C4A1E;
    color: #FFF8E5;
    border-color: #8C4A1E;
}

.option-input:checked + .option-label:before {
    background-color: #E9B44C;
    border-color: #FFF8E5;
}

.option-input:checked + .option-label:after {
    content: '';
    position: absolute;
    left: 21px;
    top: 50%;
    transform: translateY(-50%);
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #FFF8E5;
}

.question-text {
    color: #8C4A1E;
    font-weight: 600;
}

.quiz-progress {
    font-weight: 600;
    color: #8C4A1E;
}

.progress-bar {
    background-color: #E9B44C;
}

/* Ilustrasi styling */
.side-illustration {
    max-width: 100%;
    margin-bottom: 15px;
}

.quiz-illustration {
    position: sticky;
    top: 50px;
}

.character-container {
    min-height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 15px;
    transition: all 0.5s ease;
}

.character-image {
    max-width: 100%;
    border-radius: 8px;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.character-image:hover {
    transform: scale(1);
}

.character-enter {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.illustration-message {
    background-color: #FFF8E5;
    border: 2px solid #BCA888;
    border-radius: 15px;
    padding: 15px;
    position: relative;
    margin-top: 10px;
}

.illustration-message:before {
    content: '';
    position: absolute;
    top: -10px;
    left: 30px;
    width: 20px;
    height: 20px;
    background-color: #FFF8E5;
    border-left: 2px solid #BCA888;
    border-top: 2px solid #BCA888;
    transform: rotate(45deg);
}

.illustration-message p {
    margin-bottom: 0;
    color: #8C4A1E;
    font-weight: 500;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = document.querySelectorAll('.question-card');
        const totalQuestions = questions.length;
        const progressBar = document.querySelector('.progress-bar');
        const currentQuestionElem = document.getElementById('currentQuestion');
        const messageBox = document.getElementById('quizMessageBox');
        const characterContainer = document.getElementById('characterContainer');
        
        // Array gambar karakter
        const characterImages = [
            '<?= base_url('assets/images/Fuzzy Friends - Looking for Love (1).png') ?>',
            '<?= base_url('assets/images/Fuzzy Friends - Nature Life.png') ?>',
            '<?= base_url('assets/images/Big Shoes - Torso.png') ?>',
            '<?= base_url('assets/images/Fuzzy Friends - Artsy Cat.png') ?>',
            '<?= base_url('assets/images/Fuzzy Friends - Little Elephant.png') ?>',
            '<?= base_url('assets/images/Fuzzy Friends - Playful Fox.png') ?>',
            '<?= base_url('assets/images/Fuzzy Friends - Sweet Koala.png') ?>'
        ];
        
        // Fungsi untuk menampilkan karakter secara random
        function showRandomCharacter() {
            const randomIndex = Math.floor(Math.random() * characterImages.length);
            characterContainer.innerHTML = '';
            
            const imgElement = document.createElement('img');
            imgElement.src = characterImages[randomIndex];
            imgElement.alt = 'Karakter';
            imgElement.className = 'character-image character-enter';
            
            characterContainer.appendChild(imgElement);
        }
        
        // Tampilkan karakter random ketika halaman dimuat
        showRandomCharacter();
        
        // Pesan motivasi untuk ditampilkan pada bubble chat
        const messages = [
            "Pilih jawaban yang paling sesuai dengan diri Anda!",
            "Ingat, tidak ada jawaban yang salah dalam quiz ini.",
            "Terus lanjutkan! Anda hampir setengah jalan!",
            "Jawaban Anda membantu kami menemukan kepribadian yang tepat.",
            "Sebentar lagi selesai, tetap semangat!",
            "Sedikit lagi, kami akan menunjukkan hasil kepribadianmu!"
        ];
        
        // Tombol navigasi
        document.querySelectorAll('.next-btn').forEach(button => {
            button.addEventListener('click', function() {
                const currentQuestionNum = parseInt(this.getAttribute('data-question'));
                const currentQuestion = document.querySelector(`.question-card[data-question="${currentQuestionNum}"]`);
                const nextQuestion = document.querySelector(`.question-card[data-question="${currentQuestionNum + 1}"]`);
                
                // Cek apakah pertanyaan saat ini sudah dijawab
                const questionInput = currentQuestion.querySelector('input[type="radio"]:checked');
                if (!questionInput) {
                    alert('Harap jawab pertanyaan ini sebelum melanjutkan.');
                    return;
                }
                
                currentQuestion.classList.add('d-none');
                currentQuestion.classList.remove('active');
                
                nextQuestion.classList.remove('d-none');
                nextQuestion.classList.add('active');
                
                // Update progress
                currentQuestionElem.textContent = currentQuestionNum + 1;
                progressBar.style.width = `${((currentQuestionNum + 1) / totalQuestions) * 100}%`;
                
                // Update pesan chat bubble
                const messageIndex = Math.min(Math.floor((currentQuestionNum / totalQuestions) * messages.length), messages.length - 1);
                messageBox.innerHTML = `<p>${messages[messageIndex]}</p>`;
                
                // Tampilkan karakter random yang baru
                showRandomCharacter();
            });
        });
        
        document.querySelectorAll('.prev-btn').forEach(button => {
            button.addEventListener('click', function() {
                const currentQuestionNum = parseInt(this.getAttribute('data-question'));
                const currentQuestion = document.querySelector(`.question-card[data-question="${currentQuestionNum}"]`);
                const prevQuestion = document.querySelector(`.question-card[data-question="${currentQuestionNum - 1}"]`);
                
                currentQuestion.classList.add('d-none');
                currentQuestion.classList.remove('active');
                
                prevQuestion.classList.remove('d-none');
                prevQuestion.classList.add('active');
                
                // Update progress
                currentQuestionElem.textContent = currentQuestionNum - 1;
                progressBar.style.width = `${((currentQuestionNum - 1) / totalQuestions) * 100}%`;
                
                // Update pesan chat bubble
                const messageIndex = Math.max(Math.floor(((currentQuestionNum - 2) / totalQuestions) * messages.length), 0);
                messageBox.innerHTML = `<p>${messages[messageIndex]}</p>`;
                
                // Tampilkan karakter random yang baru
                showRandomCharacter();
            });
        });
        
        // Form validation before submit
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            const inputs = document.querySelectorAll('input[type="radio"][required]');
            let allAnswered = true;
            
            inputs.forEach(input => {
                const name = input.getAttribute('name');
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