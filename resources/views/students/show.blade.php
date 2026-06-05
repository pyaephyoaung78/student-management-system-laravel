@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex justify-between items-center mb-6">
        <div>
            <a href="{{ route('students.index') }}" class="text-blue-400 hover:text-blue-300">
                ← Back to Students
            </a>

            <h1 class="text-3xl text-white font-bold mt-3">
                Student Detail
            </h1>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a href="{{ route('students.edit', $student->id) }}"
               class="bg-yellow-400 text-white px-4 py-2 rounded">
                Edit Student
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Student Info --}}
        <div class="lg:col-span-2 bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">
                Student Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <p class="text-gray-500 text-sm">Student Code</p>
                    <p class="font-semibold">{{ $student->student_code ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Name</p>
                    <p class="font-semibold">{{ $student->name }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Email</p>
                    <p class="font-semibold">{{ $student->email }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Phone</p>
                    <p class="font-semibold">{{ $student->phone ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Date of Birth</p>
                    <p class="font-semibold">
                        {{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <p class="font-semibold capitalize">{{ $student->status }}</p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Current Course</p>
                    <p class="font-semibold">
                        {{ $student->course->name ?? 'No Course Assigned' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Joined Date</p>
                    <p class="font-semibold">
                        {{ $student->created_at->format('d M Y') }}
                    </p>
                </div>

            </div>

            <div class="mt-4">
                <p class="text-gray-500 text-sm">Address</p>
                <p class="font-semibold">{{ $student->address ?? '-' }}</p>
            </div>
        </div>

        {{-- Guardian Info --}}
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-xl font-bold mb-4">
                Guardian Information
            </h2>

            @if($student->guardian)
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-500 text-sm">Name</p>
                        <p class="font-semibold">{{ $student->guardian->name }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Relationship</p>
                        <p class="font-semibold">{{ $student->guardian->relationship ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Phone</p>
                        <p class="font-semibold">{{ $student->guardian->phone ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Email</p>
                        <p class="font-semibold">{{ $student->guardian->email ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Address</p>
                        <p class="font-semibold">{{ $student->guardian->address ?? '-' }}</p>
                    </div>
                </div>
            @else
                <p class="text-gray-500">No guardian information.</p>
            @endif
        </div>

    </div>

    {{-- Active Enrollment --}}
    <div class="bg-white rounded shadow p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">
            Active Enrollment
        </h2>

        @if($student->activeEnrollment)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Course</p>
                    <p class="font-semibold">
                        {{ $student->activeEnrollment->course->name ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Enrolled Date</p>
                    <p class="font-semibold">
                        {{ $student->activeEnrollment->enrolled_at?->format('d M Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Status</p>
                    <p class="font-semibold capitalize">
                        {{ $student->activeEnrollment->status }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500 text-sm">Notes</p>
                    <p class="font-semibold">
                        {{ $student->activeEnrollment->notes ?? '-' }}
                    </p>
                </div>
            </div>
        @else
            <p class="text-gray-500">No active enrollment.</p>
        @endif
    </div>

    {{-- Enrollment History --}}
    <div class="bg-white rounded shadow p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">
            Enrollment History
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">Course</th>
                        <th class="p-3 text-left">Enrolled Date</th>
                        <th class="p-3 text-left">Completed Date</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Notes</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($student->enrollments as $enrollment)
                        <tr class="border-t">
                            <td class="p-3">
                                {{ $enrollment->course->name ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $enrollment->enrolled_at?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $enrollment->completed_at?->format('d M Y') ?? 'Currently Attending' }}
                            </td>

                            <td class="p-3 capitalize">
                                {{ $enrollment->status }}
                            </td>

                            <td class="p-3">
                                {{ $enrollment->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No enrollment history.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection