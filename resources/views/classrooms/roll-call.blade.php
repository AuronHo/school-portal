<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('classrooms.subjects', $classroom->id) }}" class="hover:text-blue-600">{{ $classroom->name }}</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $subject->name }}</span>
        </div>

        <div class="flex justify-between items-end">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('Roll Call') }} — Week {{ $meeting->week_number }}
                </h2>
                <p class="text-gray-600 mt-1">Topic: <span class="font-medium">{{ $meeting->topic }}</span></p>
            </div>
            <div class="text-right">
                <span class="text-xs font-bold text-blue-600 uppercase tracking-widest">Meeting Date</span>
                <p class="text-sm text-gray-800 font-medium">{{ $meeting->meeting_date->format('l, d M Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <form action="{{ route('meetings.roll-call.store', $meeting->id) }}" method="POST">
                @csrf
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                    
                    <div class="grid grid-cols-12 bg-gray-50 border-b border-gray-100 px-6 py-4">
                        <div class="col-span-6">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Student Name</span>
                        </div>
                        <div class="col-span-6 flex justify-around">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">P</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">A</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">L</span>
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-500">E</span>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @forelse($students as $student)
                            <div class="grid grid-cols-12 px-6 py-4 items-center hover:bg-blue-50/30 transition duration-150">
                                <div class="col-span-6">
                                    <p class="font-bold text-gray-900">{{ $student->name }}</p>
                                    <p class="text-xs text-gray-400">ID: {{ $student->id }}</p>
                                </div>
                                
                                <div class="col-span-6 flex justify-around">
                                    @foreach(['present', 'absent', 'late', 'excused'] as $status)
                                        <label class="cursor-pointer p-2 rounded-full hover:bg-white transition shadow-none hover:shadow-sm">
                                            <input type="radio" 
                                                   name="attendance[{{ $student->id }}]" 
                                                   value="{{ $status }}" 
                                                   class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 focus:ring-offset-0"
                                                   {{ ($attendanceRecords[$student->id] ?? 'present') == $status ? 'checked' : '' }}>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center">
                                <p class="text-gray-500">No students enrolled in this classroom.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="px-6 py-6 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-xs text-gray-500">
                            <p><strong>P</strong>: Present | <strong>A</strong>: Absent | <strong>L</strong>: Late | <strong>E</strong>: Excused</p>
                            <p class="mt-1">Changes are saved using <code class="bg-gray-200 px-1 rounded">updateOrCreate</code> logic.</p>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <button type="reset" class="text-sm font-medium text-gray-400 hover:text-gray-600 transition">
                                Reset
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-10 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transform active:scale-95 transition-all">
                                Save Attendance
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('classrooms.meetings', [$classroom->id, $subject->id]) }}" class="text-sm text-gray-500 hover:text-blue-600 font-medium transition">
                    &larr; Back to Meeting List
                </a>
            </div>
        </div>
    </div>
</x-app-layout>