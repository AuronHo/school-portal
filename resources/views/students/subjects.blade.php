<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $classroom->name }} - Subjects
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subjects as $subject)
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                        <h3 class="text-lg font-bold text-gray-900">{{ $subject->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4">6th Semester Information Systems</p>
                        
                        <a href="{{ route('student.classrooms.meetings', [$classroom->id, $subject->id]) }}" 
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-xs font-bold uppercase rounded-lg hover:bg-blue-700">
                            View Weekly Tasks
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>