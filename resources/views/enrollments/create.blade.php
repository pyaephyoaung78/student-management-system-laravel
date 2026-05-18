@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="{{ route('enrollments.index') }}">← Back to Enrollment List</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Enroll Student
        </h1>

        <div class="bg-white shadow rounded overflow-hidden p-6">
            <form action="{{ route('enrollments.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="student_id" class="block text-gray-700 font-bold mb-2">Student:</label>
                    <select
                        name="student_id"
                        id="student_id"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        <option value="">Select Student</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->student_code ?? 'No Code' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="course_id" class="block text-gray-700 font-bold mb-2">Course:</label>
                    <select
                        name="course_id"
                        id="course_id"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code ?? 'No Code' }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="enrolled_at" class="block text-gray-700 font-bold mb-2">Enrolled At:</label>
                    <input
                        type="date"
                        id="enrolled_at"
                        name="enrolled_at"
                        value="{{ old('enrolled_at', now()->toDateString()) }}"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 font-bold mb-2">Notes:</label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="3"
                        class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                    >{{ old('notes') }}</textarea>
                </div>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Enroll Student
                    </button>

                    <a
                        href="{{ route('enrollments.index') }}"
                        class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600 inline-block text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>

@endsection
