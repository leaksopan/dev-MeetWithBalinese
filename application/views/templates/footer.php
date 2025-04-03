    </div> <!-- End of container -->

    <!-- Footer -->
    <footer class="bg-white py-4 mt-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-primary">MeetWithBalinese</h5>
                    <p class="text-muted">Platform kencan untuk masyarakat Bali yang memperhatikan nilai-nilai tradisional dan kasta.</p>
                </div>
                <div class="col-md-3">
                    <h5>Link</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-decoration-none">Home</a></li>
                        <li><a href="<?= base_url('quiz') ?>" class="text-decoration-none">Quiz</a></li>
                        <li><a href="<?= base_url('matches') ?>" class="text-decoration-none">Matches</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> info@meetwithbalinese.com</li>
                        <li><i class="fas fa-phone me-2"></i> +62 812 3456 7890</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Bali, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr>
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