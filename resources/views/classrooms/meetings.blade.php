<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('classrooms.subjects', $classroom->id) }}" class="hover:text-blue-600">{{ $classroom->name }}</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $subject->name }}</span>
        </div>
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Weekly Schedule</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
             @foreach($meetings as $meeting)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-8 {{ $meeting->meeting_date < now() ? 'border-gray-300' : 'border-green-500' }}">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div class="mb-4 md:mb-0">
                                <div class="text-sm font-bold text-blue-600 uppercase">Week {{ $meeting->week_number }}</div>
                                <h3 class="text-xl font-bold text-gray-900">{{ $meeting->topic }}</h3>
                                <p class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($meeting->meeting_date)->format('d M Y') }}</p>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('meetings.roll-call', [$classroom->id, $subject->id, $meeting->id]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                    Roll Call
                                </a>

                                <a href="{{ route('meetings.tasks.create', [$classroom->id, $subject->id, $meeting->id]) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Add Task
                                </a>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="mt-4 border-t border-gray-50 pt-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs font-semibold text-gray-500 uppercase italic text-[10px]">Weekly Tasks:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $meeting->tasks->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $meeting->tasks->count() }} {{ Str::plural('Task', $meeting->tasks->count()) }}
                                    </span>
                                </div>

                                @if($meeting->tasks->count() > 0)
                                    <button @click="open = !open" class="text-[11px] text-blue-600 hover:text-blue-800 font-bold flex items-center transition uppercase tracking-tight">
                                        <span x-text="open ? 'Close' : 'View Details'"></span>
                                        <svg class="w-3 h-3 ml-1 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <div x-show="open" x-transition.origin.top class="mt-3 space-y-2">
                                @foreach($meeting->tasks as $task)
                                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center">
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-800">{{ $task->title }}</h4>
                                            <p class="text-[10px] font-medium text-indigo-600 mt-0.5">
                                                Submissions: {{ $task->submissions_count ?? 0 }} / {{ $classroom->students->count() }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            @if($task->file_path)
                                                <a href="{{ asset('storage/' . $task->file_path) }}" target="_blank" class="p-1.5 bg-white border border-gray-200 rounded shadow-sm hover:text-blue-600 transition" title="Download Material">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                </a>
                                            @endif
                                            <a href="#" class="text-[10px] bg-white border border-gray-200 px-2 py-1.5 rounded font-bold hover:bg-gray-100 transition shadow-sm uppercase">Grade</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</x-app-layout>