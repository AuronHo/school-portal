<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Meeting;
use App\Models\Task;           
use App\Models\TaskSubmission;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // admin
        if ($user->role === 'admin') {
            return redirect()->route('admin.users');
        }

        // teacher
        if ($user->isTeacher()) {
            $classrooms = Classroom::where('teacher_id', $user->id)->get();
            return view('dashboard', compact('classrooms'));
        }

        // Student logic
        $classrooms = $user->classrooms; 

        if (!$classrooms) {
            $classrooms = collect();
        }

        $hasJoinedClass = $classrooms->count() > 0;

        return view('students.dashboard', compact('classrooms'));
    }

    public function unenroll(Classroom $classroom)
    {
        // Detach the user from this specific classroom
        auth()->user()->classrooms()->detach($classroom->id);

        return redirect()->route('dashboard')->with('status', 'You have successfully left the classroom.');
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
            ->with([
                'tasks.submissions' => function($query) {
                    $query->where('student_id', auth()->id());
                },
                // Load the roll call status for this student
                'rollCalls' => function($query) {
                    $query->where('user_id', auth()->id());
                }
            ])
            ->orderBy('week_number')
            ->get();

        return view('students.meetings', compact('classroom', 'subject', 'meetings'));
    }

    public function submitTask(Request $request, Task $task)
    {
        $request->validate([
            'file' => 'required|mimes:pdf,doc,docx,zip|max:5120',
        ]);

        // Check if it's an update or a new submission
        $isUpdate = TaskSubmission::where('task_id', $task->id)
                    ->where('student_id', auth()->id())
                    ->exists();

        $submission = TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => auth()->id()],
            ['file_path' => $request->file('file')->store('submissions', 'public')]
        );

        $message = $isUpdate ? 'Submission updated successfully!' : 'Assignment submitted successfully!';

        return back()->with('status', $message);
    }

    public function enrollIndex()
    {
        // Get all classrooms that the student IS NOT already enrolled in
        $availableClassrooms = Classroom::whereDoesntHave('students', function($query) {
            $query->where('user_id', auth()->id());
        })->with('teacher')->get();

        return view('students.enroll', compact('availableClassrooms'));
    }

    public function enrollStore(Classroom $classroom)
    {
        $user = auth()->user();

        // Attach the student to the classroom in the pivot table
        $user->classrooms()->attach($classroom->id);

        return redirect()->route('dashboard')->with('status', "Successfully enrolled in {$classroom->name}!");
    }

}