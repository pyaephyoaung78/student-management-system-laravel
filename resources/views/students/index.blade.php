@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto p-6">

    <a class="text-blue-500 hover:text-blue-700" href="/dashboard">← Back to Dashboard</a>

    <h1 class="text-3xl text-white font-bold mb-6">
        Student Management
    </h1>

    {{-- Top Bar --}}
    <div class="flex justify-between items-center mb-4">

        {{-- Search Form --}}
        <form action="{{ route('students.index') }}" method="GET">
            <input
                type="text"
                name="search"
                placeholder="Search students..."
                value="{{ request('search') }}"
                class="border rounded px-4 py-2">

            <button
                type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded">
                Search
            </button>
        </form>

        {{-- Add Button --}}
        @if(in_array(auth()->user()->role, ['admin', 'manager']))

        <a
            href="{{ route('students.create') }}"
            class="bg-green-500 text-white px-4 py-2 rounded">
            + Add Student
        </a>
        @endif

    </div>

    {{-- Student Table --}}
    <div class="bg-white shadow rounded overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Code</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Phone</th>
                    <th class="p-4 text-left">Course</th>
                    <th class="p-4 text-left">Guardian</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Actions</th>
                    <th class="p-4 text-left">Trash Status</th>
                </tr>

            </thead>

            <tbody>

                @forelse($students as $student)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $student->name }}
                    </td>

                    <td class="p-4">
                        {{ $student->student_code ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $student->email }}
                    </td>

                    <td class="p-4">
                        {{ $student->phone ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $student->course->name ?? 'No Course Assigned'}}
                    </td>

                    <td class="p-4">
                        @if($student->guardian)
                        <div>{{ $student->guardian->name }}</div>
                        <div class="text-sm text-gray-500">{{ $student->guardian->phone ?? '-' }}</div>
                        @else
                        -
                        @endif
                    </td>

                    <td class="p-4 capitalize">
                        {{ $student->status }}
                    </td>
                    <td class="p-4 flex gap-2">

                        @if($student->trashed())

                        <form action="{{ route('students.restore', $student->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <button type="submit"
                                class="bg-green-500 text-white px-3 py-1 rounded">
                                Restore
                            </button>
                        </form>

                        <form action="{{ route('students.force-delete', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-700 text-white px-3 py-1 rounded"
                                onclick="return confirm('This will permanently delete this student. Are you sure?')">
                                Force Delete
                            </button>
                        </form>

                        @else

                        <a href="{{ route('students.show', $student->id) }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded">
                            View
                        </a>

                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <a href="{{ route('students.edit', $student->id) }}"
                            class="bg-yellow-400 text-white px-3 py-1 rounded">
                            Edit
                        </a>
                        @endif

                        @if(auth()->user()->role === 'admin')
                        <form action="{{ route('students.destroy', $student->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded"
                                onclick="return confirm('Move this student to trash?')">
                                Delete
                            </button>
                        </form>
                        @endif

                        @endif

                    </td>
                    <td class="p-4">
                        @if($student->trashed())
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-sm">
                            Trashed
                        </span>
                        @else
                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-sm">
                            Active
                        </span>
                        @endif
                    </td>
                </tr>

                @empty

                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">
                        No students found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $students->links() }}
    </div>

</div>


@endsection