<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseLaunchController;
use App\Http\Controllers\Admin\CourseTopicController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourseEnrollmentController;
use App\Http\Controllers\CourseInquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriberController;

/*
|--------------------------------------------------------------------------
| Public — forms (throttled)
|--------------------------------------------------------------------------
*/
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::post('/course-enroll', [CourseEnrollmentController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('course.enroll');

Route::post('/course-inquiry', [CourseInquiryController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('course.inquiry');

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('subscribe.store');

Route::get('/search-courses', [CourseController::class, 'search'])
    ->name('courses.search');

/*
|--------------------------------------------------------------------------
| Public — pages
|--------------------------------------------------------------------------
*/
Route::get('/', [WebsiteController::class, 'index'])->name('index');
Route::get('/training', [WebsiteController::class, 'training'])->name('training.page');
Route::get('/courses/{course}', [WebsiteController::class, 'show'])->name('show');
Route::get('/course/switch-level', [WebsiteController::class, 'switchLevel'])->name('course.switch.level');

Route::get('/course-enroll', fn () => redirect('/'));

Route::get('/privacy', fn () => view('components.privacy'))->name('privacy');
Route::get('/terms', fn () => view('components.terms'))->name('terms');

/*
|--------------------------------------------------------------------------
| Authenticated — dashboard & profile
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin (authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('courses/popular', [CourseController::class, 'popular'])->name('courses.popular');
    Route::post('courses/make-popular', [CourseController::class, 'makePopular'])->name('courses.makePopular');
    Route::post('courses/remove-popular', [CourseController::class, 'removePopular'])->name('courses.removePopular');

    Route::get('courses/{course}/topics', [CourseTopicController::class, 'index'])->name('courses.topics');
    Route::post('courses/{course}/topics', [CourseTopicController::class, 'store'])->name('courses.topics.store');
    Route::get('topics/{topic}/edit', [CourseTopicController::class, 'edit'])->name('topics.edit');
    Route::put('topics/{topic}', [CourseTopicController::class, 'update'])->name('topics.update');
    Route::delete('topics/{topic}', [CourseTopicController::class, 'destroy'])->name('topics.destroy');

    Route::resource('courses', CourseController::class);

    Route::resource('course-launches', CourseLaunchController::class);

    Route::get('course-enrollments', [CourseEnrollmentController::class, 'index'])->name('course-enrollments.index');
    Route::get('course-enrollments/launch/{launch}', [CourseEnrollmentController::class, 'byLaunch'])->name('course-enrollments.byLaunch');
    Route::get('course-enrollments/{enrollment}', [CourseEnrollmentController::class, 'show'])->name('course-enrollments.show');
    Route::delete('course-enrollments/{enrollment}', [CourseEnrollmentController::class, 'destroy'])->name('course-enrollments.destroy');
    Route::post('course-enrollments/{enrollment}/approve', [CourseEnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::post('course-enrollments/{enrollment}/reject', [CourseEnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::post('course-enrollments/{enrollment}/reply', [CourseEnrollmentController::class, 'reply'])->name('enrollments.reply');

    Route::get('course-inquiries', [CourseInquiryController::class, 'index'])->name('course-inquiries.index');
    Route::get('course-inquiries/launch/{launch}', [CourseInquiryController::class, 'byLaunch'])->name('course-inquiries.byLaunch');
    Route::get('course-inquiries/{courseInquiry}', [CourseInquiryController::class, 'show'])->name('course-inquiries.show');
    Route::post('course-inquiries/{courseInquiry}/reply', [CourseInquiryController::class, 'reply'])->name('course-inquiries.reply');
    Route::delete('course-inquiries/{courseInquiry}', [CourseInquiryController::class, 'destroy'])->name('course-inquiries.destroy');

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::post('contacts/{contact}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::post('subscribers/send-message', [SubscriberController::class, 'sendMessage'])
        ->middleware('throttle:20,1')
        ->name('subscribers.send');
});

/*
|--------------------------------------------------------------------------
| Admin — training gallery (categories & images)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin/training')->name('training.')->group(function () {
    Route::get('/', [TrainingController::class, 'index'])->name('index');

    Route::get('/categories', [TrainingController::class, 'categoriesIndex'])->name('categories.index');
    Route::get('/categories/create', [TrainingController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories', [TrainingController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categories/{category}/edit', [TrainingController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{category}', [TrainingController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [TrainingController::class, 'destroyCategory'])->name('categories.destroy');

    Route::get('/images/create', [TrainingController::class, 'createImage'])->name('images.create');
    Route::post('/images', [TrainingController::class, 'storeImage'])->name('images.store');
    Route::get('/images/{image}/edit', [TrainingController::class, 'editImage'])->name('images.edit');
    Route::put('/images/{image}', [TrainingController::class, 'updateImage'])->name('images.update');
    Route::delete('/images/{image}', [TrainingController::class, 'destroyImage'])->name('images.destroy');
});

require __DIR__.'/auth.php';
