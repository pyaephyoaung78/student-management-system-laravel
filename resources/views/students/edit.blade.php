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
                            value="{{ old('guardian_name', optional($student->guardian)->name) }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_relationship" class="block text-gray-700 font-bold mb-2">Relationship:</label>
                        <input
                            type="text"
                            id="guardian_relationship"
                            name="guardian_relationship"
                            value="{{ old('guardian_relationship', optional($student->guardian)->relationship) }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                            placeholder="Example: Father, Mother, Uncle">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_phone" class="block text-gray-700 font-bold mb-2">Guardian Phone:</label>
                        <input
                            type="text"
                            id="guardian_phone"
                            name="guardian_phone"
                            value="{{ old('guardian_phone', optional($student->guardian)->phone) }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_email" class="block text-gray-700 font-bold mb-2">Guardian Email:</label>
                        <input
                            type="email"
                            id="guardian_email"
                            name="guardian_email"
                            value="{{ old('guardian_email', optional($student->guardian)->email) }}"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="guardian_address" class="block text-gray-700 font-bold mb-2">Guardian Address:</label>
                        <textarea
                            id="guardian_address"
                            name="guardian_address"
                            rows="3"
                            class="w-full border rounded px-4 py-2 focus:outline-none focus:border-blue-500"
                        >{{ old('guardian_address', optional($student->guardian)->address) }}</textarea>
                    </div>
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

        <div class="bg-white shadow rounded overflow-hidden p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                Enrollment History
            </h2>

            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Course</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Enrolled At</th>
                        <th class="p-4 text-left">Completed At</th>
                        <th class="p-4 text-left">Notes</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($student->enrollments as $enrollment)
                    <tr class="border-t">
                        <td class="p-4">
                            <div class="font-semibold">{{ $enrollment->course->name ?? 'Course removed' }}</div>
                            <div class="text-sm text-gray-500">{{ $enrollment->course->code ?? '-' }}</div>
                        </td>

                        <td class="p-4 capitalize">
                            {{ $enrollment->status }}
                        </td>

                        <td class="p-4">
                            {{ optional($enrollment->enrolled_at)->format('Y-m-d') ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ optional($enrollment->completed_at)->format('Y-m-d') ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $enrollment->notes ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            No enrollment history found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
