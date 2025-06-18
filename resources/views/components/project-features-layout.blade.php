<x-app-layout>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex">
                    {{-- Left Sidebar for Feature Navigation --}}
                    <div class="w-1/4 max-w-96 pr-6 border-r border-gray-200">
                        <div class="flex items-end justify-start gap-2">
                            <h4 class="text-lg font-semibold text-gray-800">
                                {{ $project->name }}
                            </h4>
                            <span class="text-sm text-blue-900">
                                <a href="{{ route('projects.edit', $project) }}">(edit project details)</a>
                            </span>
                        </div>
                        {{ $sidebar ?? '' }} {{-- Slot for the sidebar content (our feature tree component) --}}
                    </div>

                    {{-- Main Content Area --}}
                    <div class="w-3/4 pl-6">
                        {{ $slot }} {{-- Main content of the feature page (e.g., show, create, edit form) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>