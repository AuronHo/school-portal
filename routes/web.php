<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isTeacher()) {
        $data = [
            'classrooms' => \App\Models\Classroom::all(), 
            'total_tasks' => \App\Models\Task::where('teacher_id', $user->id)->count(),
        ];
        return view('dashboard', $data);
    } 

    // Logic for Students
    $data = [
        'classrooms' => $user->classrooms, 
        'my_submissions' => \App\Models\TaskSubmission::where('student_id', $user->id)->count(),
    ];
    return view('student.dashboard', $data);

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/classrooms/{classroom}/subjects', [ClassroomController::class, 'subjects'])->name('classrooms.subjects');
    Route::get('/classrooms/{classroom}/subjects/{subject}/meetings', [ClassroomController::class, 'meetings'])->name('classrooms.meetings');
    Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('classrooms.create');
    Route::post('/classrooms', [ClassroomController::class, 'store'])->name('classrooms.store');
    Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('classrooms.destroy');
    Route::get('/classrooms/{classroom}/subjects/assign', [ClassroomController::class, 'assignSubject'])->name('classrooms.subjects.assign');
    Route::post('/classrooms/{classroom}/subjects/attach', [ClassroomController::class, 'attachSubject'])->name('classrooms.subjects.attach');
    Route::post('/meetings/{meeting}/roll-call', [ClassroomController::class, 'storeRollCall'])->name('meetings.roll-call.store');
    Route::get('/classrooms/{classroom}/subjects/{subject}/meetings/{meeting}/roll-call', [ClassroomController::class, 'rollCall'])->name('meetings.roll-call');
    Route::get('/classrooms/{classroom}/subjects/{subject}/meetings/{meeting}/tasks/create', [ClassroomController::class, 'createTask'])->name('meetings.tasks.create');
    Route::post('/meetings/{meeting}/tasks', [ClassroomController::class, 'storeTask'])->name('meetings.tasks.store');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Student-specific views
        Route::get('/student/classrooms/{classroom}/subjects', [DashboardController::class, 'studentSubjects'])->name('student.classrooms.subjects');
        Route::get('/student/classrooms/{classroom}/subjects/{subject}/meetings', [DashboardController::class, 'studentMeetings'])->name('student.classrooms.meetings');
    });
});

require __DIR__.'/auth.php';