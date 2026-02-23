<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assign Subject to {{ $classroom->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm rounded-xl">
                <form method="POST" action="{{ route('classrooms.subjects.attach', $classroom->id) }}">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Subject</label>
                        <select name="subject_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500">
                            @foreach($availableSubjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('classrooms.subjects', $classroom->id) }}" class="text-gray-500">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                            Confirm Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>