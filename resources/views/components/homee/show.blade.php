@include('components.home.navbar')

<section class="course-detail-wrapper pdf-wrapper" id="courseContent">

   <!-- HERO -->
<div class="course-hero hero-snapshot">

    <!-- LEFT SIDE -->
    <div class="hero-left">

        <h1 class="hero-title">{{ $course->title }}</h1>
      
<div class="hero-description">
    <div class="rich-text">
        {!! $course->description !!}
    </div>
</div>
        <div class="hero-pills">
            @if($course->level)
                <span>
                                <i class="bi bi-bar-chart-steps" style="margin-right:6px;color: #F47B1E"></i>
Level: {{ $course->level }}</span>
            @endif

            @if($course->duration)
                <span>
                                <i class="bi bi-clock" style="margin-right:6px;color: #F47B1E"></i>
 Duration: {{ $course->duration }}</span>
            @endif

            <span>
                        <i class="bi bi-laptop" style="margin-right:6px;color: #F47B1E"></i>
 
                Online / Virtual</span>

            
        </div>

       
        <div class="hero-actions">
            <!-- <button class="btn-solid"
    onclick="handleLaunchCheck()">
    <i class="bi bi-calendar-check-fill" style="margin-right:2px;"></i>
    Check Available Dates
</button> -->

 <button class="btn-solid "
onclick="openEnrollModal('{{ $course->title }}', {{ $course->id }})">
                    <i class="bi bi-pencil-square"></i>

                Register Now
            </button> 



       <button class="btn-inquiry"
    onclick="openInquiryModal(
        '{{ $course->title }}',
        {{ $course->id }},
        '{{ $course->level ?? '' }}',
        '{{ $course->duration ?? '' }}'
        
    )">

 <i class="bi bi-chat-dots-fill"></i>
    Inquiry
        </div>

    </div>

    <!-- RIGHT SIDE SNAPSHOT -->
    <div class="hero-right">
        <div class="snapshot-card">

            <h4>Course Snapshot</h4>

            @if($course->price !== null)
                <div class="snapshot-price">
                    ${{ number_format($course->price, 2) }}
                </div>
            @endif

           <ul class="snapshot-list">
    @if($course->level)
        <li>
            <i class="bi bi-bar-chart-steps" style="margin-right:6px;color: #F47B1E"></i>
            Level: {{ $course->level }}
        </li>
    @endif

    @if($course->duration)
        <li>
            <i class="bi bi-clock" style="margin-right:6px;color: #F47B1E"></i>
            Duration: {{ $course->duration }}
        </li>
    @endif

    <li>
        <i class="bi bi-laptop" style="margin-right:6px;color: #F47B1E"></i>
        Mode: Online / Virtual
    </li>
</ul>

             @php
            $skills = $course->skills
                ? array_filter(array_map('trim', explode(',', $course->skills)))
                : [];
        @endphp

        @if(count($skills))
            <div class="skills-wrap" style="margin-left:-20px;">
                <h3 class="sec-title"style="    color:#09515D;
">Skills / Prerequisites</h3>
                <div class="skills">
                    @foreach($skills as $skill)
                        <span class="skill-tag" style="    color:#09515D;
">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
        @endif


            <!-- <button class="btn-solid full"
                onclick="openEnrollModal('{{ $course->title }}')">
                Register Now
            </button> -->

        </div>
    </div>

</div>


    <!-- BODY -->
    <div class="course-body">

       

        <div class="card card-course-learn">
            <h3 class="sec-title">What you will learn ?</h3>

            @if($course->topics->count())
                <div class="topics">
                    @foreach($course->topics as $topic)
                        <div class="topic-item">
                            <h4 class="topic-title">{{ $topic->title }}</h4>
                            @if($topic->description)
                                <div class="topic-desc rich-text">
                                    {!! $topic->description !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="muted">No topics added yet.</p>
            @endif
        </div>
        <!-- @if($course->launches->count())
        
<div class="card">
    <h3 class="sec-title">Upcoming Trainings</h3>

   <div class="launch-list" id="dates">
@foreach($course->launches->sortBy('launch_date') as $launch)
       <div class="launch-row pro"> -->

    <!-- LEFT: DETAILS -->
    <!-- <div class="launch-left">
        <div class="launch-date">
            <span class="day">
                {{ \Carbon\Carbon::parse($launch->launch_date)->format('d') }}
            </span>
            <span class="month">
                {{ \Carbon\Carbon::parse($launch->launch_date)->format('M Y') }}
            </span>
        </div>

        <div class="launch-details">
            <h4 class="launch-title">
                {{ $course->title }}
            </h4>

            <div class="launch-meta">
                <span>
                    <i class="bi bi-laptop" style="color: #F47B1E;"></i> Virtual
                </span>

                @if($course->duration)
                    <span>
                        <i class="bi bi-clock" style="color: #F47B1E;"></i> {{ $course->duration }}
                    </span>
                @endif

                @if($course->level)
                    <span>
                        <i class="bi bi-bar-chart-steps" style="color: #F47B1E;"></i>
                        {{ ucfirst($course->level) }}
                    </span>
                @endif
            </div>
        </div>
    </div> -->

    <!-- RIGHT: ACTIONS -->
    <!-- <div class="launch-actions">
       <button class="btn-solid small"
onclick="openInquiryModal(
    '{{ $course->title }}',
    '{{ $launch->launch_date }}',
    {{ $course->id }},
    {{ $launch->id }},
    '{{ $course->level ?? '' }}',
    '{{ $course->duration ?? '' }}'
)">
    <i class="bi bi-chat-dots-fill"></i>
    Inquiry
</button> -->




       <!-- <button class="btn-solid small"
onclick="openEnrollModal(
    '{{ $course->title }}',
    '{{ $launch->launch_date }}',
    {{ $course->id }},
    {{ $launch->id }}
)">
    <i class="bi bi-pencil-square"></i>
    Register Now
</button>

    </div> -->

</div>

    @endforeach
</div>

</div>
@endif


        <div class="card course-cta-card">
           <!-- CTA BUTTONS -->
            <!-- <div id="launchMessage"
     style="display:none; margin-top:12px; color: #09515D; font-weight:600;">
    <i class="bi bi-info-circle-fill" style="margin-right:6px;"></i>
    This course has not been launched yet. Upcoming dates will be announced soon.
</div> -->


            <div class="cta-buttons">
                 <button class="btn-primary"style="color: #fff;"
                        onclick="downloadPDF()">
                     Download PDF
                </button>

                <button class="btn-primary"style="color: #09515D;"
                        onclick="printCourse()">
                    Print
                </button>

               

                <a href="{{ route('index') }}" class="btn-secondary" style="color: #09515D;">
                    ← Back to Courses
                </a>
            </div>
        </div>
        
                </div>

    </div>
</section>

<div class="course-print-page-footer" aria-hidden="true">
    <a href="https://www.imperialtuitions.com/">www.imperialtuitions.com</a>
</div>

<!-- ENROLL MODAL -->
 <!-- ENROLL MODAL -->
<div id="enrollModal" class="modal-overlay">
    <div class="modal-box">
        <span class="close-btn" onclick="closeEnrollModal()">×</span>

<div class="enroll-header">
    <h3 id="selectedCourse" class="modal-title">
        Enroll
    </h3>

    <div class="enroll-info" id="enrollInfo" style="display:none;">
        <span class="info-pill">
            <i class="bi bi-clock"></i>
            <span id="enrollDuration"></span>
        </span>
           
        
    <span class="info-pill" id="enrollLevelWrap" style="display:none;">
        <i class="bi bi-bar-chart-steps"></i>
        <span id="enrollLevel"></span>
    </span>
        <span class="info-pill" id="enrollDateWrap" style="display:none;">
            <i class="bi bi-calendar-event"></i>
            <span id="enrollDate"></span>
        </span>
    </div>
</div>
<div class="registration-card">
    <h2 class="reg-title">Student Registration</h2>
    <p class="reg-subtitle">
        Fill the form below. A  Imperial Tuitions coordinator will confirm schedule and payment details.
    </p>

    <form method="POST" action="{{ route('course.enroll') }}">
        @csrf
        <input type="hidden" name="course_name" id="courseName">
        <!-- <input type="hidden" name="launch_date" id="selectedLaunchDate"> -->
        <input type="hidden" name="course_id" id="courseId">
        <!-- <input type="hidden" name="launch_id" id="launchId"> -->

        <input type="hidden" name="level" id="selectedLevel">



        <div class="reg-grid">
            <div class="reg-group">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Your full name" required>
            </div>

            <div class="reg-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="name@email.com" required>
            </div>

            <div class="reg-group">
                <label>Phone (Optional)</label>
                <input type="tel" name="phone" placeholder="+1 (___) ___-____">
            </div>

            <div class="reg-group">
                <label>Registration Type</label>
                <select name="registration_type">
                    <option>Individual</option>
                    <option>Corporate</option>
                </select>
            </div>
            <div class="reg-group">
    <label>Preferred Date</label>
    <input type="date" name="preferred_date">
</div>

<div class="reg-group">
    <label>Preferred Time</label>
    <input type="time" name="preferred_time">
</div>


            <div class="reg-group full">
                <label>Message (Optional)</label>
                <textarea
                    name="message"
                    placeholder="Any questions, goals, or corporate training request details..."
                    rows="4"></textarea>
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
            Submit Registration
        </button>

        <p class="reg-footer">
            By submitting, you agree to be contacted by  Imperial Tuitions for scheduling and payment coordination.
        </p>
    </form>
</div>

    </div>
</div>

<!-- INQUIRY MODAL -->
<div id="inquiryModal" class="modal-overlay">
    <div class="modal-box">
        <span class="close-btn" onclick="closeInquiryModal()">×</span>
        <div class="enroll-header">

 <h3 id="inquiryTitle" class="modal-title">
    Inquiry
</h3>

<div class="enroll-info" id="inquiryInfo" style="display:none; justify-content:center;">
    <span class="info-pill">
        <i class="bi bi-clock"></i>
        <span id="inquiryDuration"></span>
    </span>

    <span class="info-pill">
        <i class="bi bi-bar-chart-steps"></i>
        <span id="inquiryLevelText"></span>
    </span>

    
</div>
        </div>

        <div class="registration-card">
            <h2 class="reg-title">Course Inquiry</h2>
            <p class="reg-subtitle">
                Share your questions and our  Imperial Tuitions team will get back to you.
            </p>

            <form method="POST" action="{{ route('course.inquiry') }}">
                @csrf

       <!-- KEEP THIS EXACT -->
<input type="hidden" name="course_title" id="inquiryCourseTitle">
<input type="hidden" name="course_id" id="inquiryCourseId"> <!-- NEW -->
<!-- <input type="hidden" name="launch_date" id="inquiryLaunchDate">
<input type="hidden" name="launch_id" id="inquiryLaunchId">  -->
<input type="hidden" name="level" id="inquiryLevel">



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

                    <!-- EMPTY GRID SLOT (keeps 2-column layout clean) -->
                    <div></div>

                    <div class="reg-group full">
                        <label>Message</label>
                        <textarea
                            name="message"
                            rows="4"
                            placeholder="Your inquiry message..."
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
                    Submit Inquiry
                </button>

                <p class="reg-footer">
                    We usually respond within 24 hours.
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
   SUCCESS POPUP – COMPACT & CLEAN
   =============================== */
/* -----------------------------
   INQUIRY BUTTON – ICON HIDE + BOLD TEXT
   ----------------------------- */
.snapshot-card-actions .btn-inquiry.btn-snapshot i,
.btn-inquiry i {
    display: none !important;   /* Hide icon */
}

.snapshot-card-actions .btn-inquiry.btn-snapshot,
.btn-inquiry {
    font-weight: 900 !important;   /* Bold text */
    color: #fff !important;        /* Ensure text color */
    background: #09515D !important; /* Reset background so it stays original */
    border-color: #09515D !important; /* Reset border */
}

/* Mobile responsiveness (optional) */
@media (max-width: 640px) {
    .snapshot-card-actions .btn-inquiry.btn-snapshot,
    .btn-inquiry {
        width: 100% !important;
        justify-content: center !important;
        padding: 16px 0 !important;
        font-size: 15px !important;
    }
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

/* ===== LAYOUT ===== */
.course-detail-wrapper{
    max-width:1200px;
    margin:0 auto;
    padding:40px 16px;
}

/* ===== HERO ===== */
.course-hero{
    background:#09515D;
    border-radius:18px;
    padding:36px;
    display:grid;
    grid-template-columns:1.1fr 1fr;
    gap:36px;
    color:#fff;
}

.course-hero-img{
    min-height:360px;
    border-radius:14px;
    background-size:cover;
    background-position:center;
    position:relative;
}

.course-hero-img::after{
    content:'';
    position:absolute;
    inset:0;
    border-radius:14px;
    background:linear-gradient(
        180deg,
        rgba(15,23,42,.15),
        rgba(15,23,42,.65)
    );
}
/* ===== LEVEL SWITCHER ===== */
.level-switcher{
    margin:12px 0 22px;
    display:flex;
    gap:10px;
    align-items:center;
}

.level-switcher label{
    font-size:13px;
    font-weight:700;

}

.level-switcher select{
    padding:8px 12px;
    border-radius:4px;
    border:none;
    font-size:13px;
    background-color: #F47B1E;
    color: #fff;

}

#levelMessage{
    font-size:12px;
    color:#fca5a5;
    font-weight:600;
}



.course-title{
    font-size:36px;
    font-weight:900;
    margin-bottom:14px;
}

.course-meta{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:22px;
}

.meta-pill{
    font-size:13px;
    padding:7px 12px;
    border-radius:999px;
    background:rgba(255,255,255,.12);
    font-weight:600;
}

/* ===== SKILLS ===== */
.skills-wrap{
    background:rgba(255,255,255,.06);
    padding:16px;
    border-radius:12px;
    margin-bottom:26px;
}

.skills{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
}

.skill-tag{
    background:rgba(244,123,30,.18);
    color:#ffedd5;
    font-size:12px;
    padding:6px 10px;
    border-radius:999px;
    font-weight:700;
}
/*  */
/* ===== CTA BUTTONS – SEGMENTED / SaaS STYLE ===== */
.cta-buttons{
    display:flex;
    align-items:center;
    gap:18px;
    margin-top:30px;
    flex-wrap:wrap;
}

/* PRIMARY – ENROLL */
.cta-buttons .btn-primary:first-child{
    padding:14px 30px;
    font-size:14px;
    font-weight:900;
    border-radius:4px;
    background:#F47B1E;
    color:#fff;
    border:none;
    box-shadow:0 12px 28px rgba(244,123,30,.45);
    transition:.25s ease;
}

.cta-buttons .btn-primary:first-child:hover{
    transform:translateY(-2px);
    box-shadow:0 18px 40px rgba(244,123,30,.55);
}

/* SECONDARY ACTIONS – TEXT BUTTONS */
.cta-buttons .btn-primary{
    background:none;
    border:none;
    padding:0;
    font-size:13px;
    font-weight:700;
    color: #cbd5e1;
    cursor:pointer;
    position:relative;
}
.hero-description{
    min-height: 220px;   /* 👈 FIXED SPACE (buttons start after this) */
    max-height: 220px;   /* 👈 SAME height */
    overflow-y: auto;
    margin-bottom: 20px;
}
/* smooth scrollbar */
.hero-description::-webkit-scrollbar{
    width: 6px;
}
.hero-description::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:6px;
}

/* underline on hover */
.cta-buttons .btn-primary::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-4px;
    width:0;
    height:2px;
    background:#F47B1E;
    transition:.25s;
}
.cta-buttons .btn-secondary::after{
    content:'';
    position:absolute;
    left:0;
    bottom:-4px;
    width:0;
    height:2px;
    background:#F47B1E;
    transition:.25s;
}
.cta-buttons .btn-secondary:hover::after{
    width:100%;
}

.cta-buttons .btn-primary:hover{
    color:#fff;
}

.cta-buttons .btn-primary:hover::after{
    width:100%;
}

/* BACK LINK – NAV STYLE */
.btn-secondary{
    margin-left:auto;
    padding:0;
    font-size:13px;
    font-weight:700;
    color:#94a3b8;
    padding:10px 16px;
    border-radius:999px;
    background:#eef7f6;
    color:#09515D;
    border:1px solid #cbd5e1;
    transition:all .25s ease;
}

.btn-secondary:hover{
    background:#09515D;
    color: #fff !important;
    transform:translateX(-2px);
}

/* MOBILE */
@media(max-width:700px){
    .cta-buttons{
        flex-direction:column;
        align-items:flex-start;
        gap:14px;
    }

    .btn-secondary{
        margin-left:0;
    }
}

/* ===============================
   CTA BUTTONS – RESPONSIVE FIX
   =============================== */

/* Tablet */
@media (max-width: 900px){
    .cta-buttons{
        flex-wrap: wrap;
        gap:12px;
    }

    .cta-buttons .btn-primary{
        white-space: nowrap;
    }
}

/* Mobile */
@media (max-width: 640px){
    .cta-buttons{
        flex-direction: column;
        align-items: stretch;
        gap:14px;
    }

    /* Download + Print */
    .cta-buttons .btn-primary{
        width:100%;
        text-align:center;
        padding:14px 0;
        font-size:14px;
    }

    /* Back to Courses */
    .cta-buttons .btn-secondary{
        width:100%;
        text-align:center;
        justify-content:center;
        margin-left:0;
    }
}


/* ===== BODY ===== */
.course-body{
    margin-top:32px;
}

.card{
    background:#fff;
    border-radius:16px;
    padding:24px;
    margin-bottom:22px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.card-course-learn{
    border: 1px solid #b8c0df;
    background:
        linear-gradient(165deg, rgba(255, 255, 255, 0.3) 0%, transparent 48%),
        #d3d9ef;
    box-shadow:
        0 1px 0 rgba(255, 255, 255, 0.9) inset,
        0 2px 6px rgba(15, 23, 42, 0.06),
        0 12px 28px -10px rgba(15, 23, 42, 0.14),
        0 22px 48px -16px rgba(15, 23, 42, 0.12),
        0 34px 70px -26px rgba(15, 23, 42, 0.09);
}

.sec-title{
    font-size:18px;
    font-weight:800;
    margin-bottom:14px;
}

/* ===== RICH TEXT ===== */
.rich-text{
    font-size:15px;
    line-height:1.75;
    color:#374151;
    max-width:780px;
}

/* ===== TOPICS ===== */
.topics{
    display:flex;
    flex-direction:column;
    gap:14px;
}

.topic-item{
    background:#f9fafb;
    border:1px solid #e5e7eb;
    padding:16px;
    border-radius:12px;
}

/* Course detail: each topic row under “What you will learn?” */
.card-course-learn .topic-item{
    background:#d3d9ef;
    border:1px solid #b8c0df;
}

.topic-title{
    font-size:15px;
    font-weight:800;
    margin-bottom:8px;
}

.muted{
    color:#6b7280;
    font-size:14px;
}

/* ===== MODAL ===== */
.modal-overlay{
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.6);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
}

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


.close-btn{
    position:absolute;
    right:14px;
    top:10px;
    font-size:22px;
    cursor:pointer;
}

/* ===== FORM ===== */
.form-group{margin-bottom:12px;}
.form-group input,
.form-group textarea{
    width:100%;
    padding:10px 12px;
    border:1px solid #e5e7eb;
    border-radius:6px;
}

.submit-btn{
    width:100%;
    padding:12px;
    background:#09515D;
    color:#fff;
    font-weight:700;
    border:none;
    border-radius:6px;
}

.submit-btn:hover{background:#F47B1E;}

@media(max-width:900px){
    .course-hero{grid-template-columns:1fr;}
}
/* responsiveness */
/* ===============================
   RESPONSIVE – COURSE DETAIL PAGE
   =============================== */

/* LARGE TABLET */
@media (max-width: 1024px) {
    .course-title {
        font-size: 30px;
    }

    .course-hero {
        padding: 28px;
        gap: 28px;
    }

    .course-hero-img {
        min-height: 320px;
    }
}

/* TABLET */
@media (max-width: 900px) {
    .course-hero {
        grid-template-columns: 1fr;
    }

    .course-hero-img {
        min-height: 280px;
    }

    .course-title {
        font-size: 28px;
    }

    .course-meta {
        gap: 8px;
    }

    .meta-pill {
        font-size: 12px;
        padding: 6px 10px;
    }

    .level-switcher {
        flex-wrap: wrap;
    }
}

/* MOBILE */
@media (max-width: 640px) {
    .course-detail-wrapper {
        padding: 30px 14px;
    }

    .course-hero {
        padding: 22px;
        border-radius: 14px;
    }

    .course-hero-img {
        min-height: 220px;
    }

    .course-title {
        font-size: 24px;
    }

    .skills-wrap {
        padding: 14px;
    }

    .skill-tag {
        font-size: 11px;
        padding: 5px 8px;
    }

    .cta-buttons {
        margin-top: 24px;
    }

    .cta-buttons .btn-primary:first-child {
        width: 100%;
        text-align: center;
        padding: 12px 0;
    }

    .cta-buttons .btn-primary {
        font-size: 12px;
    }

    .btn-secondary {
        font-size: 12px;
    }

    .card {
        padding: 18px;
    }

    .sec-title {
        font-size: 16px;
    }

    .rich-text {
        font-size: 14px;
    }
}

/* VERY SMALL PHONES */
@media (max-width: 420px) {
    .course-title {
        font-size: 22px;
    }

    .course-hero-img {
        min-height: 200px;
    }

    .meta-pill {
        font-size: 11px;
    }

    .topic-title {
        font-size: 14px;
    }

    .topic-desc {
        font-size: 13px;
    }
}
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
.hero-left{
    display: flex;
    flex-direction: column;
    height: 100%;
}
/* MOBILE */
@media(max-width:640px){
    .reg-grid{
        grid-template-columns:1fr;
    }
}
.hero-snapshot{
    background: linear-gradient(180deg,#eef7f6,#ffffff);
    border-radius:18px;
    padding:42px;
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:36px;
}

/* LEFT */
.hero-badge{
    display:inline-flex;
    align-items:center;

    background:#fde8d8;
    color:#F47B1E;

    font-size:12px;
    font-weight:700;

    padding:6px 14px;
    border-radius:999px;

    margin-bottom:12px;

    line-height:1;        /* 🔥 IMPORTANT */
    width:fit-content;    /* 🔥 IMPORTANT */
}
.snapshot-card {
    pointer-events: none;
}


.hero-title{
    font-size:34px;
    font-weight:900;
    margin-bottom:18px;
     color:#09515D;

}

.hero-pills{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-bottom:22px;
        color:#09515D;

}

.hero-pills span{
    background:#ffffff;
    border:1px solid #e5e7eb;
    padding:7px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
     color: #09515D;

}
/* ===============================
   HERO ACTION BUTTONS – RESPONSIVE
   =============================== */

/* Tablet */
@media (max-width: 900px){
    .hero-actions{
        flex-wrap: wrap;
        gap:12px;
    }

    .hero-actions .btn-solid,
    .hero-actions .btn-outline{
        padding:12px 22px;
        font-size:14px;
    }
}

/* Mobile */
@media (max-width: 640px){
    .hero-actions{
        flex-direction: column;
        align-items: stretch;
        gap:14px;
    }

    .hero-actions .btn-solid,
    .hero-actions .btn-outline{
        width:100%;
        text-align:center;
        padding:14px 0;
        font-size:15px;
    }
}


/* ACTIONS */
.hero-actions{
    display:flex;
    gap:14px;
    margin-top:22px;
        color:#09515D;

}

.btn-solid{
    background: #09515D;
    color:#fff;
    padding:14px 26px;
    font-size:16px;
    font-weight:800;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.btn-solid i{
    color:#F47B1E;
}

.btn-solid:hover{ 
 background:#09515D;
    color:#ffffff;

    transform:translateY(-2px);

    box-shadow:
        0 12px 30px rgba(9,81,93,.25);
}
.btn-solid:hover i{ 
color :#fff !important;
}


.btn-outline{
    background:#fff;
    border:2px solid #0f766e;
    color:#09515D;
    padding:14px 26px;
    font-size:14px;
    font-weight:800;
    border-radius:8px;
    cursor:pointer;
}

/* RIGHT CARD */
.snapshot-card{
    background: #ffffff;
    border-radius:16px;
    padding:26px;
    margin-top:55px;
    box-shadow:0 14px 36px rgba(15,23,42,.12);
}

.snapshot-card h4{
    font-size:18px;
    font-weight:800;
    margin-bottom:12px;
    color:#09515D;
}

.snapshot-price{
    font-size:34px;
    font-weight:900;
    color: #09515D;
    margin-bottom:14px;
}

.snapshot-list{
    list-style:none;
    padding:0;
    margin:0 0 22px;

}

.snapshot-list li{
    font-size:18px;
    font-weight:600;
    color:#334155;
    margin-bottom:8px;
}

.snapshot-card .full{
    width:100%;
}

/* MOBILE */
@media(max-width:900px){
    .hero-snapshot{
        grid-template-columns:1fr;
        padding:28px;
    }
    .snapshot-card{
      margin-top:0px !important;

    }
}
/* lastt */
/* ===== LAUNCH LIST ===== */
.launch-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.launch-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    background:#09515D;
    border-radius:8px;
    padding:12px 16px;
    gap:14px;
}

.launch-meta{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.launch-meta .pill{
    background:#ffffff;
    color:#09515D;
    font-size:12px;
    font-weight:700;
    padding:6px 12px;
    border-radius:6px;
    border:1px solid #e5e7eb;
}

/* CTA inside launch row */
.btn-solid.small{
    padding:8px 18px;
    font-size:12px;
    border-radius:4px;
    white-space:nowrap;
}

/* Mobile */
@media(max-width:640px){
    .launch-row{
        flex-direction:column;
        align-items:flex-start;
        border-radius:16px;
    }

    .btn-solid.small{
        width:100%;
        text-align:center;
    }
}
/* ===== ENROLL HEADER (MODAL) ===== */
.enroll-header{
    padding:18px 24px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

.enroll-header .modal-title{
    font-size:22px;
    font-weight:900;
    color:#09515D;
    margin-bottom:10px;
}

.enroll-info{
    display:flex;
    justify-content:center;
    gap:10px;
    flex-wrap:wrap;
}

.info-pill{
    display:flex;
    align-items:center;
    gap:6px;
    background:#eef7f6;
    color:#09515D;
    font-size:12px;
    font-weight:700;
    padding:6px 14px;
    border-radius:999px;
    border:1px solid #cbd5e1;
}
   /* upcom */
   /* ===== UPCOMING TRAININGS – PRO VERSION ===== */
.launch-row.pro{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    padding:18px 22px;
    background:#ffffff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    box-shadow:0 10px 24px rgba(15,23,42,.06);
}

.launch-left{
    display:flex;
    gap:18px;
    align-items:center;
}

/* DATE BLOCK */
.launch-date{
    min-width:70px;
    text-align:center;
    background:#eef7f6;
    border-radius:12px;
    padding:10px 8px;
}

.launch-date .day{
    display:block;
    font-size:28px;
    font-weight:900;
    color:#09515D;
    line-height:1;
}

.launch-date .month{
    font-size:14px;
    font-weight:700;
    color:#64748b;
}

/* DETAILS */
.launch-title{
    font-size:18px;
    font-weight:800;
    margin-bottom:6px;
    color:#0f172a;
}

.launch-meta{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    font-size:16px;
    color:#475569;
}

.launch-meta span{
    display:flex;
    align-items:center;
    gap:6px;
}

/* ACTIONS */
.launch-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

/* MOBILE */
@media(max-width:640px){
    .launch-row.pro{
        flex-direction:column;
        align-items:flex-start;
    }

    .launch-actions{
        width:100%;
    }

    .launch-actions button{
        width:100%;
    }
}
/* ===== PDF MODE FIX ===== */
body.pdf-mode *{
    animation: none !important;
    transition: none !important;
}

body.pdf-mode .modal-overlay,
body.pdf-mode .btn-solid,
body.pdf-mode .btn-outline,
body.pdf-mode .cta-buttons{
    display:none !important;
}

body.pdf-mode{
    overflow: visible !important;
}

body.pdf-mode .course-hero,
body.pdf-mode .card,
body.pdf-mode .snapshot-card,
body.pdf-mode .launch-row{
    box-shadow:none !important;
    background:#fff !important;
}

body.pdf-mode .hero-snapshot{
    background:#fff !important;
}

/* CTA card: buttons hidden in PDF but .card kept a border — hide whole block */
body.pdf-mode .course-cta-card{
    display: none !important;
}
/* ===============================
   PDF SAFE LAYOUT (CRITICAL)
   =============================== */

body.pdf-mode .pdf-wrapper{
    max-width: 100% !important;
    width: 100% !important;
    padding: 20px !important;
    margin: 0 !important;
    overflow: visible !important;
}

body.pdf-mode{
    width: 100% !important;
    overflow: visible !important;
}

/* force single column */
body.pdf-mode .course-hero,
body.pdf-mode .hero-snapshot{
    display: block !important;
}

/* prevent cutting */
body.pdf-mode *{
    box-sizing: border-box !important;
}

/* keep cards intact */
body.pdf-mode .card,
body.pdf-mode .launch-row,
body.pdf-mode .launch-row.pro,
body.pdf-mode .topic-item{
    page-break-inside: avoid !important;
}

/* remove visuals that break PDF */
body.pdf-mode .hero-actions,
body.pdf-mode .cta-buttons,
body.pdf-mode button,
body.pdf-mode .modal-overlay{
    display: none !important;
}

/* neutralize shadows & gradients */
body.pdf-mode *{
    box-shadow: none !important;
    background-image: none !important;
}


/* ===============================
   HARD PDF OVERRIDES
   =============================== */
body.pdf-mode {
    background:#fff !important;
}

body.pdf-mode * {
    box-shadow: none !important;
    text-shadow: none !important;
    animation: none !important;
    transition: none !important;
}

/* remove hero gradient */
body.pdf-mode .course-hero,
body.pdf-mode .hero-snapshot {
    background: #fff !important;
    color: #000 !important;
}

/* convert grid → block */
body.pdf-mode .course-hero,
body.pdf-mode .hero-snapshot {
    display: block !important;
}

/* snapshot card */
body.pdf-mode .snapshot-card {
    margin-top: 20px !important;
    border: 1px solid #e5e7eb !important;
}

/* cards */
body.pdf-mode .card {
    border: 1px solid #e5e7eb !important;
    page-break-inside: avoid;
}

/* launch rows */
body.pdf-mode .launch-row,
body.pdf-mode .launch-row.pro {
    border: 1px solid #e5e7eb !important;
    page-break-inside: avoid;
}

/* hide ALL buttons */
body.pdf-mode button,
body.pdf-mode .btn-solid,
body.pdf-mode .btn-outline,
body.pdf-mode .cta-buttons,
body.pdf-mode .hero-actions {
    display: none !important;
}

/* remove modals completely */
body.pdf-mode .modal-overlay {
    display: none !important;
}
/* ===============================
   PDF SAFE LAYOUT (CRITICAL)
   =============================== */

body.pdf-mode .pdf-wrapper{
    max-width: 100% !important;
    width: 100% !important;
    padding: 20px !important;
    margin: 0 !important;
    overflow: visible !important;
}

body.pdf-mode{
    width: 100% !important;
    overflow: visible !important;
}

/* force single column */
body.pdf-mode .course-hero,
body.pdf-mode .hero-snapshot{
    display: block !important;
}

/* prevent cutting */
body.pdf-mode *{
    box-sizing: border-box !important;
}

/* keep cards intact */
body.pdf-mode .card,
body.pdf-mode .launch-row,
body.pdf-mode .launch-row.pro,
body.pdf-mode .topic-item{
    page-break-inside: avoid !important;
}

/* remove visuals that break PDF */
body.pdf-mode .hero-actions,
body.pdf-mode .cta-buttons,
body.pdf-mode button,
body.pdf-mode .modal-overlay{
    display: none !important;
}

/* neutralize shadows & gradients */
body.pdf-mode *{
    box-shadow: none !important;
    background-image: none !important;
}
/* ===============================
   PROFESSIONAL INQUIRY BUTTON
  
   =============================== */
   /* Normal view – looks like text */
/* NORMAL VIEW – 100% SAME LOOK */
.pdf-link,
.pdf-link:visited,
.pdf-link:hover,
.pdf-link:active{
    color: inherit !important;
    background-color:#F47B1E !important;      /* 👈 keeps original color */
    text-decoration: none !important;
    cursor: default;
}

/* PDF MODE – clickable but SAME color */
body.pdf-mode .pdf-link{
    cursor: pointer;
}
/* ONLY PDF MODE – clickable */

.btn-inquiry{
    display:inline-flex;
    align-items:center;
    gap:10px;

    padding:14px 26px;
    font-size:18px;
    font-weight:800;

    color: #fff;
    background: #09515D;

    border:2px solid #09515D;
    border-radius:10px;

    cursor:pointer;
    transition:all .25s ease;

    box-shadow:
        0 6px 18px rgba(9,81,93,.12);
}

/* icon */
.btn-inquiry i{
    font-size:16px;
    color:#F47B1E;
}

/* hover */
.btn-inquiry:hover{
    background:#09515D;
    color:#ffffff;

    transform:translateY(-2px);

    box-shadow:
        0 12px 30px rgba(9,81,93,.25);
}

.btn-inquiry:hover i{
    color:#ffffff;
}

/* active click */
.btn-inquiry:active{
    transform:translateY(0);
    box-shadow:
        0 6px 16px rgba(9,81,93,.18);
}
@media (max-width: 640px){
    .btn-inquiry{
        width:100%;
        justify-content:center;
        padding:16px 0;
        font-size:15px;
    }
}

/* ----- Print: same layout as PDF (pdf-mode + only #courseContent + footer link) ----- */
.course-print-page-footer {
    display: none !important;
}

@media print {
    @page {
        size: A4 portrait;
        margin: 0.5in 0.4in 0.6in 0.4in;
    }

    body.course-print-active * {
        visibility: hidden !important;
    }
    body.course-print-active #courseContent,
    body.course-print-active #courseContent * {
        visibility: visible !important;
    }
    body.course-print-active .course-print-page-footer,
    body.course-print-active .course-print-page-footer * {
        visibility: visible !important;
    }

    body.course-print-active #courseContent {
        position: static !important;
        left: auto !important;
        top: auto !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* “What you will learn ?” topics card on next page (same intent as main course template PDF) */
    body.course-print-active .card-course-learn {
        break-before: page !important;
        page-break-before: always !important;
    }

    body.course-print-active .course-print-page-footer {
        display: block !important;
        position: fixed !important;
        bottom: 0.3in !important;
        left: 0 !important;
        right: 0 !important;
        text-align: center !important;
        font-family: Helvetica, Arial, sans-serif !important;
        font-size: 11pt !important;
        font-weight: normal !important;
        color: #09515D !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        z-index: 99999 !important;
    }

    body.course-print-active .course-print-page-footer a {
        color: #09515D !important;
        text-decoration: none !important;
    }
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 <script>
function openEnrollModal(courseTitle, courseId = null){
    document.body.style.overflow = 'hidden';
    document.getElementById('enrollModal').style.display = 'flex';

    // Title
    document.getElementById('selectedCourse').innerText =
        'Enroll in ' + courseTitle;

    // Hidden fields
    document.getElementById('courseName').value = courseTitle;
    document.getElementById('courseId').value = courseId;

    document.getElementById('enrollInfo').style.display = 'flex';

    // Duration
    const duration = @json($course->duration);
    if (duration) {
        document.getElementById('enrollDuration').innerText = duration;
    }

    // Level
    const level = @json($course->level);
    if (level) {
        document.getElementById('enrollLevel').innerText =
            level.charAt(0).toUpperCase() + level.slice(1);

        document.getElementById('enrollLevelWrap').style.display = 'flex';
        document.getElementById('selectedLevel').value = level;
    }
}
</script>

<script>
function closeEnrollModal(){
    document.body.style.overflow = '';
    document.getElementById('enrollModal').style.display='none';
}

/* PRINT — same appearance as PDF: pdf-mode + margins + footer link */
function printCourse(){
    function cleanup(){
        document.body.classList.remove('pdf-mode', 'course-print-active');
    }
    function onAfterPrint(){
        cleanup();
        window.removeEventListener('afterprint', onAfterPrint);
    }
    document.body.classList.add('pdf-mode', 'course-print-active');
    function doPrint(){
        window.addEventListener('afterprint', onAfterPrint);
        window.print();
        setTimeout(function(){
            if (document.visibilityState === 'visible') {
                cleanup();
                window.removeEventListener('afterprint', onAfterPrint);
            }
        }, 800);
    }
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(function () {
            requestAnimationFrame(doPrint);
        }).catch(doPrint);
    } else {
        setTimeout(doPrint, 200);
    }
}

// pdf — clickable site link centered at bottom of every page
function addImperialTuitionsPdfFooter(pdf) {
    var footerText = 'www.imperialtuitions.com';
    var footerUrl = 'https://www.imperialtuitions.com/';
    var totalPages = pdf.internal.getNumberOfPages();
    var pageWidth = pdf.internal.pageSize.getWidth();
    var pageHeight = pdf.internal.pageSize.getHeight();
    pdf.setFont('helvetica', 'normal');
    pdf.setFontSize(11);
    pdf.setTextColor(9, 81, 93);
    for (var p = 1; p <= totalPages; p++) {
        pdf.setPage(p);
        var textWidth = pdf.getTextWidth(footerText);
        var x = (pageWidth - textWidth) / 2;
        var y = pageHeight - 0.3;
        if (typeof pdf.textWithLink === 'function') {
            pdf.textWithLink(footerText, x, y, { url: footerUrl });
        } else {
            pdf.text(footerText, x, y);
            var linkH = 0.17;
            pdf.link(x, y - linkH + 0.02, textWidth, linkH, { url: footerUrl });
        }
    }
    return pdf;
}

function downloadPDF(){
    const element = document.getElementById('courseContent');
    if (!element) return;

    document.body.classList.add('pdf-mode');

    const opt = {
        margin: [0.5, 0.4, 0.6, 0.4], // top right bottom left
        filename: '{{ Str::slug($course->title) }}.pdf',
        image: {
            type: 'jpeg',
            quality: 0.98
        },
        html2canvas: {
            scale: 1.2,              // 🔥 DO NOT increase
            useCORS: true,
            backgroundColor: '#ffffff',
            scrollX: 0,
            scrollY: 0,
            windowWidth: element.scrollWidth
        },
        jsPDF: {
            unit: 'in',
            format: 'a4',
            orientation: 'portrait'
        },
        pagebreak: {
            mode: ['css', 'legacy']
        }
    };

    html2pdf()
        .set(opt)
        .from(element)
        .toPdf()
        .get('pdf')
        .then(function (pdf) {
            addImperialTuitionsPdfFooter(pdf);
            return pdf;
        })
        .save()
        .then(function () {
            document.body.classList.remove('pdf-mode');
        })
        .catch(function () {
            document.body.classList.remove('pdf-mode');
        });
}


</script>


<script>
function openInquiryModal(courseTitle, courseId = null, level = '', duration = ''){
    document.body.style.overflow = 'hidden';
    document.getElementById('inquiryModal').style.display = 'flex';

    document.getElementById('inquiryTitle').innerText =
        'Inquiry about ' + courseTitle;

    document.getElementById('inquiryCourseTitle').value = courseTitle;
    document.getElementById('inquiryCourseId').value = courseId;

    document.getElementById('inquiryInfo').style.display = 'flex';

    if(duration){
        document.getElementById('inquiryDuration').innerText = duration;
    }

    if(level){
        document.getElementById('inquiryLevelText').innerText =
            level.charAt(0).toUpperCase() + level.slice(1);
        document.getElementById('inquiryLevel').value = level;
    }
}
</script>
<script>


function closeInquiryModal(){
    document.body.style.overflow = '';
    document.getElementById('inquiryModal').style.display = 'none';
}
</script>


<script>
function handleLaunchCheck() {
    const datesSection = document.getElementById('dates');
    const messageBox = document.getElementById('launchMessage');

    if (datesSection && datesSection.children.length > 0) {
        datesSection.scrollIntoView({ behavior: 'smooth' });
        if (messageBox) messageBox.style.display = 'none';
    } else {
        if (messageBox) {
            messageBox.style.display = 'block';
            messageBox.scrollIntoView({ behavior: 'smooth' });
        }
    }
}
</script>
<script>
function closeSuccessModal(){
    document.getElementById('successModal').style.display = 'none';
    document.body.style.overflow = '';
}

@if(session('popup_success'))
document.addEventListener('DOMContentLoaded', function () {

    // Close any open modals
    ['contactModal','enrollModal','inquiryModal'].forEach(id => {
        const modal = document.getElementById(id);
        if(modal) modal.style.display = 'none';
    });

    // Inject dynamic text
    document.querySelector('.success-title').innerText =
        @json(session('popup_title'));

    document.querySelector('.success-text').innerText =
        @json(session('popup_message'));

    // Show success popup
    document.getElementById('successModal').style.display = 'flex';
});
@endif
</script>
