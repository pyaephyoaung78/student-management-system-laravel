@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="/dashboard">← Back to Dashboard</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Course Management
        </h1>

        <div class="flex justify-between items-center mb-4">

            <form action="{{ route('courses.index') }}" method="GET">
                <input
                    type="text"
                    name="search"
                    placeholder="Search courses..."
                    value="{{ request('search') }}"
                    class="border rounded px-4 py-2">

                <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                    Search
                </button>
            </form>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a
                href="{{ route('courses.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded">
                + Add Course
            </a>
            @endif

        </div>

        <div class="bg-white shadow rounded overflow-hidden">

            <table class="w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Code</th>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4 text-left">Duration</th>
                        <th class="p-4 text-left">Students</th>
                        <th class="p-4 text-left">Status</th>
                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <th class="p-4 text-left">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($courses as $course)
                    <tr class="border-t">
                        <td class="p-4">
                            {{ $course->code ?? '-' }}
                        </td>

                        <td class="p-4">
                            <div class="font-semibold">{{ $course->name }}</div>
                            @if($course->description)
                            <div class="text-sm text-gray-500">{{ $course->description }}</div>
                            @endif
                        </td>

                        <td class="p-4">
                            {{ $course->duration_months ? $course->duration_months . ' months' : '-' }}
                        </td>

                        <td class="p-4">
                            {{ $course->students_count }}
                        </td>

                        <td class="p-4 capitalize">
                            {{ $course->status }}
                        </td>

                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <td class="p-4 flex gap-2">
                            <a
                                href="{{ route('courses.edit', $course->id) }}"
                                class="bg-yellow-400 text-white px-3 py-1 rounded">
                                Edit
                            </a>

                            @if(auth()->user()->role === 'admin')
                            <form
                                action="{{ route('courses.destroy', $course->id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded"
                                    onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(auth()->user()->role, ['admin', 'manager']) ? 6 : 5 }}" class="p-4 text-center text-gray-500">
                            No courses found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>

        <div class="mt-4">
            {{ $courses->links() }}
        </div>

    </div>

@endsection
