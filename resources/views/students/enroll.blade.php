<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enroll in New Classroom</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($availableClassrooms as $classroom)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Available Class</span>
                            <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $classroom->name }}</h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Teacher: <span class="font-semibold">{{ $classroom->teacher->name ?? 'No Teacher Assigned' }}</span>
                            </p>
                        </div>

                        <form action="{{ route('student.enroll.store', $classroom->id) }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-blue-600 transition uppercase tracking-widest shadow-lg shadow-gray-200">
                                Enroll Now
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500">No new classrooms available for enrollment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>