<x-project-features-layout :project="$project" :grouped-features="$groupedFeatures" :unassigned-features="$unassignedFeatures" :active-feature-id="$feature->id">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Feature: ') . $feature->title . ' for Project: ' . $project->name }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <div class="py-1">
            <div class="">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 text-gray-900">
                        <div class="flex flex-col">
                            @php
                                $randomMedia = null;
                                if ($project->media && $project->media->isNotEmpty()) {
                                    $randomMedia = $project->media->random();
                                }
                            @endphp

                            @if ($randomMedia)
                                <div class="overflow-hidden rounded">
                                    <img 
                                        src="{{ asset('storage/' . $randomMedia->path) }}" 
                                        alt="{{ $randomMedia->original_name }}" 
                                        class="w-full h-full object-contain"
                                    >
                                </div>
                            @endif


                            <div class="my-2">
                                @if ($project->description)
                                <p class="text-gray-800">{{ $project->description }}</p>
                                @else
                                <p class="text-gray-500 italic">No description provided.</p>
                                @endif
                            </div>
                            <div class="mb-4 text-sm text-gray-600">
                                <p>Created by: {{ $project->creator->name ?? 'Unknown User' }}</p>
                                <p>Created on: {{ $project->created_at->format('M d, Y H:i') }}</p>
                                <p>Last Updated: {{ $project->updated_at->format('M d, Y H:i') }}</p>
                            </div>

                        </div>

                    
                    
                    

                    
                        {{-- Link back to all projects --}}
                        <div class="mt-6">
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Back to All Projects
                            </a>
                        </div>

                    </div>


                    {{-- END Invite User Form --}}


                    {{-- List of Users Currently Working on the Project --}}
                    <div class="mt-8 p-2 border-t border-gray-200 flex flex-col justify-center items-left">
                        <h4 class="text-base font-semibold mb-4 self-center">Collaborators on this Project</h4>
                        @if ($project->users->isEmpty())
                            <p class="text-gray-600">No collaborators have been assigned to this project yet.</p>
                        @else
                            <ul class="list-disc list-inside text-gray-800">
                                @foreach ($project->users as $user)
                                    <li>{{ $user->name }} <span class="text-xs">({{ $user->email }})</span> </li>
                                @endforeach
                            </ul>
                        @endif
                        {{-- Only show the invite form if the current user is the project creator --}}
                        @if ($project->created_by === Auth::id())
                            <form method="POST" action="{{ route('projects.inviteUser', $project) }}" class="flex items-end gap-4 py-4">
                                @csrf

                                <div>
                                    <x-input-label for="email" :value="__('User Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                    {{-- Removed specific input-error here as we have general error display above --}}
                                </div>

                                <x-primary-button>
                                    {{ __('Invite User') }}
                                </x-primary-button>
                            </form>
                        @else
                            <p class="text-gray-600 text-sm">Only the project creator can invite users.</p>
                        @endif
                    </div>
                    {{-- You might add edit/delete buttons here --}}
                    {{-- And later, a section to manage users assigned to this project --}}

                </div>
            </div>
        </div>
    </x-slot>


    {{-- ALL THE EXISTING CONTENT OF YOUR edit.blade.php GOES HERE --}}
    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Feature Details</h3>

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

    <form method="POST" action="{{ route('projects.features.update', [$project, $feature]) }}">
        @csrf
        @method('PUT') {{-- Required for update method --}}

        <div class="mb-4">
            <x-input-label for="title" :value="__('Feature Title')" />
            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $feature->title)" required autofocus />
            <x-input-error :messages="$errors->get('title')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="time_allotted" :value="__('Time Allotted (minutes)')" />
            <x-text-input id="time_allotted" class="block mt-1 w-full" type="number" name="time_allotted" :value="old('time_allotted', $feature->time_allotted)" min="0" />
            <x-input-error :messages="$errors->get('time_allotted')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="department_id" :value="__('Department')" />
            <select id="department_id" name="department_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select a Department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id', $feature->department_id) == $department->id ? 'selected' : '' }}>
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
                    <option value="{{ $subdepartment->id }}" {{ old('subdepartment_id', $feature->subdepartment_id) == $subdepartment->id ? 'selected' : '' }}>
                        {{ $subdepartment->name }} ({{ $subdepartment->department->name ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('subdepartment_id')" class="mt-2" />
            <p class="text-sm text-gray-500 mt-1">
                Subdepartments are filtered based on the current feature's department.
            </p>
        </div>

        <div class="mb-4">
            <x-input-label for="sort_order" :value="__('Sort Order (Optional)')" />
            <x-text-input id="sort_order" class="block mt-1 w-full" type="number" name="sort_order" :value="old('sort_order', $feature->sort_order)" min="0" />
            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="progress" :value="__('Progress Status')" /> {{-- Updated label text --}}

            <select id="progress"
                    name="progress"
                    class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <!-- <option value="">-- Select Status --</option> {{-- Optional: A default, non-selectable option --}} -->
                <option value="not started" {{ old('progress') == 'not started' ? 'selected' : '' }}>Not Started</option>
                <option value="in progress" {{ old('progress') == 'in progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('progress') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="stuck" {{ old('progress') == 'stuck' ? 'selected' : '' }}>Stuck</option>
            </select>

            <x-input-error :messages="$errors->get('progress')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="content" :value="__('Content (Optional)')" />
            <textarea id="content" name="content" rows="6" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content', $feature->content) }}</textarea>
            <x-input-error :messages="$errors->get('content')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="deadline" :value="__('Deadline (Optional)')" />
            <x-text-input id="deadline" class="block mt-1 w-full" type="date" name="deadline" :value="old('deadline', $feature->deadline ? $feature->deadline->format('Y-m-d') : '')" />
            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Update Feature') }}
            </x-primary-button>
            <a href="{{ route('projects.features.index', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>

</x-project-features-layout>