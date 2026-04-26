<footer class="btmg-footer">
    <div class="footer-container">

        <!-- Left Section -->
        <div class="footer-left">
            <img src="images/footer.png" alt="BTMG Trainings Logo" class="footer-logo">
            <p class="footer-text">
                Empowering learners everywhere with quality courses.
                Start your journey today and unlock your true potential.
            </p>

            <div class="footer-social">
    <span>Follow Us:</span>

   <a href="https://www.facebook.com/yourpage" class="social-icon facebook" target="_blank" aria-label="Facebook">
    <i class="fa-brands fa-facebook-f"></i>
</a>

<a href="https://www.instagram.com/yourpage" class="social-icon instagram" target="_blank" aria-label="Instagram">
    <i class="fa-brands fa-instagram"></i>
</a>

<a href="https://www.linkedin.com/company/yourpage" class="social-icon linkedin" target="_blank" aria-label="LinkedIn">
    <i class="fa-brands fa-linkedin-in"></i>
</a>

</div>

        </div>

        <!-- Middle Section -->
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="#courses">Courses</a></li>
                <li><a href="#training">Gallery</a></li>
                <li><a href="#work">How we Work</a></li>
                <li><a href="#testimonials">Testimonials</a></li>
                <li>
    <a href="{{ route('privacy') }}" target="_blank" rel="noopener noreferrer">
        Privacy Policy
    </a>
</li>
            </ul>
        </div>

        <!-- Right Section -->
<div class="footer-links footer-nav">
            <h4 style="color:#fff">Navigation</h4>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#">We Offer</a></li>
                <li><a href="#">FAQ’s</a></li>
                <li><a href="#">Discover</a></li>
<li>
    <a href="{{ route('terms') }}" target="_blank" rel="noopener noreferrer">
        Terms & Conditions
    </a>
</li>
            </ul>
        </div>

    </div>

    <!-- Scroll to Top -->
    <div class="scroll-top">▲</div>
</footer>
<style>
    /* ===============================
   RESPONSIVE – FOOTER
   =============================== */
.social-icon {
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #ffffff;
    font-size: 18px;
    text-decoration: none;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    margin-right: 2px;
}

/* Facebook official */
.social-icon.facebook {
    background-color: #1877F2;
}

/* Instagram official gradient */
.social-icon.instagram {
    background: radial-gradient(
        circle at 30% 107%,
        #fdf497 0%,
        #fdf497 5%,
        #fd5949 45%,
        #d6249f 60%,
        #285AEB 90%
    );
}

/* LinkedIn official */
.social-icon.linkedin {
    background-color: #0A66C2;
}

/* Hover effect (clean & professional) */
.social-icon:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}

/* LARGE TABLET */
/* Desktop only offset */
@media (min-width: 769px) {
    .footer-nav {
        margin-left: -130px;
    }
}

@media (max-width: 1024px) {
    .btmg-footer {
        padding: 50px 40px;
    }

    .footer-container {
        gap: 50px;
    }

    .scroll-top {
        right: 40px;
        bottom: 120px;
    }
}

/* TABLET */
@media (max-width: 768px) {
    .footer-container {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: left;
    }

    /* Remove negative margin effect on small screens */
    .footer-links[style*="margin-left"] {
        margin-left: 0 !important;
    }

    .footer-text {
        max-width: 100%;
    }

    .footer-social {
        flex-wrap: wrap;
    }

    .scroll-top {
        right: 30px;
        bottom: 30px;
    }
}

/* MOBILE */
@media (max-width: 640px) {
    .btmg-footer {
        padding: 40px 20px;
    }

    .footer-logo {
        width: 110px;
    }

    .footer-text {
        font-size: 13px;
    }

    .footer-links h4 {
        font-size: 14px;
        margin-bottom: 14px;
    }

    .footer-links ul li a {
        font-size: 13px;
    }

    .scroll-top {
        width: 38px;
        height: 38px;
        font-size: 13px;
    }
}

/* VERY SMALL PHONES */
@media (max-width: 420px) {
    .footer-social {
        gap: 10px;
        font-size: 13px;
    }

    .social-icon {
        width: 26px;
        height: 26px;
        font-size: 12px;
    }
}
/* ===============================
   FIX FOOTER NAV LINKS VISIBILITY
   =============================== */

@media (max-width: 768px) {

    .footer-links {
        width: 100%;
        display: block;
        visibility: visible;
        opacity: 1;
        position: relative;
    }

    .footer-links ul {
        display: block;
    }

    .footer-links ul li {
        display: block;
    }
}
/* ===============================
   FORCE SHOW NAVIGATION ON MOBILE
   =============================== */

@media (max-width: 768px) {

    .footer-container > .footer-links[style] {
        margin-left: 0 !important;
    }

}
/* ===============================
   HARD FIX – FORCE NAVIGATION BACK
   =============================== */

@media (max-width: 768px) {

    /* Reset ANY horizontal offset completely */
    .footer-container > .footer-links[style] {
        margin-left: 0 !important;
        transform: none !important;
        left: auto !important;
        right: auto !important;
    }

    /* Force full-width stacking */
    .footer-container > .footer-links {
        grid-column: 1 / -1;
        width: 100%;
        max-width: 100%;
    }
}




</style>
<script>
(() => {
    const scrollBtn = document.querySelector('.scroll-top');
    if (!scrollBtn) return;

    // Show / hide button
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });

    // Scroll to top on click
    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
})();
</script>
