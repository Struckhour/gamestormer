<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Project') }}
        </h2>
    </x-slot>

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

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Project Details</h3>

                    <form method="POST" action="{{ route('projects.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Project Name Field --}}
                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Project Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        {{-- Description Field --}}
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="media" :value="__('Project Image (Optional)')" />
                            <input id="media"
                                type="file"
                                name="media" {{-- IMPORTANT: This 'name' is what you'll access in your controller --}}
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
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create Project') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
