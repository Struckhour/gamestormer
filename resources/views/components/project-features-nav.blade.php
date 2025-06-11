<ul class="space-y-2 text-sm">
    @if ($features->isEmpty())
        <li class="text-gray-500">No features yet.</li>
    @else
        @foreach ($features as $feature)
            <li class="{{ $feature->id == $activeFeatureId ? 'font-bold text-indigo-700' : 'text-gray-700 hover:text-indigo-600' }}">
                <a href="{{ route('projects.features.show', [$project, $feature]) }}" class="block p-1 rounded">
                    {{ $feature->title }}
                </a>
            </li>
        @endforeach
    @endif
</ul>