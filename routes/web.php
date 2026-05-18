<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Models\User;

/*
|----------------------------------
| HOME
|----------------------------------
*/
Route::get('/', function () {
    return redirect('/dashboard');
});

/*
|----------------------------------
| DASHBOARD (ALL LOGGED USERS)
|----------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::resource('users', UserController::class)->except(['show']);

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

/*
|----------------------------------
| AUTHENTICATED ROUTES
|----------------------------------
*/
Route::middleware('auth')->group(function () {

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // STUDENTS (VIEW for all logged users)
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');

    // CREATE + EDIT (admin + manager)
    Route::middleware('role:admin,manager')->group(function () {

        Route::resource('students', StudentController::class)
            ->except(['index', 'show', 'destroy']);

        Route::resource('courses', CourseController::class)
            ->except(['index', 'show', 'destroy']);

        Route::get('/enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
        Route::post('/enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
        Route::patch('/enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('enrollments.complete');
        Route::patch('/enrollments/{enrollment}/withdraw', [EnrollmentController::class, 'withdraw'])->name('enrollments.withdraw');

    });

    // DELETE (admin only)
    Route::middleware('role:admin')->group(function () {

        Route::delete('/students/{student}', [StudentController::class, 'destroy'])
            ->name('students.destroy');

        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])
            ->name('courses.destroy');

        Route::patch('/enrollments/{enrollment}/cancel', [EnrollmentController::class, 'cancel'])->name('enrollments.cancel');

    });

});

/*
|----------------------------------
| AUTH ROUTES
|----------------------------------
*/
require __DIR__.'/auth.php';
