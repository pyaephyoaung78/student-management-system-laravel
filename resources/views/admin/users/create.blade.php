@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="{{ route('admin.users.index') }}">← Back</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Add New User
        </h1>

        {{-- Add Student Form --}}
        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password:</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            required>
                        <!-- Toggle Button -->
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <!-- Heroicons Eye Icon (Outline) -->
                            <svg id="eyeIcon" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-bold mb-2">Role:</label>
                    <select
                        name="role"
                        id="role"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Add User
                    </button>

                    <a
                        href="/dashboard"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection