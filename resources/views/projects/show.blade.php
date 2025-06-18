<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2 border-b border-gray-200">{{ $project->name }}</h3>
                    <div class="grid grid-cols-2">
                        <div>
                            <div class="py-2 my-2">
                                <a href="{{ route('projects.features.index', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-base text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Features/Tasks
                                </a>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-700 font-semibold mb-1">Description:</p>
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
                        <div class="">


                            @php
                                $randomMedia = null;
                                if ($project->media && $project->media->isNotEmpty()) {
                                    $randomMedia = $project->media->random();
                                }
                            @endphp

                            @if ($randomMedia)
                                <div class="aspect-[2/2] overflow-hidden rounded">
                                    <img 
                                        src="{{ asset('storage/' . $randomMedia->path) }}" 
                                        alt="{{ $randomMedia->original_name }}" 
                                        class="w-full h-full object-contain"
                                    >
                                </div>
                            @endif

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
                    <div class="mt-8 p-6 border-t border-gray-200 flex flex-col justify-center items-center">
                        <h4 class="text-xl font-semibold mb-4">Collaborators on this Project</h4>
                        @if ($project->users->isEmpty())
                            <p class="text-gray-600">No collaborators have been assigned to this project yet.</p>
                        @else
                            <ul class="list-disc list-inside text-gray-800">
                                @foreach ($project->users as $user)
                                    <li>{{ $user->name }} ({{ $user->email }})</li>
                                @endforeach
                            </ul>
                        @endif
                        {{-- Only show the invite form if the current user is the project creator --}}
                        @if ($project->created_by === Auth::id())
                            <form method="POST" action="{{ route('projects.inviteUser', $project) }}" class="flex items-end gap-4">
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
    </div>
</x-app-layout>