<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassroomController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    // Logic to get different data based on role
    $data = [];
    if ($user->isTeacher()) {
        $data['classrooms'] = $user->managedClassrooms;
        $data['total_tasks'] = $user->postedTasks()->count();
    } else {
        $data['classrooms'] = $user->enrolledClassrooms;
        $data['my_submissions'] = $user->mySubmissions()->count();
    }

    return view('dashboard', $data);
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
});

require __DIR__.'/auth.php';