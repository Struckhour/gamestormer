<x-app-layout>
    <div class="py-12 bg-opacity-50">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white bg-opacity-90 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Assigned Tasks</h1>

                    @forelse ($grouped as $projectId => $features)
                        @php
                            $project = $features->first()->project;
                        @endphp

                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-2">
                                <a href="{{ route('projects.show', $project->id) }}" class="text-blue-700 hover:underline">
                                    {{ $project->name }}
                                </a>
                            </h2>

                            <table class="list-disc pl-5 space-y-1 ml-4 w-full table-auto text-center">
                                <thead class="bg-gray-700 text-gray-100">
                                    <tr>
                                        <th>Feature</th>
                                        <th>Deadline</th>
                                        <th>Progress</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                @foreach ($features as $feature)
                                <tr class="{{ $feature->progress === 'completed' ? 'bg-green-100' : '' }}">
                                    <td>
                                        <a href="{{ route('projects.features.show', [$feature->project_id, $feature->id]) }}"
                                        class="text-blue-500 hover:underline">
                                            {{ $feature->title }}
                                        </a>
                                    </td>
                                    <td>{{ $feature->deadline ? $feature->deadline->format('M d, Y') : '' }}</td>
                                    <td>{{ $feature->progress ?? '' }}</td>
                                    <td>{{ $feature->department->name ?? '' }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    @empty
                        <p class="text-gray-600">You have no tasks assigned.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


