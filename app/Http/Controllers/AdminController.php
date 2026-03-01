<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
