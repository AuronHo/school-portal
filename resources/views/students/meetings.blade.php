<x-app-layout>
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
                                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
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
                                                <a href="#" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition shadow-md shadow-blue-100 uppercase">
                                                    Submit
                                                </a>
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