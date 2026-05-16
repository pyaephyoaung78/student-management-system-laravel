@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="{{ route('students.index') }}">← Back to Student List</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Edit Student
        </h1>

        {{-- Edit Student Form --}}
        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('students.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="student_code" class="block text-gray-700 font-bold mb-2">Student Code:</label>
                    <input
                        type="text"
                        id="student_code"
                        name="student_code"
                        value="{{ old('student_code', $student->student_code) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $student->name) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $student->email) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone:</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $student->phone) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="date_of_birth" class="block text-gray-700 font-bold mb-2">Date of Birth:</label>
                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        value="{{ old('date_of_birth', optional($student->date_of_birth)->format('Y-m-d')) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                </div>

                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700 font-bold mb-2">Course:</label>
                    <select
                        name="course_id"
                        id="course_id"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        @foreach($courses as $course)
                        <option
                            value="{{ $course->id }}"
                            {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
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
                        required>
                        <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="graduated" {{ old('status', $student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                        <option value="suspended" {{ old('status', $student->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        <option value="withdrawn" {{ old('status', $student->status) === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >{{ old('address', $student->address) }}</textarea>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-yellow-400 text-black px-6 py-2 rounded hover:bg-yellow-500">
                        Update Student
                    </button>

                    <a
                        href="{{ route('students.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection
