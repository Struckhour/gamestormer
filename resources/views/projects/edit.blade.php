<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <strong>Whoops! Something went wrong.</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Project Details</h3>

                    <form method="POST" action="{{ route('projects.update', $project) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Project Name --}}
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Upload New Media --}}
                        <div class="mb-6">
                            <x-input-label for="media" :value="__('Upload New Media')" />
                            <input id="media" type="file" name="media[]" multiple
                                class="block mt-1 w-full text-sm text-gray-500
                                    file:me-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-600 file:text-white
                                    hover:file:bg-indigo-700
                                    file:disabled:opacity-50 file:disabled:pointer-events-none
                                    dark:text-neutral-400 dark:file:bg-indigo-500 dark:file:hover:bg-indigo-400" />
                            <x-input-error :messages="$errors->get('media')" class="mt-2" />
                            <p class="mt-2 text-xs text-gray-500">Max 2MB each. JPG, PNG, GIF, SVG accepted.</p>
                        </div>

                        {{-- Existing Media with Delete Checkboxes --}}
                        @if ($project->media->count())
                            <div class="mb-6 border-t pt-4">
                                <p class="font-medium mb-2">Current Media:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach ($project->media as $media)
                                        <div class="relative border rounded-lg overflow-hidden shadow-sm group">
                                            <img src="{{ asset('storage/' . $media->path) }}" alt="{{ $media->original_name }}"
                                                class="w-full h-48 object-cover">
                                            <div class="p-2 text-sm text-gray-700 truncate">{{ $media->original_name }}</div>

                                            {{-- Delete checkbox --}}
                                            <div class="absolute top-2 right-2 bg-white bg-opacity-80 rounded">
                                                <label class="flex items-center space-x-1 text-xs text-red-600 font-semibold">
                                                    <input type="checkbox" name="delete_media[]" value="{{ $media->id }}" class="text-red-500">
                                                    <span>Remove</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
