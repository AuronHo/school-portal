<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Meeting;
use App\Models\Attendance;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display the list of subjects for a specific classroom.
     */
    public function subjects(Classroom $classroom)
    {
        $subjects = $classroom->subjects;

        return view('classrooms.subjects', compact('classroom', 'subjects'));
    }

    /**
     * Display the weekly meetings for a specific subject within a classroom.
     */
    public function meetings(Classroom $classroom, Subject $subject)
    {
        // This assumes you have a relationship called 'meetings' in your Classroom model
        $meetings = $classroom->meetings()
            ->where('subject_id', $subject->id)
            ->orderBy('week_number', 'asc')
            ->get();

        return view('classrooms.meetings', compact('classroom', 'subject', 'meetings'));
    }

    // CRUD function to add class
    public function create()
    {
        return view('classrooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'academic_year' => 'required|integer|min:2024|max:2030',
        ]);

        // This creates the class and automatically sets the teacher_id to the logged-in user
        auth()->user()->managedClassrooms()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Classroom created successfully!');
    }

    public function destroy(Classroom $classroom)
    {
        // Ensure the teacher owns this class before deleting
        if ($classroom->teacher_id !== auth()->id()) {
            abort(403);
        }

        $classroom->delete();
        return redirect()->route('dashboard')->with('success', 'Classroom deleted.');
    }

    public function assignSubject(Classroom $classroom)
    {
        // Get subjects that are NOT already linked to this classroom
        $assignedSubjectIds = $classroom->subjects->pluck('id');
        $availableSubjects = \App\Models\Subject::whereNotIn('id', $assignedSubjectIds)->get();

        return view('classrooms.assign-subject', compact('classroom', 'availableSubjects'));
    }

    public function attachSubject(Request $request, Classroom $classroom)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
        ]);

        // 1. Attach to Pivot Table
        $classroom->subjects()->attach($request->subject_id, [
            'teacher_id' => auth()->id()
        ]);

        // 2. Automatically generate the 14-week schedule for this new assignment
        $subject = \App\Models\Subject::find($request->subject_id);
        for ($i = 1; $i <= 14; $i++) {
            \App\Models\Meeting::create([
                'classroom_id' => $classroom->id,
                'subject_id'   => $subject->id,
                'week_number'  => $i,
                'topic'        => "Week $i: " . $subject->name,
                'meeting_date' => now()->addWeeks($i),
            ]);
        }

        return redirect()->route('classrooms.subjects', $classroom->id)
                        ->with('success', 'Subject assigned and schedule generated!');
    }

    // roll call function
    public function rollCall(Classroom $classroom, Subject $subject, Meeting $meeting)
    {
        // Security Check: Ensure this meeting actually belongs to this classroom and subject
        if ($meeting->classroom_id !== $classroom->id || $meeting->subject_id !== $subject->id) {
            abort(404);
        }

        // Get all students enrolled in this classroom
        $students = $classroom->students;

        // Fetch existing status to show what was previously saved
        $attendanceRecords = Attendance::where('meeting_id', $meeting->id)
                                    ->pluck('status', 'user_id');

        return view('classrooms.roll-call', compact('classroom', 'subject', 'meeting', 'students', 'attendanceRecords'));
    }

    public function storeRollCall(Request $request, Meeting $meeting)
    {
        // Validate that we received an array of attendance data
        $request->validate([
            'attendance' => 'required|array',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            // This will update the record if it exists, or create a new one if it doesn't
            Attendance::updateOrCreate(
                ['meeting_id' => $meeting->id, 'user_id' => $studentId],
                ['status' => $status]
            );
        }

        return back()->with('success', 'Attendance has been saved successfully!');
    }
}