@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto p-6">

        <a class="text-blue-500 hover:text-blue-700" href="/dashboard">← Back to Dashboard</a>

        <h1 class="text-3xl text-white font-bold mb-6">
            Enrollment Management
        </h1>

        @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex justify-between items-center mb-4">

            <form action="{{ route('enrollments.index') }}" method="GET" class="flex gap-2">
                <input
                    type="text"
                    name="search"
                    placeholder="Search enrollments..."
                    value="{{ request('search') }}"
                    class="border rounded px-4 py-2">

                <select
                    name="status"
                    class="border rounded px-4 py-2">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="withdrawn" {{ request('status') === 'withdrawn' ? 'selected' : '' }}>Withdrawn</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <button
                    type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                    Search
                </button>
            </form>

            @if(in_array(auth()->user()->role, ['admin', 'manager']))
            <a
                href="{{ route('enrollments.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded">
                + Enroll Student
            </a>
            @endif

        </div>

        <div class="bg-white shadow rounded overflow-hidden">

            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Student</th>
                        <th class="p-4 text-left">Course</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Enrolled At</th>
                        <th class="p-4 text-left">Completed At</th>
                        <th class="p-4 text-left">Notes</th>
                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <th class="p-4 text-left">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @forelse($enrollments as $enrollment)
                    <tr class="border-t">
                        <td class="p-4">
                            <div class="font-semibold">{{ $enrollment->student->name ?? 'Student removed' }}</div>
                            <div class="text-sm text-gray-500">{{ $enrollment->student->student_code ?? '-' }}</div>
                        </td>

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

                        @if(in_array(auth()->user()->role, ['admin', 'manager']))
                        <td class="p-4">
                            <div class="flex gap-2">
                                @if($enrollment->status === 'active')
                                <form action="{{ route('enrollments.complete', $enrollment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="bg-green-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Complete this enrollment?')">
                                        Complete
                                    </button>
                                </form>

                                <form action="{{ route('enrollments.withdraw', $enrollment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="bg-orange-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Withdraw this enrollment?')">
                                        Withdraw
                                    </button>
                                </form>
                                @endif

                                @if(auth()->user()->role === 'admin' && $enrollment->status !== 'cancelled')
                                <form action="{{ route('enrollments.cancel', $enrollment->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button
                                        type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded"
                                        onclick="return confirm('Cancel this enrollment record?')">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ in_array(auth()->user()->role, ['admin', 'manager']) ? 7 : 6 }}" class="p-4 text-center text-gray-500">
                            No enrollments found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <div class="mt-4">
            {{ $enrollments->links() }}
        </div>

    </div>

@endsection
