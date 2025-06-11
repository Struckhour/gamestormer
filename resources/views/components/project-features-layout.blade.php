<x-app-layout>
    <x-slot name="header">
        {{ $header ?? '' }} {{-- Allows views to define header content --}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex">
                    {{-- Left Sidebar for Feature Navigation --}}
                    <div class="w-1/4 max-w-72 pr-6 border-r border-gray-200">
                        <h4 class="text-lg font-semibold mb-4 text-gray-800">
                            <a href="{{ route('projects.features.index', $project)}}" class="hover:text-blue-800 underline">Project Features</a></h4>
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