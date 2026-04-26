<style>
/* Reset and Base Styles */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family:
    -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue",
    Arial, sans-serif;
  font-size: 17px;
  line-height: 1.6;
  color: #333;
  background-color: #fff !important;
  scroll-behavior: smooth;
}

.container {
  max-width: min(1600px, 96vw);
  margin: 0 auto;
  padding: 0 28px;
}

/* Buttons - GREEN COLOR SCHEME */
/* Buttons - GREEN COLOR SCHEME */
.btn-primary {
  background-color: #28a745;
  color: #000;
  border: none;
  padding: 12px 28px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}
.btn-primary:hover {
  background-color: #218838;
  color: #000;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}
.btn-outline {
  background-color: transparent;
  color: #28a745;
  border: 2px solid #28a745;
  padding: 10px 26px;
  border-radius: 8px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
}
.btn-outline:hover {
  background-color: #28a745;
  color: #000;
}
.btn-details {
  min-width: 120px;
  padding: 10px 20px;
}
.btn-inquiry {
  min-width: 120px;
  padding: 8px 18px;
}
.btn-free-courses {
  background-color: #dc3545;
  color: white;
  border: none;
  padding: 14px 26px;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  margin-top: 24px;
  transition: all 0.3s ease;
}
.btn-free-courses:hover {
  background-color: #c82333;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Header */
.main-header {
  background-color: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 1020;
  padding: 15px 0;
}

/* Match .courses-layout (300px sidebar + 36px gap) so nav aligns with course cards column */
.header-content {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 36px;
  align-items: center;
}

.header-nav-group {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 24px;
  min-width: 0;
}

.logo {
  min-width: 0;
}

.logo h2 {
  color: #28a745;
  font-size: 34px;
  font-weight: 700;
}

.main-nav ul {
  display: flex;
  list-style: none;
  gap: 30px;
}

.main-nav a {
  text-decoration: none;
  color: #333;
  font-weight: 500;
  transition: color 0.3s ease;
}

.main-nav a:hover {
  color: #28a745;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 15px;
}

.search-bar {
  position: relative;
  display: flex;
  align-items: center;
}

.search-bar input {
  padding: 10px 40px 10px 15px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 17px;
  width: 250px;
  transition: all 0.3s ease;
}

.search-bar input:focus {
  outline: none;
  border-color: #28a745;
}

.search-bar i {
  position: absolute;
  right: 15px;
  color: #999;
}

.mobile-menu-toggle {
  display: none;
  background: none;
  border: none;
  font-size: 24px;
  cursor: pointer;
  color: #333;
}

/* Hero Section */
.hero-section {
  padding: 90px 0;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.hero-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 56px;
  align-items: center;
}

.hero-text h1 {
  font-size: clamp(2rem, 3.5vw + 1.5rem, 52px);
  font-weight: 700;
  color: #333;
  margin-bottom: 22px;
  line-height: 1.2;
}

.hero-text p {
  font-size: 17px;
  color: #666;
  margin-bottom: 32px;
  line-height: 1.6;
}

.hero-buttons {
  display: flex;
  gap: 18px;
}

.hero-buttons .btn-primary,
.hero-buttons .btn-outline {
  padding: 14px 32px;
  font-size: 17px;
}

.hero-image img {
  width: 100%;
  border-radius: 14px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* Offer Section */
.offer-section {
  padding: 90px 0;
  background-color: #fff;
  text-align: center;
}

.offer-section h2 {
  font-size: clamp(1.75rem, 3vw + 1rem, 40px);
  font-weight: 700;
  margin-bottom: 18px;
  color: #333;
}

.section-subtitle {
  font-size: 17px;
  color: #666;
  margin-bottom: 56px;
  max-width: 880px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.6;
}

.offer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 32px;
  margin-bottom: 48px;
}

.offer-item {
  padding: 34px 32px;
  background-color: #f9f9f9;
  border-radius: 14px;
  transition: all 0.3s ease;
}

.offer-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.offer-item i {
  font-size: 52px;
  color: #28a745;
  margin-bottom: 22px;
}

.offer-item h4 {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #333;
}

.offer-item p {
  font-size: 17px;
  color: #666;
  line-height: 1.65;
}

/* Courses Section */
.courses-section {
  padding: 90px 0;
  background-color: #f5f5f5;
}

.courses-section h2 {
  font-size: clamp(1.75rem, 3vw + 1rem, 40px);
  font-weight: 700;
  margin-bottom: 44px;
  color: #333;
}

.courses-layout {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 36px;
}

/* Sidebar Filters */
.filters-sidebar {
  background-color: #fff;
  padding: 30px 28px;
  border-radius: 14px;
  position: sticky;
  top: 100px;
  height: fit-content;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filters-sidebar h4 {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 24px;
  color: #333;
}

.filter-group {
  margin-bottom: 32px;
}

.filter-group h5 {
  font-size: 17px;
  font-weight: 600;
  margin-bottom: 14px;
  color: #333;
}

.filter-group h5:not(:first-of-type) {
  margin-top: 36px;
}

.filter-checkbox,
.filter-radio {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
  cursor: pointer;
}

.filter-checkbox input,
.filter-radio input {
  margin-right: 10px;
  cursor: pointer;
  width: 18px;
  height: 18px;
  accent-color: #28a745;
}

.filter-checkbox span,
.filter-radio span {
  font-size: 17px;
  color: #555;
}

.filter-checkbox input:checked + span,
.filter-radio input:checked + span {
  font-weight: 700;
}

/* Course Cards Grid */
.courses-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
  gap: 30px;
}

.course-card {
  background-color: white;
  padding: 28px 26px;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
}

.course-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.course-card h4 {
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 14px;
  color: #333;
  line-height: 1.3;
}

.course-card > p {
  font-size: 17px;
  color: #666;
  margin-bottom: 16px;
  line-height: 1.55;
  flex-grow: 1;
}

.level-text {
  font-size: 17px;
  font-weight: 700;
  color: #333;
  margin-bottom: 12px;
}

.course-info {
  font-size: 17px;
  color: #777;
  margin-bottom: 6px;
}

.course-actions {
  display: flex;
  gap: 12px;
  margin-top: 18px;
}

.course-actions .btn-primary,
.course-actions .btn-outline {
  padding: 12px 24px;
  font-size: 16px;
}

.course-card.hidden {
  display: none;
}


/* ==========================================
   COURSE CARD STRUCTURE FIX (PRO LAYOUT)
   ========================================== */

.course-card{
  display:flex;
  flex-direction:column;
  height:100%;
  min-height:380px; /* keeps cards aligned */
}

/* TITLE */
.course-card h4{
  margin-bottom: 12px;
  min-height: 56px; /* keeps titles aligned */
}

/* DESCRIPTION FIXED HEIGHT */
.course-card > p:first-of-type{
  min-height:78px;
  max-height:78px;
  overflow:hidden;
  margin-bottom:16px;
}

/* LEVEL */
.level-text{
  margin-bottom: 10px !important;
}

/* PRICE + DURATION */
.course-info{
  margin-bottom: 8px !important;
}

/* REMOVE EXTRA GAP BEFORE BUTTONS */
.course-card .course-info:last-of-type{
  margin-bottom: 10px !important;
}

/* PUSH BUTTONS TO BOTTOM */
.course-actions{
  margin-top:auto;
  display:flex;
  gap:10px;
}

/* MOBILE SAFE */
@media (max-width:768px){
  .course-card{
    min-height:auto;
  }

  .course-card > p:first-of-type{
    min-height:auto;
    max-height:none;
  }
  .level-text{
  margin-bottom:4px !important;
}

/* PRICE + DURATION */
.course-info{
  margin-bottom:4px !important;
}

/* REMOVE EXTRA GAP BEFORE BUTTONS */
.course-card .course-info:last-of-type{
  margin-bottom:4px !important;
}

}


/* Newsletter CTA Section */
.newsletter-cta-section {
  padding: 90px 0;
  background: linear-gradient(135deg, #28a745 0%, #218838 100%);
  color: white;
}
.newsletter-content {
  display: grid;
  grid-template-columns: 60% 40%;
  gap: 48px;
  align-items: center;
}
.newsletter-text h2 {
  font-size: clamp(1.75rem, 3vw + 1rem, 40px);
  font-weight: 700;
  margin-bottom: 18px;
}
.newsletter-text p {
  font-size: 17px;
  opacity: 0.95;
  line-height: 1.6;
}
.newsletter-form form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}
.newsletter-form input {
  padding: 16px 18px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  font-size: 17px;
  background-color: white;
  color: #333;
}
.newsletter-form input::placeholder {
  color: #999;
}
.newsletter-form input:focus {
  outline: none;
  border-color: white;
  background-color: white;
}
.newsletter-form .btn-primary {
  background-color: white;
  color: #000;
  align-self: flex-start;
}
.newsletter-form .btn-primary:hover {
  background-color: #f0f0f0;
  color: #000;
}
.newsletter-form .btn-primary:hover {
  background-color: #f0f0f0;
  color: #000;
}
.privacy-note {
  font-size: 17px;
  opacity: 0.8;
  margin-top: 5px;
}

/* Footer */
.main-footer {
  background-color: #333;
  color: white;
  text-align: center;
  padding: 36px 0;
}

.main-footer p {
  font-size: 17px;
  line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .container {
    padding: 0 24px;
  }
}

@media (max-width: 968px) {
  .container {
    padding: 0 22px;
  }
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
  }
  .header-nav-group {
    justify-content: flex-end;
    flex: 1;
    min-width: 0;
  }
  .courses-layout {
    display: flex !important;
    flex-direction: column !important;
    gap: 28px;
  }
  .filters-sidebar {
    position: relative !important;
    top: 0 !important;
    width: 100% !important;
    margin-bottom: 28px !important;
    padding: 26px 24px;
  }
  .courses-grid {
    width: 100% !important;
    gap: 26px;
  }
  .hero-section {
    padding: 70px 0;
  }
  .hero-content {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  .hero-text h1 {
    font-size: clamp(1.75rem, 4vw + 1rem, 40px);
  }
  .hero-text p {
    font-size: 18px;
  }
  .offer-section,
  .courses-section {
    padding: 70px 0;
  }
  .offer-section h2,
  .courses-section h2 {
    font-size: 34px;
    margin-bottom: 36px;
  }
  .section-subtitle {
    font-size: 17px;
    margin-bottom: 44px;
  }
  .offer-grid {
    gap: 26px;
    margin-bottom: 40px;
  }
  .offer-item {
    padding: 28px 26px;
  }
  .offer-item h4 {
    font-size: 20px;
  }
  .offer-item p {
    font-size: 17px;
  }
  .newsletter-cta-section {
    padding: 70px 0;
  }
  .newsletter-content {
    grid-template-columns: 1fr;
    gap: 36px;
  }
  .newsletter-text h2 {
    font-size: 34px;
  }
  .newsletter-text p {
    font-size: 17px;
  }
}

@media (max-width: 768px) {
  .container {
    padding: 0 20px;
  }
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
  }
  .header-nav-group {
    flex: 0 0 auto;
    justify-content: flex-end;
  }
  /* Header Navigation */
  .main-nav {
    display: none;
    position: absolute;
    top: 70px;
    left: 0;
    right: 0;
    background-color: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    padding: 20px;
    z-index: 999;
  }
  .main-nav.active {
    display: block;
  }
  .main-nav ul {
    flex-direction: column;
    gap: 15px;
  }
  .mobile-menu-toggle {
    display: block;
  }
  .search-bar input {
    width: 150px;
  }
  .search-bar input:focus {
    width: 200px;
  }
  .header-actions .btn-outline,
  .header-actions .btn-primary {
    display: none;
  }
  .hero-section {
    padding: 56px 0;
  }
  .hero-text h1 {
    font-size: clamp(1.5rem, 5vw + 1rem, 34px);
  }
  .hero-text p {
    font-size: 17px;
  }
  .hero-buttons {
    flex-direction: column;
  }
  .hero-buttons .btn-primary,
  .hero-buttons .btn-outline {
    width: 100%;
    text-align: center;
  }
  .offer-section,
  .courses-section {
    padding: 56px 0;
  }
  .offer-section h2,
  .courses-section h2 {
    font-size: 30px;
    margin-bottom: 32px;
  }
  .section-subtitle {
    font-size: 17px;
    margin-bottom: 40px;
  }
  .offer-grid {
    grid-template-columns: 1fr;
    gap: 22px;
    margin-bottom: 36px;
  }
  .offer-item {
    padding: 26px 22px;
  }
  .offer-item i {
    font-size: 44px;
  }
  .offer-item h4 {
    font-size: 19px;
  }
  .offer-item p {
    font-size: 17px;
  }
  .courses-section {
    overflow: visible !important;
  }
  .courses-layout {
    display: flex !important;
    flex-direction: column !important;
    grid-template-columns: none !important;
    gap: 24px;
  }
  .filters-sidebar {
    position: static !important;
    position: relative !important;
    top: auto !important;
    width: 100% !important;
    margin-bottom: 24px !important;
    z-index: 1 !important;
    padding: 24px 22px;
  }
  .filters-sidebar h4 {
    font-size: 22px;
  }
  .courses-grid {
    display: grid !important;
    grid-template-columns: 1fr !important;
    width: 100% !important;
    position: relative !important;
    z-index: 2 !important;
    gap: 24px;
  }
  .course-card {
    padding: 24px 22px;
    min-height: auto;
  }
  .course-card h4 {
    font-size: 20px;
    min-height: auto;
  }
  .course-card > p:first-of-type {
    min-height: auto;
    max-height: none;
    font-size: 17px;
  }
  .level-text {
    font-size: 17px;
  }
  .course-info {
    font-size: 17px;
  }
  .course-actions .btn-primary,
  .course-actions .btn-outline {
    padding: 11px 20px;
    font-size: 15px;
  }
  .newsletter-cta-section {
    padding: 56px 0;
  }
  .newsletter-content {
    grid-template-columns: 1fr;
    text-align: left;
    gap: 28px;
  }
  .newsletter-text {
    margin-bottom: 28px;
  }
  .newsletter-text h2 {
    font-size: 30px;
  }
  .newsletter-text p {
    font-size: 17px;
  }
  .newsletter-form .btn-primary {
    width: 100%;
    align-self: stretch;
  }
  .main-footer {
    padding: 28px 0;
  }
}

@media (max-width: 480px) {
  .container {
    padding: 0 16px;
  }
  .hero-section {
    padding: 44px 0;
  }
  .hero-text h1 {
    font-size: 26px;
  }
  .hero-text p {
    font-size: 17px;
  }
  .offer-section,
  .courses-section {
    padding: 44px 0;
  }
  .offer-section h2,
  .courses-section h2,
  .newsletter-text h2 {
    font-size: 26px;
  }
  .courses-section h2 {
    margin-bottom: 28px;
  }
  .section-subtitle {
    font-size: 17px;
    margin-bottom: 36px;
  }
  .offer-item {
    padding: 22px 18px;
  }
  .offer-item i {
    font-size: 40px;
  }
  .offer-item h4 {
    font-size: 18px;
  }
  .course-actions {
    flex-direction: column;
  }
  .btn-details,
  .btn-inquiry {
    width: 100%;
  }
  .filters-sidebar {
    padding: 20px 18px;
    position: static !important;
  }
  .filters-sidebar h4 {
    font-size: 20px;
  }
  .filter-group h5 {
    font-size: 17px;
  }
  .filter-checkbox span,
  .filter-radio span {
    font-size: 17px;
  }
  .course-card {
    width: 100%;
    padding: 22px 18px;
  }
  .course-card h4 {
    font-size: 18px;
  }
  .newsletter-text h2 {
    font-size: 24px;
  }
  .newsletter-cta-section {
    padding: 44px 0;
  }
  .main-footer p {
    font-size: 17px;
  }
} /* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
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

/* ==========================================
   SMART BUTTON HOVER SWITCH
   Only one green at a time
   ========================================== */

/* When outline button is hovered */
.course-actions:has(.btn-outline:hover) .btn-primary {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}

/* Optional: keep green when primary hovered */
.course-actions:has(.btn-primary:hover) .btn-outline {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}
/* ==========================================
   HERO BUTTON SMART SWITCH
   Only one green at a time
   ========================================== */

/* When outline (Contact Us) is hovered */
.hero-buttons:has(.btn-outline:hover) .btn-primary {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}

/* When primary (Browse Courses) is hovered */
.hero-buttons:has(.btn-primary:hover) .btn-outline {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}
/* ==========================================
   HEADER BUTTON SMART SWITCH
   ========================================== */

/* When Sign In (outline) is hovered */
.header-btn-group:has(.btn-outline:hover) .btn-primary {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}

/* When Get Started (primary) is hovered */
.header-btn-group:has(.btn-primary:hover) .btn-outline {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #28a745 !important;
}
.newsletter-form .btn-primary {
  background: #ffffff !important;
  color: #28a745 !important;
  border: 2px solid #ffffff !important;
  transition: all .3s ease;
}

.newsletter-form .btn-primary:hover {
  background: #28a745 !important;
  color: #ffffff !important;
  border: 2px solid #ffffff !important;
  transform: translateY(-2px);
}

/* =====================================================
   FINAL CLEAN GREEN THEME OVERRIDE (NO ORANGE ANYWHERE)
   ===================================================== */

:root{
  --green-main:#28a745;
  --green-dark:#218838;
  --black:#000000;
  --white:#ffffff;
}

/* ===== REMOVE ALL ORANGE / TEAL ===== */
*{
  border-color: inherit;
}

/* Remove any inline orange colors */
[style*="#F47B1E"],
[style*="#09515D"]{
  color: var(--green-main) !important;
  background: transparent !important;
}

/* ===== HERO ===== */
.course-hero,
.hero-snapshot{
  background:#ffffff !important;
  color:var(--black) !important;
}

.hero-title{
  color:var(--black) !important;
}

/* ===== BADGE (NO BACKGROUND) ===== */
.hero-badge{
  background: transparent !important;
  color: var(--green-main) !important;
  border: 1px solid var(--green-main) !important;
}

/* ===== ICONS ===== */
.bi{
  color: var(--green-main) !important;
}

/* ===== PILLS ===== */
.hero-pills span,
.meta-pill,
.info-pill{
  background:#ffffff !important;
  border:1px solid rgba(40,167,69,.4) !important;
  color:var(--black) !important;
}

/* ===== SNAPSHOT CARD ===== */
.snapshot-card{
  border:1px solid rgba(40,167,69,.25) !important;
}

/* ===== SKILLS ===== */
.skills-wrap{
  background: rgba(40,167,69,.06) !important;
}

.skill-tag{
  background: rgba(40,167,69,.12) !important;
  color: var(--black) !important;
}

/* ===== PRIMARY BUTTONS ===== */
.btn-primary,
.btn-solid,
.reg-submit,
.submit-btn,
.success-btn,
.btn-inquiry{
  background: var(--green-main) !important;
  border-color: var(--green-main) !important;
  color: var(--black) !important;
}

.btn-primary:hover,
.btn-solid:hover,
.reg-submit:hover,
.submit-btn:hover,
.success-btn:hover,
.btn-inquiry:hover{
  background: var(--green-dark) !important;
  color: var(--black) !important;
}

/* ===== OUTLINE BUTTONS ===== */
.btn-outline,
.btn-secondary{
  background: transparent !important;
  border: 2px solid var(--green-main) !important;
  color: var(--green-main) !important;
}

.btn-outline:hover,
.btn-secondary:hover{
  background: var(--green-main) !important;
  color: var(--black) !important;
}

/* ===== INQUIRY BUTTON ICON FIX ===== */
.btn-inquiry i{
  color: var(--black) !important;
}

/* ===== PRINT BUTTON (TEXT ONLY GREEN) ===== */
.cta-buttons button[onclick="printCourse()"]{
  background: transparent !important;
  border: none !important;
  color: var(--green-main) !important;
  box-shadow: none !important;
}

/* ===== REMOVE SHADOW COLOR TINTS ===== */
.btn-solid,
.btn-primary{
  box-shadow: 0 6px 18px rgba(40,167,69,.15) !important;
}

/* ===== SUCCESS MODAL ===== */
.success-box{
  border-top: 4px solid var(--green-main) !important;
}

.success-title{
  color: var(--black) !important;
}

/* ===== LAUNCH ROW ===== */
.launch-row,
.launch-row.pro{
  background:#ffffff !important;
  border:1px solid rgba(40,167,69,.25) !important;
}

/* ===== PDF MODE CLEAN ===== */
body.pdf-mode *{
  background-image: none !important;
  box-shadow: none !important;
}

body.pdf-mode .course-hero{
  background:#ffffff !important;
}
/* ==================================================
   HERO REGISTER / INQUIRY SMART SWITCH
   ================================================== */

/* DEFAULT STATE */
.hero-btn-group .btn-solid{
  background:#28a745 !important;
  color:#000 !important;
  border:2px solid #28a745 !important;
}

.hero-btn-group .btn-inquiry{
  background:#ffffff !important;
  color:#28a745 !important;
  border:2px solid #28a745 !important;
}

/* ICON COLORS */
.hero-btn-group .btn-solid i{
  color:#000 !important;
}

.hero-btn-group .btn-inquiry i{
  color:#28a745 !important;
}

/* ============================= */
/* WHEN INQUIRY IS HOVERED */
/* ============================= */
.hero-btn-group:has(.btn-inquiry:hover) .btn-inquiry{
  background:#28a745 !important;
  color:#000 !important;
}

.hero-btn-group:has(.btn-inquiry:hover) .btn-inquiry i{
  color:#000 !important;
}

.hero-btn-group:has(.btn-inquiry:hover) .btn-solid{
  background:#ffffff !important;
  color:#28a745 !important;
  border:2px solid #28a745 !important;
}

.hero-btn-group:has(.btn-inquiry:hover) .btn-solid i{
  color:#28a745 !important;
}

/* ============================= */
/* WHEN REGISTER IS HOVERED */
/* ============================= */
.hero-btn-group .btn-solid:hover{
  background:#218838 !important;
  color:#000 !important;
}

/* FIX: Prevent card stretching when filtered */
.courses-grid{
  align-items: start;   /* stop vertical stretch */
}

.course-card{
  height: auto !important;
}

/* ===============================
   INQUIRY MODAL – GLOBAL (matches site body 17px, larger headings & modal)
   =============================== */
#inquiryModal.modal-overlay {
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
@keyframes inquiryModalIn {
  from { opacity: 0; transform: scale(0.96) translateY(-10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
.inquiry-modal-overlay {
  background: rgba(9, 81, 93, 0.5);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  padding: 28px;
}
.inquiry-modal-overlay[style*="flex"] .inquiry-modal-box {
  animation: inquiryModalIn 0.3s ease-out;
}
/* Scoped so other .modal-box (e.g. contact) don't override */
#inquiryModal .inquiry-modal-box,
.inquiry-modal-box {
  width: 100% !important;
  max-width: 600px !important;
  max-height: 90vh !important;
  height: auto !important;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 24px 48px rgba(9, 81, 93, 0.2), 0 0 0 1px rgba(9, 81, 93, 0.08);
  overflow: hidden;
  display: flex;
  flex-direction: column;
  position: relative;
  font-size: 17px;
  line-height: 1.6;
}
.inquiry-close-btn {
  position: absolute;
  right: 18px;
  top: 12px;
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
  padding: 20px 24px 16px;
  text-align: center;
  flex-shrink: 0;
}
.inquiry-modal-title { font-size: 22px; font-weight: 800; margin: 0 0 4px; letter-spacing: -0.02em; color: #fff; line-height: 1.3; }
.inquiry-modal-subtitle { font-size: 15px; opacity: 0.95; margin: 0 0 12px; font-weight: 500; }
.inquiry-info-pills { justify-content: center; gap: 10px; margin-top: 0; }
.inquiry-modal-header .info-pill {
  padding: 6px 12px; font-size: 14px;
  background: rgba(255,255,255,0.2) !important;
  color: #fff !important;
  border: 1px solid rgba(255,255,255,0.3) !important;
}
.inquiry-modal-header .bi { color: #fff !important; font-size: 1em; }
.inquiry-modal-body {
  padding: 24px 26px 26px;
  overflow: visible;
  flex: 1;
  min-height: 0;
}
.inquiry-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.inquiry-field { display: flex; flex-direction: column; gap: 6px; }
.inquiry-field-full { grid-column: 1 / -1; }
.inquiry-field label { font-size: 17px; font-weight: 700; color: #0f172a; }
.inquiry-field .optional { font-weight: 500; color: #64748b; font-size: 15px; }
.inquiry-field input,
.inquiry-field textarea {
  width: 100%; padding: 12px 14px;
  border: 1px solid #e2e8f0; border-radius: 10px;
  font-size: 17px; color: #0f172a; background: #fff;
  transition: border-color 0.2s, box-shadow 0.2s;
  line-height: 1.5;
}
.inquiry-field input::placeholder,
.inquiry-field textarea::placeholder { color: #94a3b8; font-size: 16px; }
.inquiry-field input:focus,
.inquiry-field textarea:focus {
  outline: none; border-color: #09515D;
  box-shadow: 0 0 0 3px rgba(9, 81, 93, 0.15);
}
.inquiry-field textarea { resize: vertical; min-height: 88px; }
.inquiry-consent {
  margin-top: 16px; padding: 14px 16px;
  background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;
}
.inquiry-consent-label {
  display: flex; gap: 12px; align-items: flex-start; cursor: pointer;
  font-size: 17px; color: #475569; line-height: 1.5;
}
.inquiry-consent-checkbox { margin-top: 3px; width: 18px; height: 18px; flex-shrink: 0; accent-color: #09515D; }
.inquiry-submit-btn {
  margin-top: 18px; width: 100%; padding: 14px 24px;
  background: #09515D; color: #fff; font-size: 17px; font-weight: 700;
  border: none; border-radius: 10px; cursor: pointer;
  display: inline-flex; align-items: center; justify-content: center; gap: 10px;
  transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
  box-shadow: 0 4px 14px rgba(9, 81, 93, 0.35);
}
.inquiry-submit-btn:hover { background: #0a6573; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(9, 81, 93, 0.4); }
.inquiry-submit-btn i { font-size: 20px; }
.inquiry-footer { margin-top: 12px; text-align: center; font-size: 15px; color: #64748b; }
@media (max-width: 640px) {
  #inquiryModal .inquiry-modal-box,
  .inquiry-modal-box { max-width: 96vw !important; }
  .inquiry-modal-header { padding: 18px 20px 14px; }
  .inquiry-modal-title { font-size: 20px; }
  .inquiry-modal-subtitle { font-size: 15px; }
  .inquiry-modal-body { padding: 20px 20px 22px; }
  .inquiry-form-grid { grid-template-columns: 1fr; gap: 14px; }
  .inquiry-field label { font-size: 17px; }
  .inquiry-field input,
  .inquiry-field textarea { font-size: 17px; padding: 12px 14px; }
  .inquiry-consent-label { font-size: 17px; }
  .inquiry-submit-btn { font-size: 17px; padding: 14px; }
}
/* Quill editor font support on frontend */

.rich-text .ql-font-roboto {
    font-family: 'Roboto', sans-serif;
}

.rich-text .ql-font-poppins {
    font-family: 'Poppins', sans-serif;
}

.rich-text .ql-font-inter {
    font-family: 'Inter', sans-serif;
}

.rich-text .ql-font-syne {
    font-family: 'Syne', sans-serif;
}

.rich-text .ql-font-arial {
    font-family: Arial, sans-serif;
}

.rich-text .ql-font-serif {
    font-family: serif;
}

.rich-text .ql-font-sans-serif {
    font-family: sans-serif;
}

.rich-text .ql-font-monospace {
    font-family: monospace;
}
</style>