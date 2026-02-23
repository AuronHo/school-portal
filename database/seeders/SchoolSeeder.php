<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Meeting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Teachers
        $teachers = collect(['Smith', 'Johnson', 'Brown'])->map(function ($name) {
            return \App\Models\User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@uib.ac.id',
                'password' => bcrypt('password'),
                'role' => 'teacher',
            ]);
        });

        // 2. Create Subjects
        $subjects = collect(['Math', 'Science', 'History', 'IT', 'English'])->map(function ($name) {
            return \App\Models\Subject::create([
                'name' => $name,
                'code' => strtoupper(substr($name, 0, 3)) . rand(100, 999)
            ]);
        });

        // 3. Create Classrooms (1 per teacher)
        $classrooms = $teachers->map(function ($teacher, $index) {
            return \App\Models\Classroom::create([
                'name' => 'Class ' . (10 + $index) . '-A',
                'teacher_id' => $teacher->id,
                'academic_year' => 2026
            ]);
        });

        // 4. Create 20 Random Students using the User Factory
        $students = \App\Models\User::factory(20)->create(['role' => 'student']);

        // 5. Enroll Students randomly into Classrooms
        $students->each(function ($student) use ($classrooms) {
            // Attach each student to 1 or 2 random classrooms
            $student->enrolledClassrooms()->attach(
                $classrooms->random(rand(1, 2))->pluck('id')->toArray()
            );
        });

        $allClassrooms = \App\Models\Classroom::all();
        $allSubjects = \App\Models\Subject::all();

        // 6. Assign Subjects to Classrooms and Create Meetings
        foreach ($classrooms as $class) {
            // Randomly pick 3 subjects for this specific class
            $randomSubjects = $subjects->random(3);
            
            foreach ($randomSubjects as $subject) {
                // Step A: Link them in the pivot table (DO THIS FIRST)
                $class->subjects()->attach($subject->id, [
                    'teacher_id' => $class->teacher_id
                ]);

                // Step B: Create the 14-week schedule for this specific Class+Subject combo
                for ($i = 1; $i <= 14; $i++) {
                    Meeting::create([
                        'classroom_id' => $class->id,
                        'subject_id'   => $subject->id,
                        'week_number'  => $i,
                        'topic'        => "Week $i: " . $subject->name,
                        'meeting_date' => now()->addWeeks($i),
                    ]);
                }
            }
        }
    }
}