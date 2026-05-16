@extends('layouts.admin')

@section('content')

<div class="p-6">

    <h1 class="text-3xl text-white font-bold mb-6">
        Admin Dashboard
    </h1>

    <!-- Stats Cards -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-gray-500">
                Total Students
            </h2>

            <p class="text-4xl font-bold">
                {{ $studentCount }}
            </p>

        </div>

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-gray-500">
                Total Users
            </h2>

            <p class="text-4xl font-bold">
                {{ $userCount }}
            </p>

        </div>

        <div class="bg-white shadow rounded-xl p-6">

            <h2 class="text-gray-500">
                Total Courses
            </h2>

            <p class="text-4xl font-bold">
                {{ $courseCount }}
            </p>

        </div>

    </div>

    <div class="bg-white shadow rounded-xl p-6">

        <h2 class="text-2xl font-bold mb-4">
            Recent Students
        </h2>

        <table class="w-full border">

            <tr class="bg-gray-100">

                <th class="p-2 border">Name</th>
                <th class="p-2 border">Code</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Course</th>
                <th class="p-2 border">Status</th>

            </tr>

            @foreach($recentStudents as $student)

            <tr>

                <td class="p-2 border">
                    {{ $student->name }}
                </td>

                <td class="p-2 border">
                    {{ $student->student_code ?? '-' }}
                </td>

                <td class="p-2 border">
                    {{ $student->email }}
                </td>

                <td class="p-2 border">
                    {{ $student->course->name ?? 'No Course' }}
                </td>

                <td class="p-2 border capitalize">
                    {{ $student->status }}
                </td>

            </tr>

            @endforeach

        </table>

    </div>

</div>

@endsection