<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display the list of subjects for a specific classroom.
     */
    public function subjects(Classroom $classroom)
    {
        // Fetch all subjects (or filter them if you have a specific curriculum)
        $subjects = Subject::all();

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
}