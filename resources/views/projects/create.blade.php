
@extends('app') {{-- assuming you have a base layout --}}

@section('content')
<div class="container">
    <h1>Create Project</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf

        <div>
            <label for="name">Project Name:</label><br>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-top: 15px;">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
            @error('description')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" style="margin-top: 20px;">Create Project</button>
    </form>
</div>
@endsection
