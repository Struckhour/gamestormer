<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Success Message Display --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Project Details</h3>

                    <form method="POST" action="{{ route('projects.update', $project) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Project Name Field --}}
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Description Field --}}
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Media Upload --}}
                        <div class="mb-4">
                            <x-input-label for="media" :value="__('Update Project Image (Optional)')" />
                            <input id="media" type="file" name="media"
                                class="block mt-1 w-full text-sm text-gray-500
                                       file:me-4 file:py-2 file:px-4
                                       file:rounded-lg file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-indigo-600 file:text-white
                                       hover:file:bg-indigo-700
                                       file:disabled:opacity-50 file:disabled:pointer-events-none
                                       dark:text-neutral-400 dark:file:bg-indigo-500 dark:file:hover:bg-indigo-400" />
                            <x-input-error :messages="$errors->get('media')" class="mt-2" />
                            <p class="mt-2 text-xs text-gray-500">Max 2MB. Accepted formats: JPG, PNG, GIF, SVG.</p>

                                @if ($project->media->count())
                                    <div class="mt-4 border-t pt-4">
                                        <p class="font-medium mb-2">Current Media:</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach ($project->media as $media)
                                                <div class="relative group border rounded-lg overflow-hidden shadow-sm">
                                                    <img
                                                        src="{{ asset('storage/' . $media->path) }}"
                                                        alt="{{ $media->original_name }}"
                                                        class="w-full h-48 object-cover"
                                                    >
                                                    <div class="p-2 text-sm text-gray-700 truncate">{{ $media->original_name }}</div>

                                                    {{-- Updated Form action --}}
                                                    <form action="{{ route('projects.media.destroy', [$project, $media]) }}" method="POST" class="absolute top-2 right-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                onclick="return confirm('Are you sure you want to delete this image?');"
                                                                class="bg-red-500 hover:bg-red-600 text-white p-1 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                                                title="Delete Image">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update Project') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>