<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feature Details: ') . $feature->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-2xl font-bold mb-4">{{ $feature->title }}</h3>

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Time Allotted:</p>
                        <p class="text-gray-800">{{ $feature->time_allotted }} hours</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Department:</p>
                        <p class="text-gray-800">{{ $feature->department->name ?? 'N/A' }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Subdepartment:</p>
                        <p class="text-gray-800">{{ $feature->subdepartment->name ?? 'N/A' }}</p>
                    </div>

                    @if ($feature->sort_order !== null)
                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Sort Order:</p>
                        <p class="text-gray-800">{{ $feature->sort_order }}</p>
                    </div>
                    @endif

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Progress:</p>
                        @if ($feature->progress)
                            <p class="text-gray-800">{{ $feature->progress }}</p>
                        @else
                            <p class="text-gray-500 italic">No progress notes provided.</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Content:</p>
                        @if ($feature->content)
                            <p class="text-gray-800">{{ $feature->content }}</p>
                        @else
                            <p class="text-gray-500 italic">No content provided.</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 font-semibold mb-1">Deadline:</p>
                        <p class="text-gray-800">{{ $feature->deadline ? $feature->deadline->format('M d, Y') : 'N/A' }}</p>
                    </div>

                    <div class="mb-4 text-sm text-gray-600">
                        <p>Created on: {{ $feature->created_at->format('M d, Y H:i') }}</p>
                        <p>Last Updated: {{ $feature->updated_at->format('M d, Y H:i') }}</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('projects.features.index', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to All Features
                        </a>
                        <a href="{{ route('projects.features.edit', [$project, $feature]) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Feature
                        </a>
                        <form action="{{ route('projects.features.destroy', [$project, $feature]) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this feature?')">
                                Delete Feature
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>