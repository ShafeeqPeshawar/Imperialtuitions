# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Imperial Tutions is a Laravel 11 course management platform for an educational institution. The application handles course listings, enrollments, inquiries, contact forms, training galleries, and subscriber management with automated email notifications.

## Tech Stack

- **Backend**: Laravel 11.31 (PHP 8.2+)
- **Frontend**: Blade templates, Alpine.js, Tailwind CSS
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Testing**: Pest PHP
- **Build**: Vite
- **Queue**: Database driver
- **Mail**: Configurable (log driver in development)

## Development Commands

### Initial Setup
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Development Server
```bash
# Run all services concurrently (server, queue, logs, vite)
composer dev

# Or run individually:
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Check without fixing
./vendor/bin/pint --test
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration (drops all tables)
php artisan migrate:fresh

# Seed database
php artisan db:seed
```

### Asset Building
```bash
# Development build with hot reload
npm run dev

# Production build
npm run build
```

## Architecture

### Core Domain Models

**Course Management**
- `Course`: Main course entity with title, description, image, level, duration, price, skills, sort_order, is_active, is_popular
- `CourseTopic`: Topics/curriculum items belonging to a course
- `CourseLaunch`: Launch dates for courses (one course can have multiple launches)
- `TrainingCategory`: Categories for organizing courses
- `TrainingImage`: Gallery images for training showcase

**User Interactions**
- `CourseEnrollment`: Student enrollments with status (pending/approved/rejected), linked to course and launch
- `CourseInquiry`: Pre-enrollment inquiries with reply_status (pending/replied)
- `Contact`: General contact form submissions with reply_status
- `Subscriber`: Email subscribers for course launch notifications

### Key Relationships

- Course → hasMany CourseLaunch (one course, multiple launch dates)
- Course → hasMany CourseTopic (curriculum structure)
- Course → hasOne nextLaunch (upcoming launch date)
- Course → belongsTo TrainingCategory
- CourseLaunch → hasMany CourseEnrollment
- CourseLaunch → hasMany CourseInquiry
- CourseLaunch → belongsTo Course

### Observer Pattern

`CourseLaunchObserver` automatically sends email notifications to all subscribers when a free course launch is created. This is registered in `AppServiceProvider::boot()`.

### View Composers

`AppServiceProvider` shares pending counts across admin layout:
- `pendingEnrollmentsCount`: Enrollments with status='pending'
- `pendingContactsCount`: Contacts with reply_status='pending'
- `pendingInquiriesCount`: Course inquiries with reply_status='pending'

### Route Organization

Routes are organized in `routes/web.php`:
1. **Public forms** (throttled): contact, enrollment, inquiry, subscribe
2. **Public pages**: homepage, training gallery, course details, privacy/terms
3. **Authenticated**: dashboard, profile
4. **Admin** (auth required): courses, launches, enrollments, inquiries, contacts, subscribers, training management

All form submissions use rate limiting (5-10 requests per minute).

### Mail System

Email templates in `app/Mail/`:
- `CourseEnrollmentConfirmation`: Sent to students on enrollment
- `CourseEnrollmentApproved/Rejected`: Status updates
- `FreeCourseLaunchMail`: Auto-sent to subscribers for free course launches
- `SubscriberWelcomeMail`: Welcome email for new subscribers
- `SubscriberBroadcastMail`: Bulk messages to subscribers

All emails use `resources/views/emails/layout.blade.php` as base template.

### Admin Dashboard

`DashboardController` aggregates:
- Total enrollments and active courses
- Recent activities (last 5 from enrollments, inquiries, contacts)
- Pending counts for admin notifications

### Authentication

Uses Laravel Breeze for authentication scaffolding. Admin routes require `auth` middleware but no role-based access control is implemented (all authenticated users have admin access).

## Important Patterns

### Status Fields
- `CourseEnrollment.status`: pending → approved/rejected
- `Contact.reply_status`: pending → replied
- `CourseInquiry.reply_status`: pending → replied

### Queue Usage
Queue connection is set to 'database'. Emails are queued for async processing. Ensure queue worker is running in production:
```bash
php artisan queue:work --tries=3
```

### Image Storage
Course and training images are stored in `public/` directory. Image paths are stored as strings (e.g., 'co1.png') in the database.

### Level System
Courses have a 'level' field (beginner/intermediate/advanced). The frontend allows switching between levels for the same course using `course.switch.level` route.

## Database Notes

- Default connection is SQLite (`database/database.sqlite`)
- Migrations use incremental timestamps
- Several migrations modify existing tables (add columns, change nullability)
- No seeders are currently defined

## Frontend Structure

- Main layout: `resources/views/layouts/app.blade.php`
- Admin layout: `resources/views/layouts/admin.blade.php`
- Homepage components: `resources/views/components/homee/` (hero, courses, training, testimonials, etc.)
- Admin views: `resources/views/admin/` (organized by resource)

## Testing Notes

- Test framework: Pest PHP
- Test environment uses array drivers for cache, session, mail
- Database connection for tests should be configured in phpunit.xml (currently commented out)

## Common Tasks

### Adding a New Course
1. Create via admin panel or `Course::create()`
2. Add topics via `CourseTopicController`
3. Create launch dates via `CourseLaunchController`
4. If free (price=0), subscribers are auto-notified

### Processing Enrollments
1. Student submits enrollment form (public route)
2. Admin views in dashboard (pending status)
3. Admin approves/rejects via `CourseEnrollmentController`
4. Email sent to student with status

### Broadcasting to Subscribers
Admin can send bulk messages via `SubscriberController::sendMessage()` (throttled to 20/minute)
