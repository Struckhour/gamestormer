<x-app-layout>
    {{-- Header Slot --}}


    {{-- Main Content Area --}}
    <div class="py-12 bg-opacity-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white bg-opacity-80 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">My Projects</h3>
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create New Project
                        </a>
                    </div>

                    {{-- Success Message Display --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($projects->isEmpty())
                        <p class="text-gray-600">You haven't created any projects yet. Go ahead and create your first one!</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($projects as $project)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                                    {{-- Project Name --}}
                                    <h4 class="text-xl font-semibold text-gray-800 mb-2">
                                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:underline">
                                            {{ $project->name }}
                                        </a>
                                    </h4>

                                    {{-- Project Description --}}
                                    @if ($project->description)
                                        <p class="text-gray-700 text-sm mb-3">{{ $project->description }}</p>
                                    @else
                                        <p class="text-gray-500 text-sm mb-3 italic">No description provided.</p>
                                    @endif
                                    @if ($project->media)
                                        @foreach ($project->media as $media)
                                            <img 
                                                src="{{ asset('storage/' . $media->path) }}" 
                                                alt="{{ $media->original_name }}" 
                                                class="rounded mb-4 max-w-full h-auto"
                                            >
                                        @endforeach
                                    @endif
                                    <div class="text-xs text-gray-500">
                                        Created: {{ $project->created_at->format('M d, Y H:i') }}
                                    </div>
                                    {{-- You might add edit/delete buttons here later --}}
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>