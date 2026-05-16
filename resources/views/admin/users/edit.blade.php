@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="{{ route('users.index') }}">← Back to User List</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Edit User
        </h1>

        {{-- Edit User Form --}}
        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-bold mb-2">Role:</label>
                    <select
                        name="role"
                        id="role"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-yellow-400 text-black px-6 py-2 rounded hover:bg-yellow-500">
                        Update User
                    </button>

                    <a
                        href="{{ route('users.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection