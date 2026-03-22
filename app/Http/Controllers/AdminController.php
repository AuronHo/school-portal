<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subject;

class AdminController extends Controller
{
    public function index()
    {
        // Simple security check: if not admin, go back
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::where('id', '!=', auth()->id())->get(); // Get everyone except yourself
        return view('admin.users', compact('users'));
    }

    public function toggleRole(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Smart Toggle logic
        if ($user->role === 'teacher') {
            $user->role = 'student';
            $message = "{$user->name} has been demoted to Student.";
        } else {
            $user->role = 'teacher';
            $message = "{$user->name} has been promoted to Teacher.";
        }

        $user->save();

        return back()->with('status', "Role for {$user->name} updated to {$user->role}!");
    }

    public function subjects()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Fetch all subjects to display in the table
        $subjects = Subject::orderBy('name', 'asc')->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code', // Ensures no duplicate codes like MATH101
            'description' => 'nullable|string'
        ]);

        // Save to database
        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code), // Auto-capitalize the code
            'description' => $request->description,
        ]);

        return back()->with('status', "Subject {$request->name} added successfully!");
    }

    public function destroySubject(Subject $subject)
    {
        // Security check
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Save the name temporarily so we can show it in the success message
        $subjectName = $subject->name; 
        
        // Delete it from the database
        $subject->delete();

        return back()->with('status', "Subject '{$subjectName}' has been successfully deleted.");
    }
}
