<x-project-features-layout :project="$project" :grouped-features="$groupedFeatures" :unassigned-features="$unassignedFeatures" :active-feature-id="$feature->id">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feature Details: ') . $feature->title }}
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


    {{-- ALL THE EXISTING CONTENT OF YOUR show.blade.php GOES HERE --}}
    <h3 class="text-2xl font-bold mb-4">{{ $feature->title }}</h3>

    {{-- Success Message Display --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Feature Details (already existing) --}}
    <div class="mb-4">
        <p class="text-gray-700 font-semibold mb-1">Time Allotted:</p>
        <p class="text-gray-800">{{ $feature->time_allotted }} minutes</p>
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


    <h3 class="font-semibold mb-2">Assigned Team Members:</h3>
    <ul>
        @foreach ($feature->users as $user)
            <li class="flex items-center gap-2">
                <form action="{{ route('feature-user.remove') }}" method="POST">
                    @csrf
                    <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <button type="submit" class="text-black hover:text-red-800 hover:bg-gray-400 my-2 rounded text-base w-4 bg-gray-200 border border-black">×</button>
                </form>
                <span>{{ $user->name }}</span>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('feature-user.assign') }}" method="POST" id="assign-user-form">
        @csrf
        <input type="hidden" name="feature_id" value="{{ $feature->id }}">

        <label for="user_id">Assign a team member:</label>
        <select name="user_ids[]" id="user_id" onchange="document.getElementById('assign-user-form').submit()" class="py-1 rounded">
            <option value="">{{!$availableUsers->isEmpty() ? '-- Select a project member --' : 'All members have been assigned'}}</option>
            @foreach ($availableUsers as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </form>

    <form action="{{ route('feature.assignStatus') }}" method="POST" class ="py-2">
        @csrf
        <input type="hidden" name="feature_id" value="{{ $feature->id }}">

        <label for="status_id">Progress Status:</label>
        <select name="status_id" id="status_id" class="py-1 rounded" onchange="this.form.submit()">
            @foreach ($statuses as $status)
                <option value="{{ $status->id }}" @selected($feature->status_id == $status->id)>
                    {{ $status->name }}
                </option>
            @endforeach
        </select>
    </form>

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

    {{-- Feature Action Buttons (already existing) --}}
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

    {{-- Comments Section (already existing) --}}
    <div class="mt-8 pt-8 border-t border-gray-200">
        <h4 class="text-xl font-semibold mb-4">Comments ({{ $feature->comments->count() }})</h4>

        @if ($feature->comments->isEmpty())
            <p class="text-gray-600 mb-6">No comments yet. Be the first to add one!</p>
        @else
            <div class="space-y-6 mb-6">
                @foreach ($feature->comments->sortByDesc('created_at') as $comment)
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-medium text-gray-900">{{ $comment->creator->name ?? 'Deleted User' }}</p>
                                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            {{-- Delete Button (Visible only to creator or admin) --}}
                            @if (Auth::id() == $comment->created_by || Auth::user()->is_admin)
                                <form action="{{ route('projects.features.comments.destroy', [$project, $feature, $comment]) }}" method="POST" class="inline-block ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 text-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="text-gray-800">{{ $comment->comment }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Add New Comment Form --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Add a new comment</h4>

            {{-- Validation Errors for comment form --}}
            @if ($errors->has('comment'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        <li>{{ $errors->first('comment') }}</li>
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projects.features.comments.store', [$project, $feature]) }}">
                @csrf
                <div class="mb-4">
                    <textarea id="comment" name="comment" rows="4" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Type your comment here...">{{ old('comment') }}</textarea>
                </div>
                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('Post Comment') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

</x-project-features-layout>