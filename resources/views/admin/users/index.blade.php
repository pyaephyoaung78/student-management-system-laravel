@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="/dashboard">← Back to Admin Panel</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            User Management
        </h1>

        {{-- Top Bar --}}
        <div class="flex justify-between items-center mb-4">

            {{-- Search Form --}}
            <form action="{{ route('users.index') }}" method="GET">
                <input
                    type="text"
                    name="search"
                    placeholder="Search users..."
                    value="{{ request('search') }}"
                    class="border rounded px-4 py-2">

                <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                    Search
                </button>
            </form>

            {{-- Add Button --}}
            <a
                href="{{ route('users.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded">
                + Add User
            </a>

        </div>

        {{-- User Table --}}
        <div class="bg-white shadow rounded overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">

                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Role</th>
                        <th class="p-4 text-left">Actions</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $user->name }}
                        </td>

                        <td class="p-4">
                            {{ $user->email }}
                        </td>

                        <td class="p-4">
                            {{ $user->role }}
                        </td>
                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <td class="p-4 flex gap-2">

                            <a
                                href="{{ route('users.edit', $user->id) }}"
                                class="bg-yellow-400 text-white px-3 py-1 rounded">
                                Edit
                            </a>
                            @if(auth()->user()->role !== 'manager')
                            <form
                                action="{{ route('users.destroy', $user->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>

                            </form>
                            @endif
                        </td>
                        @endif
                    </tr>

                    @empty

                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>

@endsection