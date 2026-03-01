<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ __('My Learning Space') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 italic">
                    Welcome back, {{ auth()->user()->name }} — 6th Semester Information Systems
                </p>
            </div>
            
            <div class="mt-4 md:mt-0">
                <div class="bg-blue-50 px-4 py-2 rounded-xl border border-blue-100 text-center">
                    <span class="block text-[10px] font-black text-blue-600 uppercase tracking-widest">Current Status</span>
                    <span class="text-lg font-bold text-blue-800">
                        {{ $classrooms->count() > 0 ? 'Enrolled' : 'Not Enrolled' }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Toast Notification for Enrollment/Unenrollment Success --}}
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 p-4 bg-green-900 text-white rounded-2xl shadow-lg flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm font-bold">{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            <h3 class="text-lg font-bold text-gray-700 mb-6 uppercase tracking-wider flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                My Classroom
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($classrooms as $class)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="h-2 bg-blue-600"></div>
                        
                        <div class="p-8">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black text-green-500 uppercase tracking-widest bg-green-50 px-2 py-1 rounded">Active</span>
                            </div>

                            <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $class->name }}</h4>
                            <p class="text-gray-500 text-xs mb-6 uppercase tracking-tight">Universitas Internasional Batam</p>
                            
                            <hr class="border-gray-50 mb-6">

                            <div class="space-y-3">
                                {{-- Enter Class --}}
                                <a href="{{ route('student.classrooms.subjects', $class->id) }}" 
                                   class="flex items-center justify-center w-full px-4 py-3 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-gray-200 hover:shadow-blue-200 transition-all duration-300">
                                    Enter Class
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>

                                {{-- Unenroll / Remove Class --}}
                                <form action="{{ route('student.unenroll', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this class? All your task data for this class will remain but you will lose access.')">
                                    @csrf
                                    <button type="submit" class="w-full text-[10px] text-red-400 font-black uppercase tracking-widest hover:text-red-600 transition-colors duration-200 py-2">
                                        Unenroll from Class
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Empty State with Enroll Button --}}
                    <div class="col-span-full py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl shadow-sm text-gray-400 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-bold text-lg">Your semester is empty!</p>
                        <p class="text-gray-400 text-sm mb-8">You haven't joined any classrooms yet.</p>
                        
                        <a href="{{ route('student.enroll.index') }}" 
                           class="inline-flex items-center px-8 py-3 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-xl shadow-blue-100 transition-all duration-300">
                            Find & Enroll in Class
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>