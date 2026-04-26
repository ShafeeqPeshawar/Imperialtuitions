@include('components.home.navbar')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Inter&family=Syne&display=swap" rel="stylesheet">
<section class="course-detail-wrapper pdf-wrapper" id="courseContent">

   <!-- HERO -->
<div class="course-hero hero-snapshot">

    <!-- LEFT SIDE -->
    <div class="hero-left">

        <h1 class="hero-title">{{ $course->title }}</h1>
      
<div class="hero-description">
    <div class="rich-text ql-editor">
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

       
<div class="hero-actions hero-btn-group">
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

            <div class="snapshot-card-actions">
                <button type="button" class="btn-inquiry btn-snapshot"
                    onclick="openInquiryModal(
                        '{{ $course->title }}',
                        {{ $course->id }},
                        '{{ $course->level ?? '' }}',
                        '{{ $course->duration ?? '' }}'
                    )">
                    <i class="bi bi-chat-dots-fill"></i>
                    Inquiry
                </button>
                <button type="button" class="btn-primary btn-snapshot" onclick="downloadPDF()">
                    Download PDF
                </button>
                <button type="button" class="btn-primary btn-snapshot" onclick="printCourse()">
                    Print
                </button>
            </div>

        </div>
    </div>

</div>


    <!-- BODY -->
    <div class="course-body">
        {{-- html2pdf: force new page before “What you will learn?” + topics (see pagebreak.before + body.pdf-mode CSS) --}}
        <div class="pdf-learn-section-start" aria-hidden="true"></div>

        <div class="course-content-layout">
            <div class="course-content-main">
                <h3 class="sec-title course-learn-heading">What you will learn ?</h3>
                <div class="card course-topics-card">
                    @if($course->topics->count())
                        <div class="topics">
                            @foreach($course->topics as $topic)
                                <div class="topic-item">

                                    <!-- Title OUTSIDE -->
                                    <h4 class="topic-title">
                                        {{ $loop->iteration }}. {{ ucwords($topic->title) }}.
                                    </h4>

                                    @if(filled($topic->trimmed_description))
                                    <div class="topic-desc rich-text ql-editor">{!! $topic->trimmed_description !!}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="muted">No topics added yet.</p>
                    @endif
                </div>
            </div>

            <aside class="course-content-sidebar">
                <h3 class="course-sidebar-heading">{{ $sidebarHeading }}</h3>
                <div class="course-sidebar-cards-scroll" aria-label="Related courses">
                <div class="courses-grid details-courses-grid">
                    @foreach($sidebarCourses as $sidebarCourse)
                        @php
                            $launchDate = '';
                            if ($sidebarCourse->nextLaunch && $sidebarCourse->nextLaunch->launch_date) {
                                $launchDate = \Carbon\Carbon::parse($sidebarCourse->nextLaunch->launch_date)->toDateString();
                            }
                        @endphp
                        <div class="course-card"
                             data-level="{{ strtolower($sidebarCourse->level) }}"
                             data-category="{{ $sidebarCourse->training_category_id }}"
                             data-free="{{ $sidebarCourse->price == 0 ? 'yes' : 'no' }}"
                             data-launch="{{ $launchDate }}">
                            <h4>{{ $sidebarCourse->title }}</h4>
                            <p>{!! Str::limit(strip_tags($sidebarCourse->description), 160) !!}</p>
                            <p class="level-text"><strong>{{ ucfirst($sidebarCourse->level) }}</strong></p>
                            <p class="course-info">
                                <strong>Duration:</strong> {{ $sidebarCourse->duration ?? 'N/A' }}<br>
                                <strong>Charges:</strong> @if($sidebarCourse->price == 0) Free @else £{{ number_format($sidebarCourse->price, 2) }} @endif
                            </p>
                            <p class="course-info">Mode: Online / Virtual</p>
                            <div class="course-actions">
                                <a href="{{ route('show', $sidebarCourse->id) }}" class="btn-primary btn-details" style="text-decoration:none;">Details</a>
                                <button class="btn-outline btn-inquiry"
                                        onclick="openInquiryModal('{{ $sidebarCourse->title }}', {{ $sidebarCourse->id }}, '{{ $sidebarCourse->level ?? '' }}', '{{ $sidebarCourse->duration ?? '' }}')">Inquiry</button>
                            </div>
                        </div>
                    @endforeach
                </div>
                </div>
            </aside>
        </div>

        <div class="card course-cta-card">
            <div class="cta-buttons">
                <button class="btn-primary" style="color: #fff;"
                        onclick="downloadPDF()">
                    Download PDF
                </button>
                <button class="btn-primary" style="color: #09515D;"
                        onclick="printCourse()">
                    Print
                </button>
                <a href="{{ route('index') }}#courses" class="btn-secondary" style="color: #09515D;">
                    ← Back to Courses
                </a>
            </div>
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

{{-- Close launch block: @if / @foreach above are inside <!-- but Blade still parses them --}}
@endforeach
@endif

    </div>{{-- /.course-body --}}
</section>

{{-- Print/PDF: sibling of #courseContent so html2pdf does not rasterize it; shown only when printing with course-print-active --}}
<div class="course-print-page-footer" aria-hidden="true">
    <a href="https://www.imperialtuitions.com/">www.imperialtuitions.com</a>
</div>

<script>
(function () {
    function updateLearnHeadingStickyRelease() {
        if (document.body.classList.contains('pdf-mode')) return;

        var main = document.querySelector('.course-content-main');
        var heading = document.querySelector('.course-learn-heading');
        var nav = document.querySelector('.main-header');

        if (!main || !heading) return;

        var navBottom = nav ? nav.getBoundingClientRect().bottom : 0;
        var mainBottom = main.getBoundingClientRect().bottom;
        var need = navBottom + heading.offsetHeight + 24;

        if (mainBottom < need) {
            heading.classList.add('course-learn-heading--released');
        } else {
            heading.classList.remove('course-learn-heading--released');
        }
    }

    function updateSidebarHeight() {
        if (document.body.classList.contains('pdf-mode')) return;

        const main = document.querySelector('.course-content-main');
        const sidebarScroll = document.querySelector('.course-sidebar-cards-scroll');
        const sidebarHeading = document.querySelector('.course-sidebar-heading');

        if (!main || !sidebarScroll) return;

        const mainHeight = main.offsetHeight;
        const headingHeight = sidebarHeading ? sidebarHeading.offsetHeight : 0;
        const visibleHeight = Math.max(120, mainHeight - headingHeight);

        sidebarScroll.style.maxHeight = visibleHeight + 'px';
        sidebarScroll.style.height = visibleHeight + 'px';
        sidebarScroll.style.overflowY = 'auto';
    }

    function syncSidebarAfterLayout() {
        updateSidebarHeight();
        requestAnimationFrame(updateSidebarHeight);
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateLearnHeadingStickyRelease();
        syncSidebarAfterLayout();

        const main = document.querySelector('.course-content-main');
        const grid = document.querySelector('.course-content-sidebar .details-courses-grid');

        if (main && typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(function () {
                updateSidebarHeight();
            }).observe(main);
        }

        if (grid && typeof ResizeObserver !== 'undefined') {
            new ResizeObserver(function () {
                updateSidebarHeight();
            }).observe(grid);
        }

        window.addEventListener('scroll', updateLearnHeadingStickyRelease, { passive: true });
        window.addEventListener('resize', function () {
            updateLearnHeadingStickyRelease();
            syncSidebarAfterLayout();
        });
        window.addEventListener('load', syncSidebarAfterLayout);
    });
})();
</script>

<!-- ENROLL MODAL -->
<div id="enrollModal" class="modal-overlay enroll-modal-overlay">
    <div class="modal-box enroll-modal-box" >
        <button type="button" class="enroll-close-btn" onclick="closeEnrollModal()" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="enroll-modal-header">
            <h3 id="selectedCourse" class="enroll-modal-title">Enroll</h3>
            <p class="enroll-modal-subtitle">A coordinator will confirm schedule and payment details</p>
            <div class="enroll-info enroll-info-pills" id="enrollInfo" style="display:none;">
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

        <div class="enroll-modal-body">
            <form method="POST" action="{{ route('course.enroll') }}" class="enroll-form">
                @csrf
                <input type="hidden" name="course_name" id="courseName">
                <input type="hidden" name="course_id" id="courseId">
                <input type="hidden" name="level" id="selectedLevel">

                <div class="enroll-form-grid">
                    <div class="enroll-field">
                        <label for="enrollName">Full Name</label>
                        <input type="text" name="name" id="enrollName" placeholder="Your full name" required>
                    </div>
                    <div class="enroll-field">
                        <label for="enrollEmail">Email</label>
                        <input type="email" name="email" id="enrollEmail" placeholder="name@email.com" required>
                    </div>
                    <div class="enroll-field">
                        <label for="enrollPhone">Phone <span class="optional">(Optional)</span></label>
                        <input type="tel" name="phone" id="enrollPhone" placeholder="+1 (___) ___-____">
                    </div>
                    <div class="enroll-field">
                        <label for="enrollRegType">Registration Type</label>
                        <select name="registration_type" id="enrollRegType">
                            <option>Individual</option>
                            <option>Corporate</option>
                        </select>
                    </div>
                    <div class="enroll-field">
                        <label for="enrollDate">Preferred Date</label>
                        <input type="date" name="preferred_date" id="enrollDate">
                    </div>
                    <div class="enroll-field">
                        <label for="enrollTime">Preferred Time</label>
                        <input type="time" name="preferred_time" id="enrollTime">
                    </div>
                    <div class="enroll-field enroll-field-full">
                        <label for="enrollMessage">Message <span class="optional">(Optional)</span></label>
                        <textarea name="message" id="enrollMessage" rows="3" placeholder="Any questions, goals, or corporate training details..."></textarea>
                    </div>
                </div>

                <div class="enroll-consent">
                    <label class="enroll-consent-label">
                        <input type="checkbox" required class="enroll-consent-checkbox">
                        <span class="enroll-consent-text">
                            I confirm the information provided is accurate and agree that Imperial Tuitions may use it for educational and enrollment purposes. My data will not be shared with third parties.
                        </span>
                    </label>
                </div>

                <button type="submit" class="enroll-submit-btn">
                    <i class="bi bi-pencil-square"></i>
                    Submit Registration
                </button>
                <p class="enroll-footer">By submitting, you agree to be contacted for scheduling and payment coordination.</p>
            </form>
        </div>
    </div>
</div>

<!-- INQUIRY MODAL -->
<div id="inquiryModal" class="modal-overlay enroll-modal-overlay">
    <div class="modal-box enroll-modal-box">
        <button type="button" class="enroll-close-btn" onclick="closeInquiryModal()" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="enroll-modal-header">
            <h3 id="inquiryTitle" class="enroll-modal-title">Inquiry</h3>
            <p class="enroll-modal-subtitle">We’ll get back to you within 24 hours</p>

            <div class="enroll-info enroll-info-pills" id="inquiryInfo" style="display:none;">
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

        <div class="enroll-modal-body">
            <form method="POST" action="{{ route('course.inquiry') }}" class="enroll-form">
                @csrf
                <input type="hidden" name="course_title" id="inquiryCourseTitle">
                <input type="hidden" name="course_id" id="inquiryCourseId">
                <input type="hidden" name="level" id="inquiryLevel">

                <div class="enroll-form-grid">
                    <div class="enroll-field">
                        <label for="inquiryName">Full Name</label>
                        <input type="text" name="name" id="inquiryName" placeholder="John Smith" required>
                    </div>

                    <div class="enroll-field">
                        <label for="inquiryEmail">Email</label>
                        <input type="email" name="email" id="inquiryEmail" placeholder="john@example.com" required>
                    </div>

                    <div class="enroll-field enroll-field-full">
                        <label for="inquiryPhone">Phone <span class="optional">(Optional)</span></label>
                        <input type="tel" name="phone" id="inquiryPhone" placeholder="+1 (555) 000-0000">
                    </div>

                    <div class="enroll-field enroll-field-full">
                        <label for="inquiryMessage">Your message</label>
                        <textarea name="message" id="inquiryMessage" rows="3" placeholder="Tell us your questions about this course, schedule, or pricing..." required></textarea>
                    </div>
                </div>

                <div class="enroll-consent">
                    <label class="enroll-consent-label">
                        <input type="checkbox" name="consent" required class="enroll-consent-checkbox">
                        <span class="enroll-consent-text">
                            I confirm the information provided is accurate and agree that Imperial Tuitions may use it for educational and enrollment purposes. My data will not be shared with third parties.
                        </span>
                    </label>
                </div>

                <button type="submit" class="enroll-submit-btn">
                    📧 Send Inquiry
                </button>

                <p class="enroll-footer">We usually respond within 24 hours.</p>
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
.course-content-layout{
    display:flex;
    align-items:flex-start !important;
    gap:30px;
}

/* sidebar column */

/* scrollable courses container */
.course-sidebar-cards-scroll{
    overflow-y:auto;
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

/* ===============================
   INQUIRY MODAL – PROFESSIONAL STYLE
   =============================== */
/* ===============================
   INQUIRY MODAL – PROFESSIONAL STYLE
   =============================== */
@keyframes inquiryModalIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.inquiry-modal-overlay {
    background: rgba(9, 81, 93, 0.5);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    padding: 24px;
    align-items: center;
    justify-content: center;
}

.inquiry-modal-overlay[style*="flex"] .inquiry-modal-box {
    animation: inquiryModalIn 0.3s ease-out;
}

.inquiry-modal-box {
    width: 90%;               /* Wider modal */
    max-width: 850px;         /* Desktop max width */
    min-width: 500px;         /* Prevent it from shrinking too much */
    max-height: 90vh;
    height: auto;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 24px 48px rgba(9, 81, 93, 0.2), 0 0 0 1px rgba(9, 81, 93, 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-sizing: border-box;    /* Important for padding + width */
}

.inquiry-modal-box .inquiry-close-btn {
    top: 10px;
    right: 12px;
    width: 34px;
    height: 34px;
    font-size: 16px;
}

.inquiry-close-btn {
    position: absolute;
    right: 16px;
    top: 16px;
    width: 40px;
    height: 40px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    z-index: 2;
    transition: background 0.2s, color 0.2s, transform 0.2s;
}

.inquiry-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: scale(1.05);
}

.inquiry-modal-header {
    background: linear-gradient(180deg, #09515D 0%, #0a6573 100%);
    color: #fff;
    padding: 14px 20px 12px;
    text-align: center;
    position: relative;
    flex-shrink: 0;
}

.inquiry-modal-title {
    font-size: 17px;
    font-weight: 800;
    margin: 0 0 2px;
    letter-spacing: -0.02em;
    color: #fff;
    line-height: 1.3;
}

.inquiry-modal-subtitle {
    font-size: 12px;
    opacity: 0.9;
    margin: 0 0 10px;
    font-weight: 500;
}

.inquiry-info-pills {
    justify-content: center;
    gap: 8px;
    margin-top: 0;
}

.inquiry-modal-header .info-pill {
    padding: 4px 10px;
    font-size: 11px;
    background: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

.inquiry-modal-header .bi {
    color: #fff !important;
}

.inquiry-modal-body {
    padding: 18px 24px 24px;
    overflow: visible;
    flex: 1;
    width: 100%;              /* Ensures full width inside modal */
    box-sizing: border-box;
}

.inquiry-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);  /* 2 columns on wider screens */
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
     max-width: 100%;     
}

.inquiry-field {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.inquiry-field-full {
    grid-column: 1 / -1;
}

.inquiry-field label {
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
}

.inquiry-field .optional {
    font-weight: 500;
    color: #64748b;
}

.inquiry-field input,
.inquiry-field textarea {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    color: #0f172a;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.inquiry-field input::placeholder,
.inquiry-field textarea::placeholder {
    color: #94a3b8;
}

.inquiry-field input:focus,
.inquiry-field textarea:focus {
    outline: none;
    border-color: #09515D;
    box-shadow: 0 0 0 3px rgba(9, 81, 93, 0.15);
}

.inquiry-field textarea {
    resize: vertical;
    min-height: 68px;
}

.inquiry-consent {
    margin-top: 12px;
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.inquiry-consent-label {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    cursor: pointer;
    font-size: 12px;
    color: #475569;
    line-height: 1.4;
}

.inquiry-consent-checkbox {
    margin-top: 2px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: #09515D;
}

.inquiry-submit-btn {
    margin-top: 14px;
    width: 100%;
    padding: 12px 20px;
    background: #09515D;       /* Original green color */
    color: #fff;
    font-size: 15px;
    font-weight: 800;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(9, 81, 93, 0.35);
}

.inquiry-submit-btn:hover {
    background: #0a6573;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(9, 81, 93, 0.4);
}

.inquiry-submit-btn:active {
    transform: translateY(0);
}

.inquiry-submit-btn i {
    display: none;   /* Remove icon from Send Inquiry button */
}

.inquiry-footer {
    margin-top: 8px;
    text-align: center;
    font-size: 12px;
    color: #64748b;
}

@media (max-width: 760px) {
    .inquiry-modal-box {
        max-width: 95%; /* Responsive width for smaller screens */
        min-width: unset;
    }
    .inquiry-form-grid {
        grid-template-columns: 1fr; /* stack columns on small screens */
        gap: 12px;
    }
}
/* ===============================
   ENROLL MODAL – same as inquiry (compact, no scroll)
   =============================== */
@keyframes enrollModalIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.enroll-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    background-color: rgba(0,0,0,0.6);
    overflow: hidden;
}
.enroll-modal-overlay[style*="flex"] .enroll-modal-box {
    animation: enrollModalIn 0.3s ease-out;
}

.enroll-modal-box {
    width: 100%;
    max-width: 540px;
    max-height: 98vh;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 24px 48px rgba(9,81,93,0.2), 0 0 0 1px rgba(9,81,93,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.enroll-close-btn {
    position: absolute;
    right: 16px;
    top: 10px;
    width: 34px;
    height: 34px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
    transition: background 0.2s, color 0.2s, transform 0.2s;
}

.enroll-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: scale(1.05);
}

.enroll-modal-header {
    background: linear-gradient(180deg, #09515D 0%, #0a6573 100%);
    color: #fff;
    padding: 10px 14px 8px;
    text-align: center;
    position: relative;
    flex-shrink: 0;
}

.enroll-modal-title {
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 1px;
    letter-spacing: -0.02em;
    color: #fff;
    line-height: 1.2;
}

.enroll-modal-subtitle {
    font-size: 11px;
    opacity: 0.9;
    margin: 0 0 6px;
    font-weight: 500;
}
.enroll-modal-header .enroll-info-pills {
    justify-content: center;
    gap: 8px;
    margin-top: 0;
}

.enroll-modal-header .info-pill {
    padding: 3px 8px;
    font-size: 10px;
    background: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

.enroll-modal-header .bi {
    color: #fff !important;
}

.enroll-modal-body {
    padding: 8px 10px 10px;
    overflow: hidden;
    flex: 1 1 auto;
}
.enroll-form {
    padding-bottom: 4px;
}
.enroll-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    width: 100%;
    padding: 6px;
    box-sizing: border-box;
}
.enroll-field {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.enroll-field-full {
    grid-column: 1 / -1;
}

.enroll-field label {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
}

.enroll-field .optional {
    font-weight: 500;
    color: #64748b;
}

.enroll-field input,
.enroll-field select,
.enroll-field textarea {
    width: 100%;
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    color: #0f172a;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.enroll-field input::placeholder,
.enroll-field textarea::placeholder {
    color: #94a3b8;
}

.enroll-field input:focus,
.enroll-field select:focus,
.enroll-field textarea:focus {
    outline: none;
    border-color: #09515D;
    box-shadow: 0 0 0 3px rgba(9, 81, 93, 0.15);
}

.enroll-field textarea {
    resize: none;
    min-height: 40px;
    width: 100%;
}

.enroll-consent {
    margin-top: 4px;
    padding: 6px 8px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.enroll-consent-label {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    cursor: pointer;
    font-size: 11px;
    color: #475569;
    line-height: 1.3;
}

.enroll-consent-checkbox {
    margin-top: 2px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: #09515D;
}

.enroll-submit-btn {
    margin-top: 6px;
    width: 100%;
    padding: 8px 12px;
    background: #09515D;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(9, 81, 93, 0.35);
}

.enroll-submit-btn:hover {
    background: #0a6573;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(9, 81, 93, 0.4);
}

.enroll-submit-btn i {
    font-size: 16px;
}

.enroll-footer {
    margin-top: 3px;
    text-align: center;
    font-size: 10px;
    color: #64748b;
}
@media (max-width: 560px) {
    .enroll-modal-header {
        padding: 8px 10px 6px;
    }
    .enroll-modal-body {
        padding: 6px 8px 8px;
    }
    .enroll-form-grid {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 4px;
    }
}

/* ===== LAYOUT ===== */
.course-detail-wrapper{
    max-width:min(1600px, 96vw);
    margin:0 auto;
    padding:32px 24px;
    /* Sticky titles sit below .main-header (z-index:1000); without this they hide under the nav */
    --course-sticky-offset: 5.5rem;
}
.course-detail-wrapper .course-body,
.course-detail-wrapper .course-content-layout,
.course-detail-wrapper .course-content-main{
    overflow: visible;
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
    padding:18px;
    border-radius:12px;
    margin-bottom:26px;
}

.skills{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.skill-tag{
    background:rgba(244,123,30,.18);
    color:#ffedd5;
    font-size:13px;
    padding:7px 12px;
    border-radius:999px;
    font-weight:700;
}
/*  */
/* ===== CTA BUTTONS – SEGMENTED / SaaS STYLE ===== */
.cta-buttons{
    display:flex;
    align-items:center;
    gap:20px;
    margin-top:32px;
    flex-wrap:wrap;
}

/* PRIMARY – ENROLL */
.cta-buttons .btn-primary:first-child{
    padding:16px 34px;
    font-size:16px;
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
    font-weight: 1000;
    border:none;
    padding:0;
    font-size:20px;
     font-family: 'Inter', 'Helvetica', 'Arial', sans-serif;
    color: #cbd5e1;
    cursor:pointer;
    position:relative;
}
.hero-description{
    min-height: 240px;
    max-height: 260px;
    overflow-y: auto;
    margin-bottom: 22px;
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
    font-size:14px;
    font-weight:700;
    color:#94a3b8;
    padding:12px 20px;
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
    margin-top:36px;
}
/* PDF: invisible anchor for page break before learn section (no layout shift on screen) */
.pdf-learn-section-start{
    height: 0;
    margin: 0;
    padding: 0;
    overflow: hidden;
    width: 100%;
    pointer-events: none;
}
body.pdf-mode .pdf-learn-section-start{
    break-before: page;
    page-break-before: always;
    -webkit-column-break-before: always;
}
.course-content-layout{
    display: grid !important;
    grid-template-columns: minmax(0, 1fr) 360px !important;
    gap: 24px !important;
    align-items: start !important;
}

.course-content-main{
    min-width: 0;
}

.course-content-sidebar{
    position: sticky !important;
    top: var(--course-sticky-offset, 5.5rem) !important;
    align-self: start !important;
    display: flex !important;
    flex-direction: column !important;
    height: auto !important;
    max-height: calc(100vh - var(--course-sticky-offset, 5.5rem) - 24px) !important;
    overflow: hidden !important;
}

.course-sidebar-heading{
    flex-shrink: 0 !important;
    margin-bottom: 14px !important;
}

.course-sidebar-cards-scroll{
    height: auto !important;
    max-height: calc(100vh - var(--course-sticky-offset, 5.5rem) - 120px) !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding-right: 6px !important;
    flex: unset !important;
    min-height: 0 !important;
}
.course-sidebar-cards-scroll::-webkit-scrollbar{
    width: 10px;
}
.course-sidebar-cards-scroll::-webkit-scrollbar-track{
    background: rgba(15, 23, 42, 0.06);
    border-radius: 6px;
}
.course-sidebar-cards-scroll::-webkit-scrollbar-thumb{
    background: #94a3b8;
    border-radius: 6px;
}
.course-sidebar-cards-scroll::-webkit-scrollbar-thumb:hover{
    background: #64748b;
}
.course-content-main{
    align-self:start;
}
.course-content-main .card{
    margin-bottom:0;
}
/* Sticky section title: stays visible while scrolling topics; scrolls away with the column */
.course-learn-heading{
    position: -webkit-sticky;
    position: sticky;
    top: var(--course-sticky-offset, 5.5rem);
    /* Below .main-header — never stack on top of the navbar */
    z-index: 990;
    /* Wider than the topics card: extend a little left & right */
    margin: 0 -16px 22px;
    margin-bottom: 22px !important;
    padding: 18px 36px 18px 38px;
    box-sizing: border-box;
    line-height: 1.25;
    letter-spacing: -0.025em;
    color: #0f172a !important;
    border-radius: 14px;
    border: 1px solid #b8c0df;
    background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.35) 0%, transparent 42%),
        #d3d9ef;
    box-shadow:
        0 1px 0 rgba(255, 255, 255, 0.85) inset,
        0 2px 4px rgba(15, 23, 42, 0.07),
        0 10px 24px -8px rgba(15, 23, 42, 0.13),
        0 20px 44px -14px rgba(15, 23, 42, 0.15),
        0 32px 64px -24px rgba(15, 23, 42, 0.1);
    -webkit-backdrop-filter: blur(10px);
    backdrop-filter: blur(10px);
}
/* When the main column can’t fit the bar below the nav, release sticky so it won’t slide over the navbar */
.course-learn-heading.course-learn-heading--released{
    position: relative !important;
    top: auto !important;
}
.course-learn-heading::before{
    content: '';
    position: absolute;
    left: 0;
    top: 14px;
    bottom: 14px;
    width: 4px;
    border-radius: 0 6px 6px 0;
    background: linear-gradient(180deg, #28a745 0%, #34ce57 45%, #f59e0b 100%);
}
.course-learn-heading::after{
    content: '';
    display: block;
    height: 3px;
    width: 88px;
    margin-top: 14px;
    border-radius: 999px;
    background: linear-gradient(90deg, #28a745 0%, #34ce57 50%, rgba(245, 158, 11, 0.9) 100%);
    opacity: 0.95;
}
.course-topics-card{
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
    border: 1px solid #b8c0df;
    background:
        linear-gradient(180deg, rgba(255, 255, 255, 0.25) 0%, transparent 50%),
        #d3d9ef;
    box-shadow:
        0 1px 0 rgba(255, 255, 255, 0.9) inset,
        0 6px 16px -8px rgba(15, 23, 42, 0.1),
        0 14px 36px -18px rgba(15, 23, 42, 0.12);
}
.course-sidebar-heading{
    flex-shrink: 0;
    position: relative;
    z-index: 2;
    font-size: clamp(1.25rem, 2vw + 0.5rem, 1.625rem);
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px;
    line-height: 1.25;
    letter-spacing: -0.02em;
    padding: 12px 0 14px;
    background: linear-gradient(180deg, #ffffff 0%, #ffffff 70%, rgba(255,255,255,0.96) 100%);
    -webkit-backdrop-filter: blur(8px);
    backdrop-filter: blur(8px);
}
.course-sidebar-heading::after{
    content: '';
    display: block;
    height: 4px;
    width: 72px;
    margin-top: 12px;
    margin-bottom: 4px;
    border-radius: 999px;
    background: linear-gradient(90deg, #28a745 0%, #34ce57 50%, #f59e0b 100%);
}

/* —— Course details sidebar: premium card styling (scoped; home page unchanged) —— */
.course-content-sidebar .details-courses-grid{
    display: flex !important;
    flex-direction: column !important;
    gap: 18px !important;
}
.course-content-sidebar,
.course-sidebar-cards-scroll,
.course-content-sidebar .details-courses-grid{
    align-self: start !important;
}
/* mobile */
@media (max-width: 900px){
    .course-content-layout{
        grid-template-columns: 1fr !important;
    }

    .course-content-sidebar{
        position: static !important;
        max-height: none !important;
        overflow: visible !important;
    }

    .course-sidebar-cards-scroll{
        max-height: none !important;
        overflow: visible !important;
        padding-right: 0 !important;
    }
}
.course-content-sidebar .details-courses-grid .course-card{
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    padding: 22px 20px 20px;
    background: linear-gradient(165deg, #ffffff 0%, #f8fafc 48%, #f1f5f9 100%);
    border: 1px solid rgba(15, 23, 42, 0.08);
    box-shadow:
        0 1px 2px rgba(15, 23, 42, 0.04),
        0 12px 32px -8px rgba(15, 23, 42, 0.12),
        0 0 0 1px rgba(255, 255, 255, 0.8) inset;
    transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.35s ease,
                border-color 0.25s ease;
}
.course-content-sidebar .details-courses-grid .course-card::before{
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #28a745 0%, #34ce57 50%, #f59e0b 100%);
    opacity: 0.95;
    border-radius: 16px 16px 0 0;
}
.course-content-sidebar .details-courses-grid .course-card:hover{
    transform: translateY(-5px);
    border-color: rgba(40, 167, 69, 0.35);
    box-shadow:
        0 4px 6px rgba(15, 23, 42, 0.05),
        0 20px 40px -12px rgba(15, 23, 42, 0.18),
        0 0 0 1px rgba(40, 167, 69, 0.12) inset;
}
.course-content-sidebar .details-courses-grid .course-card h4{
    font-size: 1.15rem;
    font-weight: 800;
    line-height: 1.35;
    color: #0f172a;
    margin-bottom: 10px;
    letter-spacing: -0.02em;
}
.course-content-sidebar .details-courses-grid .course-card > p:first-of-type{
    font-size: 14px;
    line-height: 1.55;
    color: #475569;
    margin-bottom: 12px;
    padding: 10px 12px;
    background: rgba(255, 255, 255, 0.75);
    border-radius: 10px;
    border: 1px solid rgba(15, 23, 42, 0.06);
    min-height: auto;
    max-height: none;
}
.course-content-sidebar .details-courses-grid .level-text{
    display: inline-flex;
    align-items: center;
    width: fit-content;
    font-size: 12px !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #166534 !important;
    margin-bottom: 10px !important;
    padding: 5px 10px;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.14), rgba(40, 167, 69, 0.06));
    border-radius: 999px;
    border: 1px solid rgba(40, 167, 69, 0.22);
}
.course-content-sidebar .details-courses-grid .course-info{
    font-size: 13px !important;
    color: #64748b !important;
    margin-bottom: 6px !important;
    line-height: 1.45;
}
.course-content-sidebar .details-courses-grid .course-info strong{
    color: #334155;
    font-weight: 700;
}
.course-content-sidebar .details-courses-grid .course-info:last-of-type{
    margin-bottom: 14px !important;
}
.course-content-sidebar .details-courses-grid .course-actions{
    margin-top: 4px;
    gap: 10px;
}
.course-content-sidebar .details-courses-grid .course-actions .btn-details{
    border-radius: 10px;
    font-weight: 800;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 14px rgba(40, 167, 69, 0.28);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.course-content-sidebar .details-courses-grid .course-actions .btn-details:hover{
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(40, 167, 69, 0.35);
}
.details-courses-grid .course-actions .btn-inquiry{
    font-size:15px;
    padding:11px 20px;
    font-weight:700;
    border-radius:10px;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.course-content-sidebar .details-courses-grid .course-actions .btn-inquiry:hover{
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
}
.details-courses-grid .course-actions .btn-inquiry i{
    display:none;
}
@media (prefers-reduced-motion: reduce){
    .course-content-sidebar .details-courses-grid .course-card,
    .course-content-sidebar .details-courses-grid .course-actions .btn-details,
    .course-content-sidebar .details-courses-grid .course-actions .btn-inquiry{
        transition: none;
    }
    .course-content-sidebar .details-courses-grid .course-card:hover,
    .course-content-sidebar .details-courses-grid .course-actions .btn-details:hover,
    .course-content-sidebar .details-courses-grid .course-actions .btn-inquiry:hover{
        transform: none;
    }
}

.card{
    background:#fff;
    border-radius:18px;
    padding:32px 28px;
    margin-bottom:26px;
    box-shadow:0 12px 28px rgba(15,23,42,.08);
}

.sec-title{
    font-size: clamp(1.75rem, 2.5vw + 1.5rem, 42px);
    font-weight:800;
    margin-bottom:16px;
}

/* ===== RICH TEXT ===== */
.rich-text{
    font-size:17px;
    line-height:1.8;
    color:#374151;
    max-width:100%;
}

/* ===== TOPICS ===== */
.topics{
    display:flex;
    flex-direction:column;
    gap:18px;
}

.topic-item {
    margin-bottom: 20px;
              /* IMPORTANT: padding hatao */
}

.topic-title {
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #222;
      font-family: 'Poppins', 'Segoe UI', sans-serif;
}
/* ✅ GLOBAL (har screen ke liye) */
.topic-desc {
    font-size: 14px;
    background-color: #f5f5f5;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #e0e0e0;
    margin-top: 6px;
}
/* Course detail: description box under each topic title */
.course-topics-card .topic-desc {
    background-color: #d3d9ef;
    border: 1px solid #b8c0df;
}

.muted{
    color:#6b7280;
    font-size:17px;
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
    height:auto;

    max-width:95vw;
    max-height:98vh;
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
    .course-detail-wrapper {
        padding: 28px 20px;
    }

    .hero-snapshot {
        padding: 36px 32px;
        gap: 32px;
    }

    .hero-title {
        font-size: clamp(1.5rem, 4vw + 1rem, 36px);
    }

    .course-hero {
        padding: 28px;
        gap: 28px;
    }

    .course-hero-img {
        min-height: 320px;
    }

    .snapshot-card {
        padding: 26px 22px;
    }

    .snapshot-price {
        font-size: 34px;
    }

    .card {
        padding: 26px 22px;
    }

    .sec-title,
    .snapshot-card h4 {
        font-size: clamp(1.5rem, 4vw + 1rem, 36px);
    }

    .rich-text {
        font-size: 16px;
    }
}

/* TABLET */
@media (max-width: 900px) {
    .course-hero {
        grid-template-columns: 1fr;
    }

    .hero-snapshot {
        grid-template-columns: 1fr;
        padding: 28px 24px;
        gap: 28px;
    }

    .hero-snapshot .snapshot-card {
        margin-top: 0;
    }

    .course-hero-img {
        min-height: 280px;
    }

    .hero-title,
    .sec-title,
    .snapshot-card h4 {
        font-size: clamp(1.4rem, 4vw + 1rem, 32px);
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

    .hero-description {
        min-height: 200px;
        max-height: 220px;
    }
}

/* MOBILE */
@media (max-width: 640px) {
    .course-detail-wrapper {
        padding: 20px 16px;
    }

    .hero-snapshot {
        padding: 22px 18px;
        gap: 22px;
        border-radius: 16px;
    }

    .course-hero {
        padding: 22px;
        border-radius: 14px;
    }

    .course-hero-img {
        min-height: 220px;
    }

    .hero-pills span {
        font-size: 12px;
        padding: 7px 12px;
    }

    .hero-description {
        min-height: 180px;
        max-height: 200px;
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
        padding: 14px 0;
        font-size: 15px;
    }

    .cta-buttons .btn-primary {
        font-size: 13px;
    }

    .btn-secondary {
        font-size: 13px;
        padding: 10px 16px;
    }

    .card {
        padding: 20px 18px;
        border-radius: 14px;
    }

    .hero-title,
    .sec-title,
    .snapshot-card h4 {
        font-size: clamp(1.25rem, 5vw + 1rem, 28px);
    }

    .rich-text {
        font-size: 16px;
    }

   .topic-item {
    margin-bottom: 20px;   /* topics ke darmiyan gap */
}

   .topic-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;   /* title aur box ke darmiyan gap */
    color: #222;
}
    .snapshot-card {
        padding: 22px 18px;
    }

    .snapshot-price {
        font-size: 32px;
    }

    .snapshot-list li {
        font-size: 16px;
    }

    .snapshot-card-actions .btn-snapshot {
        padding: 10px 14px;
        font-size: 14px;
    }
}

/* VERY SMALL PHONES */
@media (max-width: 420px) {
    .course-detail-wrapper {
        padding: 16px 12px;
    }

    .hero-title,
    .sec-title,
    .snapshot-card h4 {
        font-size: 22px;
    }

    .course-hero-img {
        min-height: 200px;
    }

    .meta-pill {
        font-size: 11px;
    }

    .topic-title {
        font-size: 15px;
    }

    .course-topics-card .topic-desc {
        font-size: 14px;
        background-color: #d3d9ef !important;
        padding: 12px 14px !important;
        border-radius: 10px !important;
        border: 1px solid #b8c0df !important;
        margin-top: 6px;
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
    border-radius:20px;
    padding:48px 44px;
    display:grid;
    grid-template-columns:1.2fr .8fr;
    gap:44px;
    align-items: stretch;
}

.hero-right {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

/* LEFT */
.hero-badge {
    display:inline-flex !important;
    align-items:center !important;
    background:#2b8d40 !important;
    color:#F47B1E !important;
    font-size:13px !important;
    font-weight:700 !important;
    padding:8px 16px !important;
    border-radius:999px !important;
    margin-bottom:14px !important;
    line-height:1 !important;
    width:fit-content !important;
}
.snapshot-card {
    pointer-events: none;
}

.snapshot-card-actions {
    pointer-events: auto;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #e5e7eb;
}

.snapshot-card-actions .btn-snapshot {
    width: 100%;
    padding: 10px 14px;
    font-size: 30px;
    font-weight: 900;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.snapshot-card-actions .btn-inquiry.btn-snapshot {
    background: var(--green-main);
    color: var(--black);
}

.snapshot-card-actions .btn-primary.btn-snapshot {
    background: #eef7f6;
    color: #09515D;
    border: 1px solid rgba(40,167,69,.4);
}

.snapshot-card-actions .btn-primary.btn-snapshot:hover {
    background: var(--green-main);
    color: var(--black);
}


.hero-title{
    font-size:clamp(1.75rem, 2.5vw + 1.5rem, 42px);
    font-weight:900;
    margin-bottom:20px;
    color:#09515D;
    line-height:1.2;
}

.hero-pills{
    display:flex;
    flex-wrap:wrap;
    gap:14px;
    margin-bottom:24px;
    color:#09515D;
}

.hero-pills span{
    background:#ffffff;
    border:1px solid #e5e7eb;
    padding:9px 16px;
    border-radius:999px;
    font-size:14px;
    font-weight:600;
    color:#09515D;
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
    padding:8px 6px;
    font-size:21px;
    font-weight:800;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
.btn-solid i{
    color: #000 !important;
}

.btn-solid:hover i{ 
    color: #000 !important;

    transform:translateY(-2px);


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

/* RIGHT CARD – compact so bottom aligns with left column */
.snapshot-card{
    background: #ffffff;
    border-radius:18px;
    padding:22px 24px;
    margin-top:0;
    box-shadow:0 14px 36px rgba(15,23,42,.12);
}

.snapshot-card h4{
    font-size: clamp(1.75rem, 2.5vw + 1.5rem, 42px);
    font-weight:800;
    margin-bottom:10px;
    color:#09515D;
}

.snapshot-price{
    font-size:34px;
    font-weight:900;
    color: #09515D;
    margin-bottom:10px;
}

.snapshot-list{
    list-style:none;
    padding:0;
    margin:0 0 14px;
}

.snapshot-list li{
    font-size:17px;
    font-weight:600;
    color:#334155;
    margin-bottom:6px;
}

.snapshot-card .full{
    width:100%;
}

/* compact skills inside snapshot so card height aligns with left */
.snapshot-card .skills-wrap {
    padding: 12px;
    margin-bottom: 12px;
    margin-left: 0 !important;
}
.snapshot-card .skills-wrap .sec-title {
    font-size: 1rem;
    margin-bottom: 8px;
}
.snapshot-card .skill-tag {
    font-size: 12px;
    padding: 5px 10px;
}

/* MOBILE */
@media(max-width:900px){
    .course-detail-wrapper{
        --course-sticky-offset: 4.75rem;
    }
    .course-content-layout{
    display:flex !important;
    align-items:flex-start !important;
}
    .course-learn-heading{
        margin-left: -10px;
        margin-right: -10px;
        padding-left: 32px;
        padding-right: 30px;
    }
    .hero-snapshot{
        grid-template-columns:1fr;
        padding:28px;
    }
    .hero-right {
        justify-content: flex-start;
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
/* ===============================
   PDF SAFE LAYOUT (CRITICAL)
   =============================== */

body.pdf-mode .pdf-wrapper{
    max-width: 100% !important;
    width: 100% !important;
    padding: 20px !important;
    margin: 0 !important;
    overflow: visible !important;
    /* Same fonts as course page: body stack from components/styles.blade.php */
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
}
body.pdf-mode .pdf-wrapper *{
    font-family: inherit !important;
}
body.pdf-mode .pdf-wrapper .bi{
    font-family: bootstrap-icons !important;
}

/* PDF: only course details — hide “You may also like” / related course cards */
body.pdf-mode .course-content-sidebar{
    display: none !important;
}

/* CTA card: buttons are hidden in PDF but the .card shell kept a border — hide whole block */
body.pdf-mode .course-cta-card{
    display: none !important;
}
body.pdf-mode .course-content-layout{
    grid-template-columns: 1fr !important;
    gap: 0 !important;
}

body.pdf-mode .course-learn-heading{
    position: static !important;
    box-shadow: none !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
    padding: 0 !important;
}
body.pdf-mode .course-learn-heading::before,
body.pdf-mode .course-learn-heading::after{
    display: none !important;
}
body.pdf-mode .course-learn-heading{
    break-inside: avoid !important;
    page-break-inside: avoid !important;
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

/* cards (topics card is plain in PDF — see .course-content-main .course-topics-card below) */
body.pdf-mode .card:not(.course-topics-card) {
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
    background-color: #28a745 !important;      /* 👈 keeps original color */
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
    font-size:25px;
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


/* ==========================================
   COLOR OVERRIDE ONLY – GREEN / BLACK / WHITE
   DO NOT CHANGE ANY STRUCTURE OR STYLES
   ========================================== */

:root{
  --green-main:#28a745;
  --green-dark:#218838;
  --black:#000000;
  --white:#ffffff;
}

/* ===== HERO ===== */
.course-hero,
.hero-snapshot{
  background:#ffffff !important;
  color:var(--black) !important;
}

.hero-title,
.course-title,
.sec-title,
.snapshot-card h4,
.launch-title{
  color:var(--black) !important;
}

/* ===== BADGE ===== */
.hero-badge{
  background:rgba(40,167,69,.15) !important;
  color:var(--green-main) !important;
}

/* ===== ICON COLORS ===== */
.bi{
  color:var(--green-main) !important;
}

/* ===== PILLS ===== */
.hero-pills span,
.meta-pill,
.info-pill{
  background:#ffffff !important;
  border-color:rgba(40,167,69,.4) !important;
  color:var(--black) !important;
}

/* ===== SKILLS ===== */
.skills-wrap{
  background:rgba(40,167,69,.08) !important;
}
.skill-tag{
  background:rgba(40,167,69,.15) !important;
  color:var(--black) !important;
}

/* ===== SNAPSHOT ===== */
.snapshot-price{
  color:var(--green-main) !important;
}

/* ===== BUTTONS – PRIMARY ===== */
.btn-primary,
.btn-solid,
.reg-submit,
.submit-btn,
.success-btn,
.btn-inquiry{
  background:var(--green-main) !important;
  border-color:var(--green-main) !important;
  color:var(--black) !important;
}

.btn-primary:hover,
.btn-solid:hover,
.reg-submit:hover,
.submit-btn:hover,
.success-btn:hover,
.btn-inquiry:hover{
  background:var(--green-dark) !important;
  color:var(--black) !important;
}

/* ===== BUTTONS – OUTLINE ===== */
.btn-outline,
.btn-secondary{
  background:#ffffff !important;
  border-color:var(--green-main) !important;
  color:var(--green-main) !important;
}

.btn-outline:hover,
.btn-secondary:hover{
  background:var(--green-main) !important;
  color:var(--black) !important;
}

/* ===== CTA UNDERLINES ===== */
.cta-buttons .btn-primary::after,
.cta-buttons .btn-secondary::after{
  background:var(--green-main) !important;
}

/* ===== MODALS ===== */
.modal-overlay{
  background:rgba(0,0,0,.6) !important;
}

.enroll-header .modal-title,
.reg-title{
  color:var(--black) !important;
}

/* ===== FORMS ===== */
.reg-group label{
  color:var(--black) !important;
}

.reg-group input:focus,
.reg-group textarea:focus,
.reg-group select:focus{
  border-color:var(--green-main) !important;
  box-shadow:0 0 0 3px rgba(40,167,69,.2) !important;
}

/* ===== SUCCESS MODAL ===== */
.success-box{
  border-top-color:var(--green-main) !important;
}
.success-title{
  color:var(--black) !important;
}
.success-text{
  color:#333 !important;
}

/* ===== LAUNCH ROW ===== */
.launch-row,
.launch-row.pro{
  background:#ffffff !important;
  border-color:rgba(40,167,69,.3) !important;
}

/* ===== PDF MODE ===== */
body.pdf-mode .course-hero,
body.pdf-mode .hero-snapshot{
  background:#ffffff !important;
  color:#000 !important;
}

/* PDF: full course description (no scroll box) + same body typography as topics */
/* Keep course-hero description adjustments */
body.pdf-mode .course-hero .hero-description {
    min-height: 0 !important;
    max-height: none !important;
    overflow: visible !important;
}

/* Force font-size for rich-text content */
body.pdf-mode .hero-description .rich-text,
body.pdf-mode .hero-description .rich-text p,
body.pdf-mode .hero-description .rich-text li,
body.pdf-mode .hero-description .rich-text ul,
body.pdf-mode .hero-description .rich-text ol {
    font-size: 18px !important;   /* change as needed */
    line-height: 1.8 !important;
}

body.pdf-mode .course-hero .hero-description .rich-text,
body.pdf-mode .course-content-main .course-topics-card .topic-desc,
body.pdf-mode .course-content-main .topic-desc{
    font-family: inherit !important;
    font-size: 17px !important;
    line-height: 1.8 !important;
    color: #374151 !important;
    font-weight: 400 !important;
}
body.pdf-mode .course-hero .hero-title{
    page-break-after: avoid !important;
    break-after: avoid !important;
}
body.pdf-mode .course-hero .hero-description .rich-text p,
body.pdf-mode .course-hero .hero-description .rich-text li{
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    orphans: 3 !important;
    widows: 3 !important;
}

/* PDF: “What you will learn?” — match .hero-title (course name) size & weight */
body.pdf-mode .course-content-main .course-learn-heading{
    color: #000000 !important;
    font-size: clamp(1.75rem, 2.5vw + 1.5rem, 42px) !important;
    font-weight: 900 !important;
    margin: 1.25rem 0 20px !important;
    padding: 0 !important;
    letter-spacing: normal !important;
    line-height: 1.2 !important;
}
body.pdf-mode .course-content-main .course-topics-card{
    background: #ffffff !important;
    border: none !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 0 !important;
    margin: 0 !important;
    /* Allow multiple topics across pages; avoid splits inside each topic / paragraph */
    page-break-inside: auto !important;
    break-inside: auto !important;
}
body.pdf-mode .course-content-main .topics{
    gap: 0 !important;
    display: block !important;
}
body.pdf-mode .course-content-main .topic-item{
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 0 0 0.85rem !important;
    margin: 0 0 0.65rem !important;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
}
/* Topic lines (numbered): bold + larger than description, always smaller than .course-learn-heading (max 42px) */
body.pdf-mode .course-content-main .topic-title{
    font-family: inherit !important;
    font-size: clamp(1.125rem, 0.85vw + 1rem, 1.5rem) !important;
    line-height: 1.35 !important;
    font-weight: 700 !important;
    color: #111827 !important;
    margin: 0 0 0.35rem !important;
    padding: 0 !important;
    border: none !important;
    page-break-after: avoid !important;
    break-after: avoid !important;
}
body.pdf-mode .course-content-main .course-topics-card .topic-desc,
body.pdf-mode .course-content-main .topic-desc{
    background: transparent !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 0 !important;
    margin: 0 0 0.15rem !important;
}
body.pdf-mode .course-content-main .topic-desc p{
    font-size: inherit !important;
    line-height: inherit !important;
    margin: 0 0 0.85em !important;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    orphans: 3 !important;
    widows: 3 !important;
}
body.pdf-mode .course-content-main .topic-desc p:last-child{
    margin-bottom: 0 !important;
}
body.pdf-mode .course-content-main .topic-desc ul,
body.pdf-mode .course-content-main .topic-desc ol{
    font-size: inherit !important;
    line-height: inherit !important;
    margin: 0.35em 0 0.5em !important;
    padding-left: 1.25em !important;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
}
body.pdf-mode .course-content-main .topic-desc li{
    font-size: inherit !important;
    line-height: inherit !important;
    margin-bottom: 0.25em !important;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
}
body.pdf-mode .course-content-main .topic-desc tr{
    page-break-inside: avoid !important;
    break-inside: avoid !important;
}
body.pdf-mode .course-content-main .topic-desc table{
    border-collapse: collapse !important;
    width: 100% !important;
    margin: 0.4em 0 !important;
    font-size: inherit !important;
}
body.pdf-mode .course-content-main .topic-desc th,
body.pdf-mode .course-content-main .topic-desc td{
    border: none !important;
    padding: 0.2em 0.5em 0.2em 0 !important;
    vertical-align: top !important;
}
body.pdf-mode .course-content-main .topic-desc th{
    font-weight: 700 !important;
    background: transparent !important;
}

/* ===== SNAPSHOT BUTTONS – COMPLETE FIX ===== */

/* Common style (ALL 3 buttons) */
.snapshot-card-actions .btn-snapshot {
    font-weight: 800 !important;
    font-size: 20px !important;
    border-radius: 10px !important;
    letter-spacing: 0.3px;
}

/* ❌ Remove icons from ALL buttons */
.snapshot-card-actions .btn-snapshot i {
    display: none !important;
}

/* 🟢 Inquiry button */
.snapshot-card-actions .btn-inquiry.btn-snapshot {
    background: #1ba302 !important;   /* parrot green */
    color: #000 !important;
    border: none !important;
}

/* 🟢 Download + Print buttons */
.snapshot-card-actions button.btn-primary.btn-snapshot,
.snapshot-card-actions a.btn-primary.btn-snapshot {
    background: #1ba302  !important;   /* SAME parrot green */
    color: #000 !important;
    border: none !important;
}

/* ✨ Hover (ALL buttons) */
.snapshot-card-actions .btn-snapshot:hover {
    background: #2ecc71 !important;   /* thora darker green */
    color: #000 !important;
    transform: translateY(-2px);
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

    /* static flow so page-break-before works inside the course (absolute blocks breaks) */
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

    /* Match PDF: “What you will learn ?” block starts on a new page */
    body.course-print-active .pdf-learn-section-start {
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
.ql-snow .ql-picker.ql-size .ql-picker-item::before {
    content: attr(data-value);
}

.ql-snow .ql-picker.ql-size .ql-picker-label::before {
    content: attr(data-value);
}
/* ===== FINAL FIX: REMOVE TOP/BOTTOM SPACE IN TOPIC DESC ===== */

.course-topics-card .topic-desc,
.course-topics-card .topic-desc.rich-text,
.course-topics-card .topic-desc.ql-editor {
    padding: 8px 12px !important;
    margin: 0 !important;
    min-height: 0 !important;
    height: auto !important;
    line-height: 1.5 !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
}

.course-topics-card .topic-desc > *:first-child,
.course-topics-card .topic-desc.rich-text > *:first-child,
.course-topics-card .topic-desc.ql-editor > *:first-child {
    margin-top: 0 !important;
}

.course-topics-card .topic-desc > *:last-child,
.course-topics-card .topic-desc.rich-text > *:last-child,
.course-topics-card .topic-desc.ql-editor > *:last-child {
    margin-bottom: 0 !important;
}

.course-topics-card .topic-desc p,
.course-topics-card .topic-desc.rich-text p,
.course-topics-card .topic-desc.ql-editor p {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.course-topics-card .topic-desc ul,
.course-topics-card .topic-desc ol,
.course-topics-card .topic-desc.rich-text ul,
.course-topics-card .topic-desc.rich-text ol,
.course-topics-card .topic-desc.ql-editor ul,
.course-topics-card .topic-desc.ql-editor ol {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

.course-topics-card .topic-desc li,
.course-topics-card .topic-desc.rich-text li,
.course-topics-card .topic-desc.ql-editor li,
.course-topics-card .topic-desc h1,
.course-topics-card .topic-desc h2,
.course-topics-card .topic-desc h3,
.course-topics-card .topic-desc h4,
.course-topics-card .topic-desc h5,
.course-topics-card .topic-desc h6,
.course-topics-card .topic-desc.rich-text h1,
.course-topics-card .topic-desc.rich-text h2,
.course-topics-card .topic-desc.rich-text h3,
.course-topics-card .topic-desc.rich-text h4,
.course-topics-card .topic-desc.rich-text h5,
.course-topics-card .topic-desc.rich-text h6,
.course-topics-card .topic-desc.ql-editor h1,
.course-topics-card .topic-desc.ql-editor h2,
.course-topics-card .topic-desc.ql-editor h3,
.course-topics-card .topic-desc.ql-editor h4,
.course-topics-card .topic-desc.ql-editor h5,
.course-topics-card .topic-desc.ql-editor h6 {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

/* ===============================
   INQUIRY MODAL – MATCH ENROLL SIZE EXACTLY
   =============================== */
@keyframes inquiryModalIn {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(-10px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.inquiry-modal-overlay {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 8px;
    background-color: rgba(0,0,0,0.6);
    overflow: hidden;
}

.inquiry-modal-overlay[style*="flex"] .inquiry-modal-box {
    animation: inquiryModalIn 0.3s ease-out;
}

.inquiry-modal-box {
    width: 100%;
    max-width: 540px;
    max-height: 98vh;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 24px 48px rgba(9,81,93,0.2), 0 0 0 1px rgba(9,81,93,0.08);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
}

/* close button */
.inquiry-close-btn {
    position: absolute;
    right: 16px;
    top: 10px;
    width: 34px;
    height: 34px;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    z-index: 2;
    transition: background 0.2s, color 0.2s, transform 0.2s;
}

.inquiry-close-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    transform: scale(1.05);
}

/* header */
.inquiry-modal-header {
    background: linear-gradient(180deg, #09515D 0%, #0a6573 100%);
    color: #fff;
    padding: 10px 14px 8px;
    text-align: center;
    position: relative;
    flex-shrink: 0;
}

.inquiry-modal-title {
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 1px;
    letter-spacing: -0.02em;
    color: #fff;
    line-height: 1.2;
}

.inquiry-modal-subtitle {
    font-size: 11px;
    opacity: 0.9;
    margin: 0 0 6px;
    font-weight: 500;
    line-height: 1.35;
}

/* pills */
.inquiry-info-pills {
    justify-content: center;
    gap: 8px;
    margin-top: 0;
}

.inquiry-modal-header .info-pill {
    padding: 3px 8px;
    font-size: 10px;
    background: rgba(255, 255, 255, 0.2) !important;
    color: #fff !important;
    border: 1px solid rgba(255, 255, 255, 0.3) !important;
}

.inquiry-modal-header .bi {
    color: #fff !important;
}

/* body */
.inquiry-modal-body {
    padding: 8px 10px 10px;
    overflow: hidden;
    flex: 1 1 auto;
}

.inquiry-form {
    padding-bottom: 4px;
}

/* grid */
.inquiry-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    width: 100%;
    padding: 6px;
    box-sizing: border-box;
}

.inquiry-field {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.inquiry-field-full {
    grid-column: 1 / -1;
}

/* labels */
.inquiry-field label {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}

.inquiry-field .optional {
    font-weight: 500;
    color: #64748b;
}

/* inputs */
.inquiry-field input,
.inquiry-field textarea {
    width: 100%;
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    color: #0f172a;
    background: #fff;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.inquiry-field input::placeholder,
.inquiry-field textarea::placeholder {
    color: #94a3b8;
}

.inquiry-field input:focus,
.inquiry-field textarea:focus {
    outline: none;
    border-color: #09515D;
    box-shadow: 0 0 0 3px rgba(9, 81, 93, 0.15);
}

/* exact compact heights like enroll */
.inquiry-field textarea {
    resize: none;
    min-height: 40px;
    width: 100%;
}

/* consent */
.inquiry-consent {
    margin-top: 4px;
    padding: 6px 8px;
    background: #f8fafc;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
}

.inquiry-consent-label {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    cursor: pointer;
    font-size: 11px;
    color: #475569;
    line-height: 1.3;
}

.inquiry-consent-checkbox {
    margin-top: 2px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    accent-color: #09515D;
}

/* button */
.inquiry-submit-btn {
    margin-top: 6px;
    width: 100%;
    padding: 8px 12px;
    background: #09515D;
    color: #fff;
    font-size: 13px;
    font-weight: 800;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(9, 81, 93, 0.35);
}

.inquiry-submit-btn:hover {
    background: #0a6573;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(9, 81, 93, 0.4);
}

.inquiry-submit-btn i {
    display: none;
}

.inquiry-footer {
    margin-top: 3px;
    text-align: center;
    font-size: 10px;
    color: #64748b;
}

/* mobile */
@media (max-width: 560px) {
    .inquiry-modal-header {
        padding: 8px 10px 6px;
    }

    .inquiry-modal-body {
        padding: 6px 8px 8px;
    }

    .inquiry-form-grid {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 4px;
    }

    .inquiry-modal-box {
        max-width: 95%;
        min-width: unset;
    }
}

/* ===============================
   MATCH ENROLL VERTICAL SPACING
   =============================== */

/* more space between rows like enroll */
#inquiryModal .enroll-form-grid{
    row-gap: 10px;
}

/* add breathing space between sections */
#inquiryModal .enroll-field{
    margin-bottom: 2px;
}

/* textarea height same feel as enroll */
#inquiryModal textarea{
    min-height: 70px;
}

/* more space before consent */
#inquiryModal .enroll-consent{
    margin-top: 10px;
}

/* more space before button */
#inquiryModal .enroll-submit-btn{
    margin-top: 12px;
}

/* footer spacing like enroll */
#inquiryModal .enroll-footer{
    margin-top: 6px;
}
/* Match inquiry modal height with enroll modal */
#inquiryModal .enroll-form-grid{
    margin-bottom: 45px;
}

#inquiryModal .enroll-consent{
    margin-top: 0;
}


</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

 <script>
function openEnrollModal(courseTitle, courseId = null){
    document.body.style.overflow = 'hidden';
    document.getElementById('enrollModal').style.display = 'flex';

    // Title
    document.getElementById('selectedCourse').innerText =
        'Enroll in "' + courseTitle + '"';

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

/* PRINT — same appearance as PDF: pdf-mode typography/layout + margins + footer link */
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

// pdf — clickable site link centered at bottom of every page (jsPDF layer on top of canvas pages)
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
            mode: ['css', 'legacy'],
            before: ['.pdf-learn-section-start'],
            avoid: [
                '.course-learn-heading',
                '.topic-title',
                '.topic-item',
                '.topic-desc p',
                '.topic-desc li',
                '.hero-description .rich-text p',
                '.hero-description .rich-text li'
            ]
        }
    };

    function runHtml2Pdf() {
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

    /* Wait for Inter/Poppins (same as course page) so html2canvas captures correct faces */
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(runHtml2Pdf).catch(runHtml2Pdf);
    } else {
        setTimeout(runHtml2Pdf, 200);
    }
}


</script>


<script>
function openInquiryModal(courseTitle, courseId = null, level = '', duration = ''){
    document.body.style.overflow = 'hidden';
    document.getElementById('inquiryModal').style.display = 'flex';

    document.getElementById('inquiryTitle').innerText =
        'Inquiry about "' + courseTitle + '"';

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
