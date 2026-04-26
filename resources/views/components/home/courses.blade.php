<!-- Courses Section with Sidebar -->
<section class="courses-section" id="courses">
    <div class="container">
        <h2>Courses</h2>
        <div class="courses-layout">
            <!-- Sidebar Filters -->
            <aside class="filters-sidebar" id="filtersSidebar">
                <h4>Filters</h4>
                <div class="filter-group">
                    <h5>Filter by Category:</h5>
                    <label class="filter-radio">
                        <input type="radio" name="category" value="all" checked onchange="setCategory(this.value)">
                        <span>All Categories</span>
                    </label>
                    @foreach($categories as $category)
                        <label class="filter-radio">
                            <input type="radio" name="category" value="{{ $category->id }}" onchange="setCategory(this.value)">
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach

                    <h5>Filter by Level:</h5>
                    <label class="filter-radio">
                        <input type="radio" name="level" value="all" checked onchange="setLevel('all')">
                        <span>All Levels</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="level" value="beginner" onchange="setLevel(this.value)">
                        <span>Beginner</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="level" value="intermediate" onchange="setLevel(this.value)">
                        <span>Intermediate</span>
                    </label>
                    <label class="filter-radio">
                        <input type="radio" name="level" value="advanced" onchange="setLevel(this.value)">
                        <span>Advanced</span>
                    </label>
                </div>
  <button type="button"
class="btn-free-courses"
onclick="filterFreeCourses(event)">
Free Courses
</button>
            </aside>

            <!-- No Courses Message -->
            <div id="noCoursesMessage" style="display:none;text-align:center;padding:40px 20px;color:#6b7280;font-size:17px;font-weight:500;">
                <i class="bi bi-info-circle"></i>
                <span id="noCoursesText">No courses available.</span>
            </div>

            <!-- Course Cards Grid -->
            <div class="courses-grid" id="coursesGrid">
                @foreach($courses as $course)
                    @php
                        $launchDate = '';
                        if ($course->nextLaunch && $course->nextLaunch->launch_date) {
                            $launchDate = \Carbon\Carbon::parse($course->nextLaunch->launch_date)->toDateString();
                        }
                    @endphp
                    <div class="course-card"
                         data-level="{{ strtolower($course->level) }}"
                         data-category="{{ $course->training_category_id }}"
                         data-free="{{ $course->price == 0 ? 'yes' : 'no' }}"
                         data-launch="{{ $launchDate }}">
                        <h4>{{ $course->title }}</h4>
                        <p>{!! Str::limit(strip_tags($course->description), 160) !!}</p>
                        <p class="level-text"><strong>{{ ucfirst($course->level) }}</strong></p>
                        <p class="course-info">
                            <strong>Duration:</strong> {{ $course->duration ?? 'N/A' }}<br>
                            <strong>Charges:</strong> @if($course->price == 0) Free @else £{{ number_format($course->price, 2) }} @endif
                        </p>
                        <p class="course-info">Mode: Online / Virtual</p>
                        <div class="course-actions">
                            <a href="{{ route('show', $course->id) }}" class="btn-primary btn-details" style="text-decoration:none;">Details</a>
                            <button class="btn-outline btn-inquiry"
                                    onclick="openInquiryModal('{{ $course->title }}', {{ $course->id }}, '{{ $course->level ?? '' }}', '{{ $course->duration ?? '' }}')">Inquiry</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- INQUIRY MODAL -->
<div id="inquiryModal" class="modal-overlay inquiry-modal-overlay">
    <div class="inquiry-modal-box">
        <!-- Header -->
        <div class="inquiry-modal-header">
            <h3 id="inquiryTitle" class="inquiry-modal-title">Inquiry</h3>
            <p class="inquiry-modal-subtitle">We’ll get back to you within 24 hours</p>
            <div class="enroll-info inquiry-info-pills" id="inquiryInfo" style="display:none;">
                <span class="info-pill"><i class="bi bi-clock"></i><span id="inquiryDuration"></span></span>
                <span class="info-pill"><i class="bi bi-bar-chart-steps"></i><span id="inquiryLevelText"></span></span>
            </div>
            <button type="button" style="color:black;" class="inquiry-close-btn" onclick="closeInquiryModal()" aria-label="Close">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Scrollable Body -->
        <div class="inquiry-modal-body">
            <form method="POST" action="{{ route('course.inquiry') }}" class="inquiry-form">
                @csrf
                <input type="hidden" name="course_title" id="inquiryCourseTitle">
                <input type="hidden" name="course_id" id="inquiryCourseId">
                <input type="hidden" name="level" id="inquiryLevel">

                <div class="inquiry-form-grid">
                    <div class="inquiry-field"><label for="inquiryName">Full Name</label><input type="text" name="name" id="inquiryName" placeholder="John Smith" required></div>
                    <div class="inquiry-field"><label for="inquiryEmail">Email</label><input type="email" name="email" id="inquiryEmail" placeholder="john@example.com" required></div>
                    <div class="inquiry-field inquiry-field-full"><label for="inquiryPhone">Phone <span class="optional">(Optional)</span></label><input type="tel" name="phone" id="inquiryPhone" placeholder="+1 (555) 000-0000"></div>
                    <div class="inquiry-field inquiry-field-full"><label for="inquiryMessage">Your message</label><textarea name="message" id="inquiryMessage" rows="4" placeholder="Tell us your questions about this course, schedule, or pricing..." required></textarea></div>
                </div>

                <div class="inquiry-consent">
                    <label class="inquiry-consent-label">
                        <input type="checkbox" name="consent" required class="inquiry-consent-checkbox">
                        <span class="inquiry-consent-text">
                            I confirm the information provided is accurate and agree that Imperial Tuitions may use it for educational and enrollment purposes. My data will not be shared with third parties.
                        </span>
                    </label>
                </div>

                <button type="submit" class="inquiry-submit-btn">📧 Send Inquiry</button>
                <p class="inquiry-footer">We usually respond within 24 hours.</p>
            </form>
        </div>
    </div>
</div>

<!-- SUCCESS MODAL -->
<div id="successModal" class="modal-overlay">
    <div class="success-box">
        <h3 class="success-title">Message Sent Successfully</h3>
        <p class="success-text">
            Thank you for reaching out to <strong>Imperial Tuitions</strong>.<br>
            Our team will contact you shortly.
        </p>
        <button class="success-btn" onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<!-- STYLES -->
<style>
#courses {
    scroll-margin-top: 100px;
}

/* overlay */
.modal-overlay {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    background: rgba(0,0,0,0.6);
}

/* modal box */
.inquiry-modal-box {
    width: 90%;
    min-width: 900px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    border-radius: 16px;
    background: #fff;
    overflow: hidden;
    position: relative;
    box-shadow: 0 24px 48px rgba(9,81,93,0.2), 0 0 0 1px rgba(9,81,93,0.08);
}

/* HEADER (enroll style) */
.inquiry-modal-header {
   background: linear-gradient(180deg, #09515D 0%, #0a6573 100%);
    color: #fff;
    padding: 10px 14px 8px;
    text-align: center;
    position: relative;
    flex-shrink: 0;
}

/* close button */
.inquiry-close-btn {
    position: absolute;
    right: 14px;
    top: 14px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    background: #f1f5f9;
    color: #64748b;
    cursor: pointer;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* prevent overlap */
.inquiry-modal-title,
.inquiry-modal-subtitle,
.inquiry-info-pills {
    padding-right: 48px;
    padding-left: 16px;
}

/* title */
.inquiry-modal-title {
    font-size: 15px;
    font-weight: 800;
    margin: 0 0 2px;
    letter-spacing: -0.02em;
}

/* subtitle */
.inquiry-modal-subtitle {
    font-size: 11px;
    opacity: 0.9;
    margin: 0 0 8px;
}

/* pills */
.inquiry-info-pills {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}

.inquiry-modal-header .info-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    font-size: 11px;
    border-radius: 999px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.3);
}

.inquiry-modal-header .info-pill i {
    font-size: 12px;
}

/* body */
.inquiry-modal-body {
    padding: 12px 14px;
    overflow-y: auto;
}

/* grid */
.inquiry-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px 12px;
}

.inquiry-field-full {
    grid-column: 1 / -1;
}

/* inputs */
.inquiry-field label {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
}

.inquiry-field input,
.inquiry-field textarea {
    width: 100%;
    padding: 8px 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 11px; /* 👈 change from 13px to 11px */
}

.inquiry-field textarea {
    resize: none;
    min-height: 70px;
}

/* focus */
.inquiry-field input:focus,
.inquiry-field textarea:focus {
    border-color: #09515D;
    box-shadow: 0 0 0 3px rgba(9,81,93,0.15);
    outline: none;
}

/* consent */
.inquiry-consent {
    margin-top: 10px;
    padding: 10px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.inquiry-consent-label {
    display: flex;
    gap: 8px;
    font-size: 11px;
    color: #475569;
}

/* button (same as enroll) */
.inquiry-submit-btn {
    margin-top: 10px;
    width: 100%;
    padding: 12px;
    font-size: 14px;
    font-weight: 800;
    background: #09515D;
    color: #fff;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(9,81,93,0.35);
}

.inquiry-submit-btn:hover {
    background: #0a6573;
    transform: translateY(-1px);
}

/* footer */
.inquiry-footer {
    margin-top: 6px;
    font-size: 11px;
    text-align: center;
    color: #64748b;
}
.inquiry-field input::placeholder,
.inquiry-field textarea::placeholder {
    font-size: 11px;
      color: #94a3b8;
    opacity: 1; /* 🔥 THIS is the main fix */
}

/* mobile */
@media (max-width: 560px) {
    .inquiry-form-grid {
        grid-template-columns: 1fr;
    }

    .inquiry-modal-box {
        width: 95%;
    }
}
</style>

<!-- SCRIPTS -->
<script>
    window.addEventListener("hashchange", function() {
    if (location.hash === "#courses") {

        const section = document.getElementById("courses");

        if (section) {

            const offset = 120;

            const position =
                section.getBoundingClientRect().top +
                window.pageYOffset -
                offset;

            window.scrollTo({
                top: position,
                behavior: "smooth"
            });

        }
    }
});

let selectedCategory = "all";
let selectedLevel = "all";

function setCategory(category) {
    selectedCategory = category;
    filterCourses();
}

function setLevel(level) {
    selectedLevel = level;
    filterCourses();
}
function filterFreeCourses(event) {
    if (event) event.preventDefault();

    const freeCategoryRadio = Array.from(
        document.querySelectorAll('input[name="category"]')
    ).find(radio => {
        const labelText = radio.closest('label')?.innerText.trim().toLowerCase();
        return labelText === 'free courses';
    });

    if (freeCategoryRadio) {
        freeCategoryRadio.checked = true;
        selectedCategory = freeCategoryRadio.value;
        filterCourses();
    }

    const section = document.getElementById('courses');

    if (section) {
        const offset = 120;
        const position =
            section.getBoundingClientRect().top +
            window.pageYOffset -
            offset;

        window.scrollTo({
            top: position,
            behavior: 'smooth'
        });
    }
}
function filterCourses() {
    const cards = document.querySelectorAll(".course-card");
    let visibleCount = 0;

    cards.forEach(card => {
        const categoryMatch =
            selectedCategory === "all" ||
            card.dataset.category === selectedCategory;

        const levelMatch =
            selectedLevel === "all" ||
            card.dataset.level === selectedLevel;

        if (categoryMatch && levelMatch) {
            card.style.display = "block";
            visibleCount++;
        } else {
            card.style.display = "none";
        }
    });

    document.getElementById("noCoursesMessage").style.display =
        visibleCount === 0 ? "block" : "none";
}


function openInquiryModal(title, id, level, duration) {

    const modal = document.getElementById('inquiryModal');

    modal.style.display='flex';
    document.body.style.overflow='hidden';

   document.getElementById('inquiryTitle').innerText =
    `Inquiry about "${title}"`;

    document.getElementById('inquiryCourseTitle').value = title;
    document.getElementById('inquiryCourseId').value = id;
    document.getElementById('inquiryLevel').value = level || '';

    const infoBox = document.getElementById('inquiryInfo');

    if(duration || level) {

        infoBox.style.display='flex';

        document.getElementById('inquiryDuration').innerText =
            duration || '';

        document.getElementById('inquiryLevelText').innerText =
            level
            ? level.charAt(0).toUpperCase() + level.slice(1)
            : '';

    }
    else infoBox.style.display='none';

}


function closeInquiryModal() {

    document.getElementById('inquiryModal').style.display='none';
    document.body.style.overflow='';

}


window.addEventListener('click', function(e){

    if(e.target===document.getElementById('inquiryModal'))
        closeInquiryModal();

});


function closeSuccessModal(){

    document.getElementById('successModal').style.display='none';
    document.body.style.overflow='';

}


@if(session('popup_success'))

document.addEventListener('DOMContentLoaded', function(){

    document.getElementById('successModal').style.display='flex';
    document.body.style.overflow='hidden';

    document.querySelector('.success-title').innerText =
        "{{ session('popup_title') }}";

    document.querySelector('.success-text').innerText =
        "{{ session('popup_message') }}";

});

@endif

</script>