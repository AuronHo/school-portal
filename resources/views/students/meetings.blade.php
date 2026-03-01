<x-app-layout>
    <div x-data="{ show: true }" 
        x-show="show" 
        x-init="setTimeout(() => show = false, 3000)"
        x-transition:leave="transition ease-in duration-1000"
        x-transition:leave-start="opacity-100"
        @if (session('status'))
            class="fixed top-5 right-5 z-50 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center space-x-3 border border-gray-700"
        @else
            class="hidden"
        @endif
    >
        <div class="bg-green-500 p-1 rounded-full">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <p class="text-sm font-bold tracking-wide">{{ session('status') }}</p>
    </div>
    
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('student.classrooms.subjects', $classroom->id) }}" class="hover:text-blue-600">{{ $classroom->name }}</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $subject->name }}</span>
        </div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Weekly Learning & Tasks</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                @foreach($meetings as $meeting)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-8 {{ $meeting->meeting_date < now() ? 'border-gray-300' : 'border-blue-500' }}">
                        <div class="p-6">
                            {{-- NEW: Attendance & Global Status Row --}}
                            @php
                                $attendance = $meeting->rollCalls->where('user_id', auth()->id())->first();
                                $allTasksSubmitted = $meeting->tasks->count() > 0 && $meeting->tasks->every(function($t) {
                                    return $t->submissions->where('user_id', auth()->id())->count() > 0;
                                });
                            @endphp
                            <div class="flex flex-wrap items-center gap-2 mb-4">
                               @if($attendance)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest 
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        {{ $attendance->status }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 text-gray-400">
                                        No Attendance Record
                                    </span>
                                @endif

                                @if($allTasksSubmitted)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">
                                        All Tasks Done ✓
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
                                <div>
                                    <div class="text-sm font-bold text-blue-600 uppercase tracking-tight">Week {{ $meeting->week_number }}</div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $meeting->topic }}</h3>
                                    <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</p>
                                </div>
                                
                                <div class="mt-4 md:mt-0">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-black uppercase rounded-full">
                                        {{ $meeting->tasks->count() }} Assignment(s)
                                    </span>
                                </div>
                            </div>

                            @if($meeting->tasks->count() > 0)
                                <div class="mt-4 space-y-3">
                                    @foreach($meeting->tasks as $task)
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col gap-4">
                                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-bold text-gray-800">{{ $task->title }}</h4>
                                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                                                    <p class="text-[10px] text-red-500 font-bold mt-2 uppercase">Due: {{ $task->due_date->format('d M, H:i') }}</p>
                                                </div>

                                                <div class="flex items-center space-x-2">
                                                    @if($task->file_path)
                                                        <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="px-3 py-2 bg-white border border-gray-200 rounded-lg text-xs font-bold hover:bg-gray-50 transition shadow-sm flex items-center">
                                                            <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                            Material
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-2 p-3 bg-white rounded-lg border border-gray-200">
                                                @php
                                                    $submission = $task->submissions->where('student_id', auth()->id())->first();
                                                @endphp

                                                @if($submission)
                                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="p-2 bg-green-100 rounded-full text-green-600">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none">Your Submission</p>
                                                                <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-xs font-bold text-blue-600 hover:underline truncate max-w-[200px] block">
                                                                    View Submitted Document
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <div x-data="{ showUpload: false }">
                                                            <button @click="showUpload = !showUpload" class="text-[10px] bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1 px-3 rounded uppercase transition">
                                                                <span x-text="showUpload ? 'Cancel' : 'Resubmit File'"></span>
                                                            </button>

                                                            <div x-show="showUpload" x-transition class="mt-3">
                                                                <form action="{{ route('tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                                                                    @csrf
                                                                    <input type="file" name="file" required class="text-[10px] text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-blue-50 file:text-blue-700">
                                                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white text-[10px] font-bold rounded uppercase">Update</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <form action="{{ route('tasks.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                                                        @csrf
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">Not Submitted Yet</p>
                                                            <input type="file" name="file" required class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                                        </div>
                                                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition uppercase shadow-sm">
                                                            Upload Assignment
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400 italic mt-2 italic">No assignments posted for this week.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>