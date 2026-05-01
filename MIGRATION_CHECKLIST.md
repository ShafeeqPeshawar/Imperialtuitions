# Imperial Tuitions Laravel -> Next.js Migration Checklist

This checklist tracks one-to-one feature parity from the current Laravel app.

## Public Routes

- [x] `/` homepage route created in Next (`app/page.tsx`)
- [x] `/training` route created in Next (`app/training/page.tsx`)
- [x] `/courses/[id]` route created in Next (`app/courses/[id]/page.tsx`)
- [x] `/privacy` page parity
- [x] `/terms` page parity
- [x] `/course/switch-level` API parity
- [x] `/search-courses` API parity

## Public Forms / Actions

- [x] `POST /contact` (validation + success UX + email)
- [x] `POST /course-enroll` (validation + success UX + email)
- [x] `POST /course-inquiry` (validation + success UX + email)
- [x] `POST /subscribe` (success/duplicate/error modal behavior parity)

## Auth + User

- [x] Login / register / password reset parity
- [x] Dashboard parity
- [x] Profile edit/update/delete parity

## Admin Routes + Features

- [x] Courses CRUD
- [x] Popular courses management
- [x] Course topic CRUD
- [x] Course launches CRUD
- [x] Course enrollments list/show/reply/approve/reject/delete
- [x] Course inquiries list/show/reply/delete
- [x] Contacts list/show/reply/delete
- [x] Subscribers list + broadcast send
- [x] Training categories/images CRUD

## Data / Backend

- [x] Move models and data layer into Next.js backend (or agreed external API layer)
- [ ] Recreate all validation rules exactly
- [ ] Recreate all email templates and email send flows
- [x] Keep pagination behavior exactly same as Laravel pages (20/page on admin lists)

## UI Fidelity

- [ ] Import and apply final production CSS without visual regressions
- [ ] Match all colors, spacing, typography, hover states, modals
- [ ] Match all JS interactions (filters, modals, smooth scroll, mobile nav)
- [ ] Match responsive behavior across breakpoints

## Final Go-Live

- [x] Run route-by-route QA against Laravel baseline (admin/auth/public core routes)
- [ ] Run production build and performance checks
- [ ] Redirect traffic to Next.js app
