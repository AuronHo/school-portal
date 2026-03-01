<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900">
    <h2 class="text-2xl font-bold mb-6">Teacher Control Center</h2>

    @if(isset($notifications) && count($notifications) > 0)
        <div class="mb-10 max-w-7xl">
            <h3 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4 flex items-center">
                <span class="relative flex h-2 w-2 mr-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                Classroom Management Alerts
            </h3>
            <div class="space-y-3">
                @foreach($notifications as $note)
                    <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm flex items-start space-x-4">
                        {{-- Dynamic Icon Container --}}
                        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center 
                            {{ $note['color'] == 'blue' ? 'bg-blue-50 text-blue-600' : 'bg-amber-50 text-amber-600' }}">
                            
                            @if($note['type'] == 'task')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            @endif
                        </div>

                        <div class="flex-1">
                            <p class="text-[10px] font-black uppercase tracking-widest {{ $note['color'] == 'blue' ? 'text-blue-500' : 'text-amber-600' }}">
                                {{ $note['title'] }}
                            </p>
                            <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $note['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- CRUD button -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900">Your Managed Classes</h3>
        <a href="{{ route('classrooms.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">+ Add Class</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
       @foreach($classrooms as $class)
            <div class="relative bg-white border border-gray-100 rounded-xl shadow-sm p-6 hover:shadow-md transition-all duration-200 group">
                
                <div class="flex justify-between items-start mb-4">
                    <div class="pr-8">
                        <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                            {{ $class->name }}
                        </h4>
                        <p class="text-xs font-medium text-gray-400 tracking-wider">
                            Academic Year {{ $class->academic_year }}
                        </p>
                    </div>

                    <form action="{{ route('classrooms.destroy', $class->id) }}" method="POST" 
                        onsubmit="return confirm('Are you sure you want to delete this class? This cannot be undone.');"
                        class="absolute top-4 right-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors p-2 rounded-full hover:bg-red-50" title="Delete Class">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="flex items-center space-x-4 py-3 border-t border-b border-gray-50 my-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="font-semibold">{{ $class->students_count ?? 0 }}</span>
                        <span class="ml-1">Students</span>
                    </div>
                    <div class="h-4 w-px bg-gray-200"></div>
                    <div class="flex items-center">
                        <span class="flex h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                        <span class="text-xs font-medium text-gray-500 italic">Active</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-3 mt-4">
                    <a href="{{ route('classrooms.subjects', $class->id) }}" 
                    class="flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-blue-700 shadow-sm transition">
                        View Subjects
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
</x-app-layout>
