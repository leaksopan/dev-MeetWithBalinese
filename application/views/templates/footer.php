    </div> <!-- End of container -->

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4">
                    <h5>MeetWithBalinese</h5>
                    <p>Platform pencarian pasangan yang menghormati tradisi kasta di Bali dan membantu Anda menemukan pasangan yang sesuai dengan kasta Anda.</p>
                </div>
                <div class="col-md-4">
                    <h5>Tautan</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>">Beranda</a></li>
                        <li><a href="<?= base_url('quiz') ?>">Quiz Kasta</a></li>
                        <li><a href="<?= base_url('matches') ?>">Cari Pasangan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> info@meetwithbalinese.com</li>
                        <li><i class="fas fa-phone me-2"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Denpasar, Bali, Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="balinese-divider my-4"></div>
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> MeetWithBalinese. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html> 