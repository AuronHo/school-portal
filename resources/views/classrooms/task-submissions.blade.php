<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('classrooms.subjects', $classroom->id) }}" class="hover:text-blue-600">{{ $classroom->name }}</a>
            <span>/</span>
            <a href="{{ route('classrooms.meetings', [$classroom->id, $subject->id]) }}" class="hover:text-blue-600">{{ $subject->name }}</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $task->title }}</span>
        </div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Submissions</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Task summary card --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Due: {{ \Carbon\Carbon::parse($task->due_date)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-3xl font-black text-indigo-600">{{ $submissions->count() }}</span>
                        <span class="text-gray-400 text-sm"> / {{ $students->count() }}</span>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Submitted</p>
                    </div>
                </div>
            </div>

            {{-- Student submission list --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-black text-gray-500 uppercase tracking-widest">Student Status</h3>
                </div>

                <ul class="divide-y divide-gray-50">
                    @foreach($students as $student)
                        @php
                            $submission = $submissions->firstWhere('student_id', $student->id);
                        @endphp
                        <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
                                    {{ $submission ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-400' }}">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $student->email }}</p>
                                </div>
                            </div>

                            <div class="flex items-center space-x-3">
                                @if($submission)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-green-100 text-green-700">
                                        Submitted
                                    </span>
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank"
                                       class="p-1.5 border border-gray-200 rounded hover:text-blue-600 hover:border-blue-300 transition" title="Download submission">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                    @if($submission->grade)
                                        <span class="text-xs font-bold text-gray-600">Grade: {{ $submission->grade }}</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-gray-100 text-gray-400">
                                        Not Submitted
                                    </span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
