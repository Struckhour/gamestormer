<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    You are logged in as an **Administrator**!
                    <p>Total Users: {{ $totalUsers }}</p>
                    <p>Admin Users: {{ $adminUsers }}</p>
                    <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">Manage Users</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>