@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="/students">← Back to Student List</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Add New Student
        </h1>

        {{-- Add Student Form --}}
        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone:</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="date_of_birth" class="block text-gray-700 font-bold mb-2">Date of Birth:</label>
                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        value="{{ old('date_of_birth') }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >
                </div>

                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700 font-bold mb-2">Course:</label>
                    <select 
                        name="course_id" 
                        id="course_id"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-bold mb-2">Status:</label>
                    <select
                        name="status"
                        id="status"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required
                    >
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ old('status') === 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="withdrawn" {{ old('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >{{ old('address') }}</textarea>
                </div>

                <div class="border-t pt-6 mt-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">
                        Guardian Information
                    </h2>

                    <div class="mb-4">
                        <label for="guardian_name" class="block text-gray-700 font-bold mb-2">Guardian Name:</label>
                        <input
                            type="text"
                            id="guardian_name"
                            name="guardian_name"
                            value="{{ old('guardian_name') }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_relationship" class="block text-gray-700 font-bold mb-2">Relationship:</label>
                        <input
                            type="text"
                            id="guardian_relationship"
                            name="guardian_relationship"
                            value="{{ old('guardian_relationship') }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            placeholder="Example: Father, Mother, Uncle">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_phone" class="block text-gray-700 font-bold mb-2">Guardian Phone:</label>
                        <input
                            type="text"
                            id="guardian_phone"
                            name="guardian_phone"
                            value="{{ old('guardian_phone') }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_email" class="block text-gray-700 font-bold mb-2">Guardian Email:</label>
                        <input
                            type="email"
                            id="guardian_email"
                            name="guardian_email"
                            value="{{ old('guardian_email') }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_address" class="block text-gray-700 font-bold mb-2">Guardian Address:</label>
                        <textarea
                            id="guardian_address"
                            name="guardian_address"
                            rows="3"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        >{{ old('guardian_address') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button 
                        type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600"
                    >
                        Add Student
                    </button>
                    
                    <a 
                        href="/students" 
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection
