<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Feature for Project: ') . $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">New Feature Details</h3>

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('projects.features.store', $project) }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Feature Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="time_allotted" :value="__('Time Allotted (hours)')" />
                            <x-text-input id="time_allotted" class="block mt-1 w-full" type="number" name="time_allotted" :value="old('time_allotted')" required min="0" />
                            <x-input-error :messages="$errors->get('time_allotted')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="department_id" :value="__('Department')" />
                            <select id="department_id" name="department_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select a Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="subdepartment_id" :value="__('Subdepartment (Optional)')" />
                            <select id="subdepartment_id" name="subdepartment_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Select a Subdepartment</option>
                                @foreach ($subdepartments as $subdepartment)
                                    <option value="{{ $subdepartment->id }}" {{ old('subdepartment_id') == $subdepartment->id ? 'selected' : '' }}>
                                        {{ $subdepartment->name }} ({{ $subdepartment->department->name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('subdepartment_id')" class="mt-2" />
                            <p class="text-sm text-gray-500 mt-1">
                                To dynamically filter subdepartments based on the selected department, you would need JavaScript.
                            </p>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="sort_order" :value="__('Sort Order (Optional)')" />
                            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order')" min="0" />
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="progress" :value="__('Progress Notes (Optional)')" />
                            <textarea id="progress" name="progress" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('progress') }}</textarea>
                            <x-input-error :messages="$errors->get('progress')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="content" :value="__('Content (Optional)')" />
                            <textarea id="content" name="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deadline" :value="__('Deadline (Optional)')" />
                            <x-text-input id="deadline" class="block mt-1 w-full" type="date" name="deadline" :value="old('deadline')" />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Create Feature') }}
                            </x-primary-button>
                            <a href="{{ route('projects.features.index', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>