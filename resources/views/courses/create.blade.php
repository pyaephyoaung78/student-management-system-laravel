@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="{{ route('courses.index') }}">← Back to Course List</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Add New Course
        </h1>

        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-bold mb-2">Course Code:</label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        placeholder="Example: CS-101"
                        required>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Course Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="duration_months" class="block text-gray-700 font-bold mb-2">Duration Months:</label>
                    <input
                        type="number"
                        id="duration_months"
                        name="duration_months"
                        value="{{ old('duration_months') }}"
                        min="1"
                        max="120"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-bold mb-2">Status:</label>
                    <select
                        name="status"
                        id="status"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Add Course
                    </button>

                    <a
                        href="{{ route('courses.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection
