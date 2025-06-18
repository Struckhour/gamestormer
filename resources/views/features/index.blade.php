<x-project-features-layout :project="$project" :grouped-features="$groupedFeatures" :unassigned-features="$unassignedFeatures" :active-feature-id="null">

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

    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900">Features/Tasks</h3>
        <a href="{{ route('projects.features.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Add New Feature
        </a>
    </div>

    {{-- Success Message Display --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($features->isEmpty())
        <p class="text-gray-600">No features found for this project. Start by adding one!</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Allotted</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Member</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdepartment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($features as $feature)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('projects.features.show', [$project, $feature]) }}" class="text-blue-600 hover:underline">
                                    {{ mb_strlen($feature->title) > 20 ? mb_substr($feature->title, 0, 20) . '...' : $feature->title; }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->time_allotted ? $feature->time_allotted . " min." : "" }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @foreach ($feature->users as $user)
                                <div>
                                    {{ $user->name }}
                                </div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->deadline ? $feature->deadline->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($feature->status->name ?? '', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->department->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->subdepartment->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('projects.features.edit', [$project, $feature]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                <form action="{{ route('projects.features.destroy', [$project, $feature]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this feature?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


</x-project-features-layout>