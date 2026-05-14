<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subject;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return view('admin.users', compact('users'));
    }

    public function toggleRole(User $user)
    {
        if ($user->role === 'teacher') {
            $user->role = 'student';
        } else {
            $user->role = 'teacher';
        }

        $user->save();

        return back()->with('status', "Role for {$user->name} updated to {$user->role}!");
    }

    public function subjects()
    {
        $subjects = Subject::orderBy('name', 'asc')->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code',
            'description' => 'nullable|string'
        ]);

        Subject::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
        ]);

        return back()->with('status', "Subject {$request->name} added successfully!");
    }

    public function destroySubject(Subject $subject)
    {
        $subjectName = $subject->name;
        $subject->delete();

        return back()->with('status', "Subject '{$subjectName}' has been successfully deleted.");
    }
}
