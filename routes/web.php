<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $studentCount = Student::count();
    $userCount = User::count();
    $courseCount = Course::count();
    $recentStudents = Student::with('course')->latest()->take(4)->get();

    return view('dashboard', compact(
        'studentCount',
        'userCount',
        'courseCount',
        'recentStudents'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | View Routes: admin, manager, staff
    |--------------------------------------------------------------------------
    */
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');

    Route::get('/students/create', [StudentController::class, 'create'])
        ->name('students.create');

    Route::post('/students', [StudentController::class, 'store'])
        ->name('students.store');
        
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');

    /*
    |--------------------------------------------------------------------------
    | Create + Edit Routes: admin, manager
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,manager')->group(function () {

        Route::resource('students', StudentController::class)
            ->except(['index', 'show', 'destroy']);

        Route::resource('courses', CourseController::class)
            ->except(['index', 'show', 'destroy']);

        Route::get('/enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
        Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');

        Route::patch('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])
            ->name('enrollments.complete');

        Route::patch('/enrollments/{enrollment}/withdraw', [EnrollmentController::class, 'withdraw'])
            ->name('enrollments.withdraw');
    });

    /*
    |--------------------------------------------------------------------------
    | Delete / Cancel Routes: admin only
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        Route::patch('/students/{id}/restore', [StudentController::class, 'restore'])
            ->name('students.restore');

        Route::delete('/students/{id}/force-delete', [StudentController::class, 'forceDelete'])
            ->name('students.force-delete');

        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
            ->name('courses.destroy');

        Route::patch('/enrollments/{enrollment}/cancel', [EnrollmentController::class, 'cancel'])
            ->name('enrollments.cancel');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });
    });
});

require __DIR__ . '/auth.php';
