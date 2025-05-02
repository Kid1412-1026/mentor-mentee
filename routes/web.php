<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/',[App\Http\Controllers\AnnouncementController::class, 'index'])->name('home');

Route::get('dashboard', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->role === 'student') {
        return redirect()->route('student.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('student/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('student/profile', \App\Livewire\Student\Profile::class)->name('student.profile');
    Route::post('student-profileupdate', [App\Http\Controllers\Student\ProfileController::class, 'updateProfile'])->name('student.profileupdate');
    Route::post('student-profileimageupdate', [App\Http\Controllers\Student\ProfileController::class, 'updateProfileImage'])->name('student.profileimageupdate');

    Route::get('student-activity', [App\Http\Controllers\Student\ActivityController::class, 'index'])->name('student.activity');
    Route::post('student-activity', [App\Http\Controllers\Student\ActivityController::class, 'store'])->name('student.activity.store');
    Route::get('student-activity/{id}/edit', [App\Http\Controllers\Student\ActivityController::class, 'edit'])->name('student.activity.edit');
    Route::put('student-activity/{id}', [App\Http\Controllers\Student\ActivityController::class, 'update'])->name('student.activity.update');
    Route::delete('student-activity/{id}', [App\Http\Controllers\Student\ActivityController::class, 'destroy'])->name('student.activity.destroy');

    Route::get('student-course', [App\Http\Controllers\Student\CourseController::class, 'viewcourse'])->name('student.course');
    Route::post('student/enroll', [App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('student.enroll');
    Route::put('student-course/{id}', [App\Http\Controllers\Student\CourseController::class, 'updateEnrollment'])->name('student.course.update');
    Route::get('student-kpi', [App\Http\Controllers\Student\KPIController::class, 'index'])->name('student.kpi');
    Route::get('student-mentor', [App\Http\Controllers\Student\MentorController::class, 'index'])->name('student.mentor');
    Route::get('student-mentor/events', [App\Http\Controllers\Student\MentorController::class, 'events'])->name('student.mentor.events');
    Route::post('student.mentor.store', [App\Http\Controllers\Student\MentorController::class, 'store'])->name('student.mentor.store');
    Route::get('/student/mentor/details/{id}', [App\Http\Controllers\Student\MentorController::class, 'getDetails'])
        ->middleware(['auth', 'student'])
        ->name('student.mentor.details');
    Route::delete('/student/mentor/{id}', [App\Http\Controllers\Student\MentorController::class, 'destroy'])
        ->middleware(['auth', 'student'])
        ->name('student.mentor.destroy');
    // Challenge Management Routes
    Route::get('student-challenge', [App\Http\Controllers\Student\ChallengeController::class, 'studchallenge'])->name('student.challenge');
    Route::post('student-challenge', [App\Http\Controllers\Student\ChallengeController::class, 'store'])->name('student.challenge.store');
    Route::get('student-challenge/{id}/edit', [App\Http\Controllers\Student\ChallengeController::class, 'edit'])->name('student.challenge.edit');
    Route::put('student-challenge/{id}', [App\Http\Controllers\Student\ChallengeController::class, 'update'])->name('student.challenge.update');
    Route::delete('student-challenge/{id}', [App\Http\Controllers\Student\ChallengeController::class, 'destroy'])->name('student.challenge.destroy');

    Route::get('student-career', [App\Http\Controllers\Student\CareerController::class, 'studcareer'])->name('student.career');
    Route::get('/student/courses/export', [App\Http\Controllers\Student\CourseController::class, 'exportCourseProgress'])
        ->name('student.courses.export');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('admin/profile', \App\Livewire\Admin\Profile::class)->name('admin.profile');
    Route::get('announcements', [App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements');

    // Add the new assign mentor route
    Route::get('admin/assign-mentor', [App\Http\Controllers\Admin\MentorController::class, 'assignMentor'])
        ->name('admin.assign-mentor');
    Route::post('admin/assign-mentor/{student}', [App\Http\Controllers\Admin\MentorController::class, 'assignMentorToStudent'])
        ->name('admin.assign-mentor.store');
    Route::post('/assign-mentor/bulk', [App\Http\Controllers\Admin\MentorController::class, 'assignMentorBulk'])
        ->name('admin.assign-mentor.bulk');

    // Student Management Routes
    Route::get('student/{student}', [App\Http\Controllers\Admin\DashboardController::class, 'show'])->name('admin.student.show');
    Route::get('/student/{id}/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.student.export');
    Route::get('/admin/student/export-batch', [App\Http\Controllers\Admin\ReportController::class, 'exportBatch'])->name('admin.student.export-batch');

    // News Management Routes
    Route::get('admin-news', [App\Http\Controllers\Admin\NewsController::class, 'index'])->name('admin.news');
    Route::post('admin-news', [App\Http\Controllers\Admin\NewsController::class, 'store'])->name('admin.news.store');
    Route::get('admin-news/{id}/edit', [App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('admin.news.edit');
    Route::put('admin-news/{id}', [App\Http\Controllers\Admin\NewsController::class, 'update'])->name('admin.news.update');
    Route::delete('admin-news/{id}', [App\Http\Controllers\Admin\NewsController::class, 'destroy'])->name('admin.news.destroy');

    // Career Management Routes
    Route::get('admin-career', [App\Http\Controllers\Admin\CareerController::class, 'index'])->name('admin.career');
    Route::post('admin-career', [App\Http\Controllers\Admin\CareerController::class, 'store'])->name('admin.career.store');
    Route::get('admin-career/{id}/edit', [App\Http\Controllers\Admin\CareerController::class, 'edit'])->name('admin.career.edit');
    Route::put('admin-career/{id}', [App\Http\Controllers\Admin\CareerController::class, 'update'])->name('admin.career.update');
    Route::delete('admin-career/{id}', [App\Http\Controllers\Admin\CareerController::class, 'destroy'])->name('admin.career.destroy');

    // Course Management Routes
    Route::get('admin-course', [App\Http\Controllers\Admin\CourseController::class, 'admincourse'])->name('admin.course.index');
    Route::post('admin-course', [App\Http\Controllers\Admin\CourseController::class, 'store'])->name('admin.course.store');
    Route::get('admin-course/{id}/edit', [App\Http\Controllers\Admin\CourseController::class, 'edit'])->name('admin.course.edit');
    Route::put('admin-course/{id}', [App\Http\Controllers\Admin\CourseController::class, 'update'])->name('admin.course.update');
    Route::delete('admin-course/{id}', [App\Http\Controllers\Admin\CourseController::class, 'destroy'])->name('admin.course.destroy');

    Route::get('admin-buildcoursestruct', [App\Http\Controllers\Admin\BuildStructController::class, 'viewStruct'])->name('admin.buildcoursestruct');
    Route::post('admin-buildcoursestruct/store', [App\Http\Controllers\Admin\BuildStructController::class, 'store'])->name('admin.buildcoursestruct.store');
    Route::put('admin-buildcoursestruct/{id}', [App\Http\Controllers\Admin\BuildStructController::class, 'update'])->name('admin.buildcoursestruct.update');
    Route::delete('admin-buildcoursestruct/{id}', [App\Http\Controllers\Admin\BuildStructController::class, 'destroy'])->name('admin.buildcoursestruct.destroy');
    Route::get('admin-buildcoursestruct/get-structure', [App\Http\Controllers\Admin\BuildStructController::class, 'getStructure'])->name('admin.buildcoursestruct.get-structure');
    Route::get('admin-report', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.report');
    Route::get('admin-mentor', [App\Http\Controllers\Admin\MentorController::class, 'index'])->name('admin.mentor');
    Route::get('admin-mentor/events', [App\Http\Controllers\Admin\MentorController::class, 'events'])->name('admin.mentor.events');
    Route::post('admin-mentor/update-status/{counseling}', [App\Http\Controllers\Admin\MentorController::class, 'updateStatus'])->name('admin.mentor.update-status');
    Route::get('admin-mentorreport', [App\Http\Controllers\Admin\MentorController::class, 'report'])->name('admin.mentorreport');
    Route::get('admin-profileupdate', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfile'])->name('admin.profileupdate');
    Route::get('admin-profileimageupdate', [App\Http\Controllers\Admin\ProfileController::class, 'updateProfileImage'])->name('admin.profileimageupdate');
    Route::get('admin-kpigoal', [App\Http\Controllers\Admin\KPIGoalController::class, 'index'])->name('admin.kpigoal');
    Route::get('admin-kpi-goal/{id}/edit', [App\Http\Controllers\Admin\KPIGoalController::class, 'edit']);
    Route::put('admin-kpi-goal/{id}', [App\Http\Controllers\Admin\KPIGoalController::class, 'update'])->name('admin.kpi-goal.update');
    Route::get('admin-meetingreport', [App\Http\Controllers\Admin\MeetingReportController::class, 'index'])
        ->name('admin.meetingreport');
    Route::post('admin-meetings', [App\Http\Controllers\Admin\MeetingReportController::class, 'store'])
        ->name('admin.meetings.store');
    Route::get('admin-meetings/{meeting}', [App\Http\Controllers\Admin\MeetingReportController::class, 'show'])
        ->name('admin.meetings.show');
    Route::get('admin-meeting-report', [App\Http\Controllers\Admin\MentorController::class, 'meetingreport'])
        ->name('admin.meetingreport.');
    Route::get('/meeting/{meeting}/export', [App\Http\Controllers\Admin\MeetingReportController::class, 'export'])
        ->name('admin.meeting.export');
    Route::get('/meeting/export-batch', [App\Http\Controllers\Admin\MeetingReportController::class, 'exportBatch'])
        ->name('admin.meeting.export-batch');
    Route::get('/course-structure/export-batch', [App\Http\Controllers\Admin\BuildStructController::class, 'exportBatch'])
        ->name('admin.course-structure.export-batch');
});

require __DIR__.'/auth.php';









































































