<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Meeting;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isTeacher()) {
            $classrooms = Classroom::all(); 
            return view('dashboard', compact('classrooms'));
        }

        // Student logic
        $classrooms = $user->classrooms; 

        if (!$classrooms) {
            $classrooms = collect();
        }

        return view('students.dashboard', compact('classrooms'));
    }

    public function studentSubjects(Classroom $classroom)
    {
        // Security check: Ensure student is actually in this class
        if (!auth()->user()->classrooms->contains($classroom->id)) {
            abort(403, 'Unauthorized access to this classroom.');
        }

        $subjects = $classroom->subjects;
        return view('students.subjects', compact('classroom', 'subjects'));
    }

    public function studentMeetings(Classroom $classroom, Subject $subject)
    {
        $meetings = Meeting::where('classroom_id', $classroom->id)
            ->where('subject_id', $subject->id)
            ->with(['tasks']) // Eager load tasks for better performance
            ->orderBy('week_number')
            ->get();

        return view('students.meetings', compact('classroom', 'subject', 'meetings'));
    }
}