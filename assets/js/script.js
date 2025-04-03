/**
 * Custom JavaScript untuk MeetWithBalinese
 */
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle Password Visibility di form login/register
    const togglePasswordButtons = document.querySelectorAll('.toggle-password');
    if (togglePasswordButtons) {
        togglePasswordButtons.forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = document.querySelector(this.getAttribute('data-toggle'));
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    }
    
    // Animasi progress bar saat halaman dimuat
    const progressBars = document.querySelectorAll('.progress-bar');
    if (progressBars) {
        setTimeout(() => {
            progressBars.forEach(bar => {
                bar.style.width = bar.getAttribute('style').replace('width: ', '');
            });
        }, 100);
    }
    
    // Validasi form register
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passconf = document.getElementById('passconf').value;
            
            if (password !== passconf) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
            }
        });
    }
    
    // Auto dismiss alert setelah 5 detik
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    if (alerts) {
        setTimeout(() => {
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    }
    
    // Hapus tab active saat halaman dimuat
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;
    
    if (navLinks) {
        navLinks.forEach(link => {
            link.classList.remove('active');
            
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    }
}); 