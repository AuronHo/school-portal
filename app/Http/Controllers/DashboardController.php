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
            $notifications = $this->getTeacherNotifications($user, $classrooms);
            return view('dashboard', compact('classrooms', 'notifications'));
        }

        // Student logic
        $classrooms = $user->classrooms; 

        if (!$classrooms) {
            $classrooms = collect();
        }

        $hasJoinedClass = $classrooms->count() > 0;

        // Call the specialized notification function
        $notifications = $this->getStudentNotifications($user, $classrooms);

        return view('students.dashboard', compact('classrooms', 'notifications'));
    }

    private function getStudentNotifications($user, $classrooms)
    {
        $notifications = [];

        foreach ($classrooms as $classroom) {
            // A. Task Alerts: New & Deadlines
            $tasks = Task::whereIn('meeting_id', $classroom->meetings->pluck('id'))
                        ->where(function($q) {
                            // New in last 3 days OR due in next 2 days
                            $q->where('created_at', '>=', now()->subDays(3))
                            ->orWhereBetween('due_date', [now(), now()->addDays(2)]);
                        })->get();

            foreach ($tasks as $task) {
                // If it's a deadline and not submitted yet
                if ($task->due_date > now() && $task->due_date <= now()->addDays(2)) {
                    $hasSubmitted = $task->submissions()->where('student_id', $user->id)->exists();
                    if (!$hasSubmitted) {
                        $notifications[] = [
                            'type' => 'deadline',
                            'title' => 'Upcoming Deadline',
                            'message' => "{$task->title} in {$classroom->name} is due soon!",
                            'color' => 'red'
                        ];
                    }
                } 
                // If it's just a new post
                elseif ($task->created_at >= now()->subDays(3)) {
                    $notifications[] = [
                        'type' => 'new',
                        'title' => 'New Task',
                        'message' => "A new task was posted in {$classroom->name}.",
                        'color' => 'blue'
                    ];
                }
            }
        }

        // B. Attendance Alerts: Recent Absences
        $absences = \App\Models\Attendance::where('user_id', $user->id)
                    ->where('status', 'absent')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->with('meeting')
                    ->get();

        foreach ($absences as $abs) {
            $notifications[] = [
                'type' => 'attendance',
                'title' => 'Absence Recorded',
                'message' => "You were marked absent for Week {$abs->meeting->week_number}.",
                'color' => 'amber'
            ];
        }

        return $notifications;
    }

    private function getTeacherNotifications($user, $classrooms)
    {
        $notifications = [];

        foreach ($classrooms as $classroom) {
            // A. Check for Meetings Today that need Roll Call
            $todayMeetings = $classroom->meetings()
                ->whereDate('meeting_date', now()->toDateString())
                ->get();

            foreach ($todayMeetings as $meeting) {
                // Check if any roll call has been recorded yet
                if ($meeting->rollCalls()->count() === 0) {
                    $notifications[] = [
                        'type' => 'attendance',
                        'title' => 'Roll Call Missing',
                        'message' => "You haven't recorded attendance for today's meeting in {$classroom->name}.",
                        'color' => 'amber'
                    ];
                }
            }

            // B. Check for New Submissions (Last 24 hours)
            $meetingIds = $classroom->meetings->pluck('id');
            $newSubmissionsCount = \App\Models\TaskSubmission::whereIn('task_id', function($query) use ($meetingIds) {
                $query->select('id')->from('tasks')->whereIn('meeting_id', $meetingIds);
            })->where('created_at', '>=', now()->subDay())->count();

            if ($newSubmissionsCount > 0) {
                $notifications[] = [
                    'type' => 'task',
                    'title' => 'New Submissions',
                    'message' => "There are {$newSubmissionsCount} new task submissions in {$classroom->name} since yesterday.",
                    'color' => 'blue'
                ];
            }
        }

        return $notifications;
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