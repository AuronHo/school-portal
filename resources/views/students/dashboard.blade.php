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
            
            <div class="mt-4 md:mt-0 flex space-x-4">
                <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 text-center">
                    <span class="block text-xs font-bold text-blue-600 uppercase tracking-widest">Enrolled</span>
                    <span class="text-lg font-bold text-blue-800">{{ $classrooms->count() }} Classes</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-bold text-gray-700 mb-6 uppercase tracking-wider flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                My Enrolled Classrooms
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
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active</span>
                            </div>

                            <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $class->name }}</h4>
                            <p class="text-gray-500 text-xs mb-6 uppercase tracking-tight">Universitas Internasional Batam</p>
                            
                            <hr class="border-gray-50 mb-6">

                            <a href="{{ route('student.classrooms.subjects', $class->id) }}" 
                               class="flex items-center justify-center w-full px-4 py-3 bg-gray-900 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-gray-200 hover:shadow-blue-200 transition-all duration-300">
                                Enter Class
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 text-center">
                        <p class="text-gray-500 font-medium">You are not enrolled in any classes for this semester yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>