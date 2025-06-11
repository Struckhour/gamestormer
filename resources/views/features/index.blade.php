<x-project-features-layout :project="$project" :grouped-features="$groupedFeatures" :unassigned-features="$unassignedFeatures" :active-feature-id="null">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('projects.index') }}" class="underline hover:text-blue-800">My Projects</a>
            >
            <a href="{{ route('projects.show', $project) }}" class="underline hover:text-blue-800">{{$project->name}}</a>
            > Features
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-project-features-nav
            :project="$project"
            :grouped-features="$groupedFeatures" 
            :unassigned-features="$unassignedFeatures"
            :active-feature-id="null" {{-- No specific feature active on index page --}}
        />
    </x-slot>


    {{-- ALL THE EXISTING CONTENT OF YOUR index.blade.php GOES HERE --}}
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium text-gray-900">Manage Project Features</h3>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subdepartment</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
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
                                {{ $feature->department->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->subdepartment->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $feature->deadline ? $feature->deadline->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($feature->progress, 50) }}
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

    <div class="mt-6">
        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            Back to Project Details
        </a>
    </div>

</x-project-features-layout>