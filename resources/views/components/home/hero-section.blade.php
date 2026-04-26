
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>Empowering IT Skills, Transforming Futures</h1>
                    <p>Learn in-demand IT skills from expert instructors. Join our online and in-person tuition programs
                        and build your future in tech!</p>
                    <div class="hero-buttons"> <button class="btn-primary" onclick="scrollToCourses()">Browse
                            Courses</button> 
<button class="btn-outline" onclick="openContactModal()">
    Contact Us
</button>
                        </div>
                </div>
                <div class="hero-image"> <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800"
                        alt="Students learning"> </div>
            </div>
        </div>
    </section> <!-- What We Offer Section -->


    <div id="contactModal" class="modal-overlay">
    <div class="modal-box">
        <span class="close-btn" onclick="closeContactModal()">×</span>

        <div class="registration-card">
            <h2 class="reg-title">Contact Us</h2>
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

                    <div></div>

                    <div class="reg-group full">
                        <label>Message</label>
                        <textarea name="message" rows="4" placeholder="Your message..." required></textarea>
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
                    📨 Contact Us
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
   SUCCESS MODAL
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
    box-shadow:0 30px 60px rgba(15,23,42,.25);
    animation: fadeInUp .35s ease;
    border-top:6px solid #0f766e;
}

.success-title{
    font-size:18px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:8px;
}

.success-text{
    font-size:14px;
    color:#475569;
    line-height:1.6;
    margin-bottom:20px;
}

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
    background:#0b5d56;
}

/* ===============================
   OPTIONAL SMALL ELEMENTS
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

/* ===============================
   MODAL OVERLAY
   =============================== */
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(9,81,93,0.5);
    backdrop-filter:blur(8px);
    -webkit-backdrop-filter:blur(8px);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    font-family:Inter,system-ui,sans-serif;
    padding:24px;
}

/* ===============================
   MODAL BOX
   =============================== */
.modal-box{
    width:90%;
    max-width:850px;
    min-width:500px;
    max-height:90vh;
    background:#ffffff;
    border-radius:20px;
    box-shadow:0 24px 48px rgba(9,81,93,0.2), 0 0 0 1px rgba(9,81,93,0.08);
    overflow:hidden;
    display:flex;
    flex-direction:column;
    position:relative;
}

.modal-box::before{
    content:"";
    display:block;
    width:100%;
    height:120px;
    background:linear-gradient(180deg, #09515D 0%, #0a6573 100%);
    flex-shrink:0;
}

/* ===============================
   CLOSE BUTTON
   =============================== */
.close-btn{
    position:absolute;
    right:16px;
    top:16px;
    width:40px;
    height:40px;
    border:none;
    background:#f1f5f9;
    color:#64748b;
    border-radius:50%;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:22px;
    z-index:5;
    transition:background .2s,color .2s,transform .2s;
}

.close-btn:hover{
    background:#e2e8f0;
    color:#0f172a;
    transform:scale(1.05);
}

/* ===============================
   CARD
   =============================== */
.registration-card{
    background:#ffffff;
    padding:18px 24px 24px;
    width:100%;
    position:relative;
    z-index:1;
}

/* ===============================
   HEADER TEXT INSIDE TEAL BAR
   =============================== */
.reg-title{
    position:relative;
    top:-86px;
    margin:0 0 4px;
    text-align:center;
    font-size:17px;
    font-weight:800;
    line-height:1.3;
    color:#ffffff !important;
    z-index:3;
}

.reg-subtitle{
    position:relative;
    top:-86px;
    margin:0;
    text-align:center;
    font-size:12px;
    line-height:1.4;
    color:rgba(255,255,255,0.9) !important;
    z-index:3;
}

/* pull form upward */
.registration-card form{
    margin-top:-44px;
}

/* ===============================
   GRID
   =============================== */
.reg-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
    width:100%;
    align-items:start;
}

.reg-group label{
    font-size:12px;
    font-weight:700;
    color:#0f172a;
    display:block;
    margin-bottom:8px;
    line-height:1.3;
}

.reg-group input,
.reg-group select,
.reg-group textarea{
    width:100%;
    padding:8px 12px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    font-size:14px;
    color:#0f172a;
    background:#ffffff;
    outline:none;
    transition:border-color .2s, box-shadow .2s;
    min-height:44px;
    box-sizing:border-box;
}

.reg-group input::placeholder,
.reg-group textarea::placeholder{
    color:#94a3b8;
}

.reg-group input:focus,
.reg-group textarea:focus,
.reg-group select:focus{
    border-color:#09515D;
    box-shadow:0 0 0 3px rgba(9,81,93,.15);
}

.reg-group textarea{
    resize:vertical;
    min-height:120px;
}

.reg-group.full{
    grid-column:1 / -1;
}

/* ===============================
   CONSENT BOX
   =============================== */
.consent-box{
    margin-top:12px;
    padding:12px 14px;
    border:1px solid #e2e8f0;
    border-radius:8px;
    background:#f8fafc;
}

.consent-label{
    display:flex;
    gap:10px;
    align-items:flex-start;
    font-size:12px;
    color:#475569;
    line-height:1.5;
    cursor:pointer;
}

.consent-label input{
    margin-top:4px;
}

.highlight{
    color:#09515D;
    font-weight:700;
}

/* ===============================
   SUBMIT BUTTON
   =============================== */
button.reg-submit,
.reg-submit{
    appearance:none;
    -webkit-appearance:none;
    margin-top:14px;
    width:100%;
    padding:12px 20px;
    background:#09515D !important;
    color:#ffffff !important;
    font-size:15px;
    font-weight:800;
    border:none !important;
    border-radius:12px;
    cursor:pointer;
    transition:background .2s, transform .2s, box-shadow .2s;
    box-shadow:0 4px 14px rgba(9,81,93,0.35);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
}

button.reg-submit:hover,
.reg-submit:hover{
    background:#0a6573 !important;
    color:#ffffff !important;
    transform:translateY(-1px);
    box-shadow:0 6px 20px rgba(9,81,93,0.4);
}

.reg-footer{
    margin-top:8px;
    font-size:12px;
    color:#64748b;
    text-align:center;
}

/* ===============================
   NAV RESPONSIVE
   =============================== */
@media (max-width:1024px){
    .nav-container{
        padding:0 16px;
    }

    .nav-links{
        gap:20px;
        font-size:13px;
    }

    .nav-logo img{
        height:58px;
    }
}

@media (max-width:768px){
    .nav-container{
        flex-wrap:wrap;
        row-gap:14px;
    }

    .nav-links{
        width:100%;
        justify-content:center;
        flex-wrap:wrap;
        gap:18px;
        text-align:center;
    }

    .nav-right{
        width:100%;
        justify-content:center;
        gap:14px;
    }
}

@media (max-width:640px){
    .navbar-wrapper{
        padding:14px 0;
    }

    .nav-container{
        flex-direction:column;
        align-items:center;
        gap:14px;
    }

    .nav-logo img{
        height:52px;
    }

    .nav-links{
        flex-direction:column;
        gap:12px;
        width:100%;
    }

    .nav-links li{
        width:100%;
        text-align:center;
    }

    .nav-links a{
        display:inline-block;
        width:100%;
        padding:10px 0;
    }

    .nav-right{
        flex-direction:column;
        gap:10px;
    }

    .join-btn{
        width:100%;
        text-align:center;
        padding:10px 0;
    }

    .lang-box{
        width:38px;
        height:38px;
    }
}

@media (max-width:420px){
    .nav-links{
        font-size:13px;
    }

    .nav-logo img{
        height:48px;
    }
}

/* ===============================
   MOBILE DRAWER
   =============================== */
.nav-toggle{
    display:none;
    flex-direction:column;
    gap:5px;
    cursor:pointer;
}

.nav-toggle span{
    width:22px;
    height:2px;
    background:#111827;
    display:block;
}

@media (max-width:768px){
    .nav-toggle{
        display:flex;
    }

    .nav-links{
        position:fixed;
        top:0;
        right:-100%;
        height:100vh;
        width:260px;
        background:#ffffff;
        flex-direction:column;
        align-items:flex-start;
        padding:90px 24px 24px;
        gap:16px;
        box-shadow:-12px 0 30px rgba(15,23,42,.12);
        transition:right .3s ease;
        z-index:9998;
    }

    .nav-links.open{
        right:0;
    }

    .nav-links li{
        width:100%;
    }

    .nav-links a{
        width:100%;
        padding:10px 0;
        font-size:14px;
    }

    body.nav-open::after{
        content:'';
        position:fixed;
        inset:0;
        background:rgba(15,23,42,.45);
        z-index:9997;
    }

    .nav-container{
        justify-content:space-between;
    }
}

/* ===============================
   MODAL RESPONSIVE
   =============================== */
@media (max-width:768px){
    .modal-box{
        width:95%;
        min-width:unset;
        max-height:95vh;
    }

    .registration-card{
        padding:16px 18px 20px;
    }

    .reg-title,
    .reg-subtitle{
        top:-74px;
    }

    .registration-card form{
        margin-top:-34px;
    }

    .reg-grid{
        grid-template-columns:1fr;
        gap:12px;
    }
}

/* ===============================
   ANIMATION
   =============================== */
@keyframes fadeInUp{
    from{
        opacity:0;
        transform:translateY(16px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ===============================
   CONTACT MODAL – FIX HEADER + FIELD POSITION
   =============================== */
#contactModal.modal-overlay{
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    background: rgba(0,0,0,0.6);
    overflow: hidden;
    z-index: 9999;
    font-family: Inter, system-ui, sans-serif;
}

#contactModal .modal-box{
    width: 90%;
    max-width: 850px;
    min-width: 500px;
    max-height: 92vh;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 24px 48px rgba(9,81,93,0.2), 0 0 0 1px rgba(9,81,93,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
}

#contactModal .modal-box::before{
    display: none !important;
    content: none !important;
}

/* close button */
#contactModal .close-btn{
    position: absolute;
    right: 16px;
    top: 12px;
    width: 38px;
    height: 38px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 50%;
    font-size: 20px;
    border: none;
    cursor: pointer;
    z-index: 5;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* card */
#contactModal .registration-card{
    position: relative;
    padding: 0;
    background: #fff;
}

/* smaller header */
#contactModal .registration-card::before{
    content:"";
    display:block;
    width:100%;
    height: 78px;
    background: linear-gradient(180deg,#09515D 0%, #0a6573 100%);
}

/* title */
#contactModal .reg-title{
    position: absolute;
    top: 12px;
    left: 0;
    width: 100%;
    margin: 0;
    text-align: center;
    font-size: 15px;
    font-weight: 800;
    line-height: 1.2;
    color: #fff !important;
    padding: 0 60px;
    box-sizing: border-box;
}

/* subtitle */
#contactModal .reg-subtitle{
    position: absolute;
    top: 34px;
    left: 0;
    width: 100%;
    margin: 0;
    text-align: center;
    font-size: 11px;
    line-height: 1.3;
    color: rgba(255,255,255,.92) !important;
    padding: 0 60px;
    box-sizing: border-box;
}

/* IMPORTANT: push form below header */
#contactModal .registration-card form{
    margin-top: 0 !important;
    padding: 16px 14px 12px;
}

/* grid */
#contactModal .reg-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px 14px;
}

/* fields */
#contactModal .reg-group{
    display:flex;
    flex-direction:column;
    gap:4px;
}

#contactModal .reg-group.full{
    grid-column:1 / -1;
}

#contactModal .reg-group label{
    font-size:11px;
    font-weight:700;
    color:#0f172a;
    margin:0;
    line-height:1.2;
}

#contactModal .reg-group input,
#contactModal .reg-group textarea{
    width:100%;
    padding:7px 10px;
    font-size:13px;
    border-radius:7px;
    border:1px solid #e2e8f0;
    min-height:38px;
    box-sizing:border-box;
    outline:none;
}

#contactModal .reg-group textarea{
    min-height:78px;
    resize:none;
}

/* consent */
#contactModal .consent-box{
    margin-top:8px;
    padding:10px 12px;
    font-size:11px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    background:#f8fafc;
}

#contactModal .consent-label{
    display:flex;
    gap:10px;
    align-items:flex-start;
    font-size:11px;
    line-height:1.4;
    color:#475569;
}

#contactModal .consent-label input{
    margin-top:3px;
}

/* button */
#contactModal .reg-submit{
    margin-top:10px;
    width:100%;
    padding:11px;
    font-size:14px;
    font-weight:800;
    border:none;
    border-radius:10px;
    background:#09515D !important;
    color:#fff !important;
    cursor:pointer;
}

/* footer */
#contactModal .reg-footer{
    margin-top:6px;
    text-align:center;
    font-size:11px;
    color:#64748b;
}

/* mobile */
@media (max-width:768px){
    #contactModal .modal-box{
        width:95%;
        min-width:unset;
    }

    #contactModal .registration-card::before{
        height:74px;
    }

    #contactModal .reg-title{
        top:11px;
        font-size:14px;
        padding:0 52px;
    }

    #contactModal .reg-subtitle{
        top:32px;
        font-size:10px;
        padding:0 52px;
    }

    #contactModal .registration-card form{
        padding:14px 12px 12px;
    }

    #contactModal .reg-grid{
        grid-template-columns:1fr;
        gap:8px;
    }

    #contactModal .reg-group.full{
        grid-column:auto;
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
<script>
function scrollToCourses() {
    const section = document.getElementById("courses"); // match karo section ka ID
    if (section) {
        section.scrollIntoView({ behavior: "smooth" });
    }
}
</script>