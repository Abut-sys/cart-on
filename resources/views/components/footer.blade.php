<!-- resources/views/footer.blade.php -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <!-- Logo Section -->
            <div class="footer-col logo-col">
                <div class="logo-wrapper">
                    <img src="/image/Logo_baru.png" alt="CartON Logo" class="footer-logo">
                    <div class="company-slogan">
                        <p>Your Trusted E-Commerce Partner</p>
                    </div>
                </div>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="#"><i class="fa-solid fa-chevron-right"></i>Home</a></li>
                    <li><a href="#"><i class="fa-solid fa-chevron-right"></i>Products</a></li>
                    <li><a href="#"><i class="fa-solid fa-chevron-right"></i>About Us</a></li>
                    <li><a href="#"><i class="fa-solid fa-chevron-right"></i>Contact</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="footer-col">
                <h3 class="footer-title">Contact Info</h3>
                <ul class="contact-info">
                    <li>
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span>Jl. Manggarai No. 45<br>Jakarta, Bekasi 12345</span>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <a href="mailto:CartOn@mail.com">CartOn@mail.com</a>
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <a href="tel:+622112345678">(021) 1234-5678</a>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-col">
                <h3 class="footer-title">Newsletter</h3>
                <p class="newsletter-text">Dapatkan update promo dan produk terbaru!</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Masukkan email Anda" required>
                    <button type="submit">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom">
            <div class="copyright">
                <p>&copy; {{ date('Y') }} CartON. All rights reserved.</p>
            </div>
            <div class="legal-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
