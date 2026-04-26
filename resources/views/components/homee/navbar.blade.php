
<nav class="navbar-wrapper" style="overflow-x:hidden !important;">
    <div class="nav-container">
<!-- Mobile Menu Toggle -->
<div class="nav-toggle" onclick="toggleNav()" style="margin-right:-250px !important; color:#09515D !important;">
    <span></span>
    <span></span>
    <span></span>
</div>

        {{-- Logo --}}
        <div class="nav-logo">
            <img src="/images/btmg-logo.png" alt="BTMG Trainings">
        </div>

        {{-- Navigation Links --}}
        <ul class="nav-links">
            <li><a href="#" class="active-link">Home</a></li>
            <li><a href="#courses">Courses</a></li>
            <li><a href="#learn">About Platform</a></li>
            <li><a href="#trust">Why Choose Us</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
<li>
    <a href="javascript:void(0)" onclick="openContactModal()">Get Notified</a>
</li>
        </ul>

        {{-- Right Buttons --}}
        <div class="nav-right">
            <a href="#courses" class="join-btn">Join Us</a>

            <div class="lang-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 3C7.031 3 3 7.031 3 12s4.031 
                             9 9 9 9-4.031 9-9-4.031-9-9-9zm0 
                             0c-2.757 0-5 4.031-5 9s2.243 9 
                             5 9m0-18c2.757 0 5 4.031 5 9s-2.243 
                             9-5 9m-9-9h18" />
                </svg>
            </div>
        </div>
    </div>
</nav>
<!-- Contact Modal -->
<!-- CONTACT MODAL -->
<div id="contactModal" class="modal-overlay">
    <div class="modal-box">
        <span class="close-btn" onclick="closeContactModal()">×</span>

        <h3 class="modal-title" style="text-align:center">Contact Us</h3>

        <div class="registration-card">
            <h2 class="reg-title">Get in Touch</h2>
            <p class="reg-subtitle">
                Leave us a message and our team will respond shortly.
            </p>

            <form method="POST" action="{{ route('contact.store') }}">
                @csrf

                <div class="reg-grid">
                    <div class="reg-group">
                        <label>Full Name</label>
                        <input type="text" name="name" required>
                    </div>

                    <div class="reg-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="reg-group">
                        <label>Phone (Optional)</label>
                        <input type="tel" name="phone">
                    </div>

                    <!-- empty column to preserve grid balance -->
                    <div></div>

                    <div class="reg-group full">
                        <label>Message</label>
                        <textarea
                            name="message"
                            rows="4"
                            placeholder="Your message..."
                            required></textarea>
                    </div>
                </div>
                <div class="consent-box">
            <label class="consent-label">
                <input type="checkbox" required>
                <span>
                    <strong>Consent & Disclaimer</strong><br>
                    I confirm that all information provided is accurate.<br>
                    I agree that my information will be used by
                    <span class="highlight"> Imperial Tuitions</span>
                    solely for educational and enrollment purposes.<br>
                    I understand that my data will not be shared with any third-party organizations.
                </span>
            </label>
        </div>

                <button type="submit" class="reg-submit">
                    Send Message
                </button>

                <p class="reg-footer">
                    We usually respond within one business day.
                </p>
            </form>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="successModal" class="modal-overlay">
    <div class="success-box">
        <h3 class="success-title">Message Sent Successfully</h3>

        <p class="success-text">
            Thank you for reaching out to <strong> Imperial Tuitions</strong>.<br>
            Our team will contact you shortly.
        </p>

        <button class="success-btn" onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<style>
    /* ===============================
   SUCCESS MODAL – THEME MATCH
   =============================== */
/* ===============================
   SUCCESS POPUP – COMPACT & CLEAN
   =============================== */

#successModal{
    z-index:10000;
}

.success-box{
    background:#ffffff;
    width:380px;
    max-width:92vw;
    padding:28px 26px 26px;
    border-radius:16px;
    text-align:center;

    box-shadow:
        0 30px 60px rgba(15,23,42,.25);

    animation: fadeInUp .35s ease;

    border-top:6px solid #0f766e;
}

/* TITLE */
.success-title{
    font-size:18px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

/* TEXT */
.success-text{
    font-size:14px;
    color:#475569;
    line-height:1.6;
    margin-bottom:20px;
}

/* BUTTON */
.success-btn{
    width:100%;
    padding:12px;
    background:#0f766e;
    color:#fff;
    font-size:14px;
    font-weight:700;
    border:none;
    border-radius:999px;
    cursor:pointer;
    transition:.2s ease;
}

.success-btn:hover{
    background:#f59e0b;
}

       /* ===============================
   ENROLL MODAL – BRAND ALIGNED
   =============================== */
   .level-select{
    margin-top:6px;
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #e5e7eb;
    font-size:13px;
    cursor:pointer;
}

.single-level{
    font-size:13px;
    color:#6b7280;
    margin-top:6px;
}

.course-details-link{
    display:inline-block;
    font-size:13px;
    color:#09515D;
    font-weight:600;
    text-decoration:none;
}
.course-details-link:hover{
    color:#F47B1E;
    text-decoration:underline;
}


.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    font-family: Inter, system-ui, sans-serif;
}

/* MODAL CARD */

.modal-box{
    background:#fff;
    width:900px;
    height:600px;

    max-width:95vw;
    max-height:90vh;

    padding:0;               /* important */
    border-radius:14px;
    position:relative;

    display:flex;
    flex-direction:column;

    overflow:hidden;         /* clean edges */
}
/* DESKTOP ONLY – FORCE SIZE */
@media (min-width: 1025px) {
    .modal-box{
        width:900px !important;
        height:600px !important;
        max-width:none !important;
        max-height:none !important;
    }
}


/* CLOSE */
.close-btn {
    position: absolute;
    right: 14px;
    top: 10px;
    font-size: 22px;
    color: #6b7280;
    cursor: pointer;
    transition: color .2s ease;
}
.close-btn:hover {
    color: #F47B1E;
}

/* HEADER */
.modal-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 4px;
}

.modal-subtitle {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 18px;
}

/* FORM */
.form-group {
    margin-bottom: 12px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    font-size: 13px;
    border-radius: 4px;
    border: 1px solid #e5e7eb;
    color: #111827;
    outline: none;
    transition: all .2s ease;
    background: #fff;
}

.form-group textarea {
    resize: vertical;
    min-height: 90px;
}

/* FOCUS */
.form-group input:focus,
.form-group textarea:focus {
    border-color: #F47B1E;
    box-shadow: 0 0 0 2px rgba(244,123,30,0.15);
}

/* SUBMIT */
.submit-btn {
    width: 100%;
    margin-top: 8px;
    padding: 12px;
    background: #09515D;          /* brand teal */
    color: #ffffff;
    border: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
}

.submit-btn:hover {
    background: #F47B1E;          /* brand orange on hover */
}

/* ANIMATION */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* responsive */
/* ===============================
   RESPONSIVE NAVBAR FIXES
   =============================== */

/* TABLET */
@media (max-width: 1024px) {
    .nav-container {
        padding: 0 16px;
    }

    .nav-links {
        gap: 20px;
        font-size: 13px;
    }

    .nav-logo img {
        height: 58px;
    }
}

/* SMALL TABLET */
@media (max-width: 768px) {
    .nav-container {
        flex-wrap: wrap;
        row-gap: 14px;
    }

    .nav-links {
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
        gap: 18px;
        text-align: center;
    }

    .nav-right {
        width: 100%;
        justify-content: center;
        gap: 14px;
    }
}

/* MOBILE */
@media (max-width: 640px) {
    .navbar-wrapper {
        padding: 14px 0;
    }

    .nav-container {
        flex-direction: column;
        align-items: center;
        gap: 14px;
    }

    .nav-logo img {
        height: 52px;
    }

    .nav-links {
        flex-direction: column;
        gap: 12px;
        width: 100%;
    }

    .nav-links li {
        width: 100%;
        text-align: center;
    }

    .nav-links a {
        display: inline-block;
        width: 100%;
        padding: 10px 0;
    }

    .nav-right {
        flex-direction: column;
        gap: 10px;
    }

    .join-btn {
        width: 100%;
        text-align: center;
        padding: 10px 0;
    }

    .lang-box {
        width: 38px;
        height: 38px;
    }
}

/* VERY SMALL PHONES */
@media (max-width: 420px) {
    .nav-links {
        font-size: 13px;
    }

    .nav-logo img {
        height: 48px;
    }
}
/* ===============================
   MOBILE DRAWER TOGGLER
   =============================== */

/* HIDE TOGGLER ON DESKTOP */
.nav-toggle {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
}

.nav-toggle span {
    width: 22px;
    height: 2px;
    background: #111827;
    display: block;
}

/* MOBILE ONLY */
@media (max-width: 768px) {

    .nav-toggle {
        display: flex;
    }

    /* Drawer base */
    .nav-links {
        position: fixed;
        top: 0;
        right: -100%;
        height: 100vh;
        width: 260px;
        background: #ffffff;
        flex-direction: column;
        align-items: flex-start;
        padding: 90px 24px 24px;
        gap: 16px;
        box-shadow: -12px 0 30px rgba(15,23,42,.12);
        transition: right .3s ease;
        z-index: 9998;
    }

    /* Show drawer */
    .nav-links.open {
        right: 0;
    }

    .nav-links li {
        width: 100%;
    }

    .nav-links a {
        width: 100%;
        padding: 10px 0;
        font-size: 14px;
    }

    /* Overlay when open */
    body.nav-open::after {
        content: '';
        position: fixed;
        inset: 0;
        background: rgba(15,23,42,.45);
        z-index: 9997;
    }

    /* Keep logo + buttons aligned */
    .nav-container {
        justify-content: space-between;
    }
}

/* regis */

@media (max-width: 768px){
    .modal-box{
        width:95vw;
        height:90vh;
    }

    .registration-card{
        padding:20px;
    }

    .reg-grid{
        grid-template-columns:1fr;
    }
}

/* registration */
.registration-card{
    background: linear-gradient(#fff, #fff) padding-box,
                linear-gradient(135deg,#f59e0b,#22c1c3) border-box;
    border:2px solid transparent;
    border-radius:14px;

    padding:28px;
    font-family: Inter, system-ui, sans-serif;

    width:100%;
    height:100%;

    overflow-y:auto;   /* 👈 scroll INSIDE if needed */
}


.reg-title{
    font-size:24px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:6px;
}

.reg-subtitle{
    font-size:13px;
    color:#64748b;
    margin-bottom:22px;
}

.reg-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.reg-group label{
    font-size:13px;
    font-weight:600;
    color:#0f172a;
    display:block;
    margin-bottom:6px;
}

.reg-group input,
.reg-group select,
.reg-group textarea{
    width:100%;
    padding:12px 14px;
    border-radius:10px;
    border:1px solid #cbd5e1;
    font-size:14px;
    outline:none;
}

.reg-group input:focus,
.reg-group textarea:focus,
.reg-group select:focus{
    border-color:#22c1c3;
    box-shadow:0 0 0 3px rgba(34,193,195,.15);
}

.reg-group.full{
    grid-column:1 / -1;
}

.consent-box{
    margin-top:18px;
    padding:14px;
    border:1px solid #e5e7eb;
    border-radius:10px;
    background:#fff;
}

.consent-label{
    display:flex;
    gap:12px;
    font-size:13px;
    color:#111827;
    line-height:1.5;
}

.consent-label input{
    margin-top:4px;
}

.highlight{
    color:#ef4444;
    font-weight:700;
}

.reg-submit{
    margin-top:18px;
    width:100%;
    padding:14px;
    background:#0f766e;
    color:#fff;
    font-size:15px;
    font-weight:800;
    border:none;
    border-radius:10px;
    cursor:pointer;
}

.reg-submit:hover{
    background:#f59e0b;
}

.reg-footer{
    margin-top:10px;
    font-size:12px;
    color:#64748b;
    text-align:center;
}

/* MOBILE */
@media(max-width:640px){
    .reg-grid{
        grid-template-columns:1fr;
    }
}
</style>

<script>
function openContactModal() {
    document.getElementById('contactModal').style.display = 'flex';
}

function closeContactModal() {
    document.getElementById('contactModal').style.display = 'none';
}

// Close on outside click
window.addEventListener('click', function(e) {
    const modal = document.getElementById('contactModal');
    if (e.target === modal) {
        closeContactModal();
    }
});
</script>
<script>
function toggleNav() {
    const nav = document.querySelector('.nav-links');
    nav.classList.toggle('open');
    document.body.classList.toggle('nav-open');
}

/* Close drawer on link click (mobile UX) */
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.nav-links').classList.remove('open');
        document.body.classList.remove('nav-open');
    });
});
</script>
<script>
function closeSuccessModal(){
    document.getElementById('successModal').style.display = 'none';
}

@if(session('contact_success'))
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('successModal').style.display = 'flex';
    });
@endif
</script>
