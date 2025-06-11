<ul class="space-y-4 text-sm">
    @if (empty($groupedFeatures) && $unassignedFeatures->isEmpty())
        <li class="text-gray-500">No features yet.</li>
    @else
        @foreach (collect($groupedFeatures)->sortBy('name') as $departmentId => $departmentGroup)
            <li>
                {{-- This div is the clickable header for departments --}}
                <div class="flex items-center justify-between cursor-pointer group js-collapsible-header">
                    <span class="font-bold text-gray-800 group-hover:text-indigo-600">{{ $departmentGroup['name'] }}</span>
                    {{-- Arrow Icon --}}
                    <svg class="w-4 h-4 text-gray-600 transition-transform duration-200 js-collapsible-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>


                <ul class="ml-4 mt-1 space-y-1 js-collapsible-content hidden">
                    {{-- Features directly under this department (no subdepartment) --}}
                    @if (!empty($departmentGroup['features_no_subdepartment']))
                        @foreach (collect($departmentGroup['features_no_subdepartment'])->sortBy('sort_order') as $feature)
                            <li class="{{ $feature->id == $activeFeatureId ? 'font-bold text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                                <a href="{{ route('projects.features.show', [$project, $feature]) }}" class="block p-1 rounded">
                                    {{ $feature->title }}
                                </a>
                            </li>
                        @endforeach
                    @endif

                    {{-- Subdepartment Section --}}
                    @foreach (collect($departmentGroup['subdepartments'])->sortBy('name') as $subdepartmentId => $subdepartmentGroup)
                        <li>
                            {{-- This div is the clickable header for subdepartments --}}
                            <div class="flex items-center justify-between cursor-pointer group js-collapsible-header">
                                <span class="font-semibold text-gray-700 group-hover:text-indigo-600">{{ $subdepartmentGroup['name'] }}</span>
                                {{-- Arrow Icon --}}
                                <svg class="w-4 h-4 text-gray-600 transition-transform duration-200 js-collapsible-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                            </div>

                            {{-- This UL is the content to be collapsed/expanded for subdepartments --}}
                            {{-- Add 'hidden' to make it start collapsed --}}
                            <ul class="ml-4 mt-1 space-y-1 js-collapsible-content hidden">
                                @foreach (collect($subdepartmentGroup['features'])->sortBy('sort_order') as $feature)
                                    <li class="{{ $feature->id == $activeFeatureId ? 'font-bold text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                                        <a href="{{ route('projects.features.show', [$project, $feature]) }}" class="block p-1 rounded">
                                            {{ $feature->title }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endforeach

        {{-- Features with Null Department/Subdepartment (at the bottom) --}}
        @if ($unassignedFeatures->isNotEmpty())
            <li class="border-t border-gray-200 pt-4 mt-4">
                {{-- This div is the clickable header for unassigned features --}}
                <div class="flex items-center justify-between cursor-pointer group js-collapsible-header">
                    <span class="font-bold text-gray-800 group-hover:text-indigo-600">Unassigned Features</span>
                    {{-- Arrow Icon --}}
                    <svg class="w-4 h-4 text-gray-600 transition-transform duration-200 js-collapsible-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>

                {{-- This UL is the content to be collapsed/expanded for unassigned features --}}
                {{-- Add 'hidden' to make it start collapsed --}}
                <ul class="ml-4 mt-1 space-y-1 js-collapsible-content hidden">
                    @foreach ($unassignedFeatures->sortBy('sort_order') as $feature)
                        <li class="{{ $feature->id == $activeFeatureId ? 'font-bold text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                            <a href="{{ route('projects.features.show', [$project, $feature]) }}" class="block p-1 rounded">
                                {{ $feature->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endif
</ul>