<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Assign Task') }}: Week {{ $meeting->week_number }}
        </h2>
        <p class="text-sm text-gray-500">{{ $subject->name }} - {{ $classroom->name }}</p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <form action="{{ route('meetings.tasks.store', $meeting->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Task Title</label>
                            <input type="text" name="title" required class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Algebra Quiz 1">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Instructions</label>
                            <textarea name="description" rows="4" required class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Describe the task requirements..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Due Date</label>
                                <input type="datetime-local" name="due_date" required class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wider mb-2">Attachment (Optional)</label>
                                <input type="file" name="file" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-50 flex justify-end items-center space-x-4">
                        <a href="{{ url()->previous() }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2.5 rounded-lg font-bold shadow-sm transition">
                            Post Assignment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>