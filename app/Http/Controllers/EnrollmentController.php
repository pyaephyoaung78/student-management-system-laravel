<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status;

        $enrollments = Enrollment::with(['student', 'course'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('student_code', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('code', 'LIKE', "%{$search}%");
                    });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('enrolled_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = Student::orderBy('name')->get();
        $courses = Course::where('status', 'active')->orderBy('name')->get();

        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $student = Student::findOrFail($request->student_id);

        if ($student->enrollments()
            ->where('course_id', $request->course_id)
            ->where('status', 'active')
            ->exists()) {
            return back()
                ->withErrors(['course_id' => 'This student is already actively enrolled in this course.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $student) {
            $student->enrollments()
                ->where('status', 'active')
                ->update([
                    'status' => 'completed',
                    'completed_at' => now()->toDateString(),
                ]);

            $student->enrollments()->create([
                'course_id' => $request->course_id,
                'enrolled_at' => $request->enrolled_at,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            $student->update([
                'course_id' => $request->course_id,
                'status' => 'active',
            ]);
        });

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled successfully.');
    }

    public function complete(Enrollment $enrollment)
    {
        return $this->finishEnrollment($enrollment, 'completed', 'Enrollment completed successfully.');
    }

    public function withdraw(Enrollment $enrollment)
    {
        return $this->finishEnrollment($enrollment, 'withdrawn', 'Enrollment withdrawn successfully.');
    }

    public function cancel(Enrollment $enrollment)
    {
        if ($enrollment->status === 'cancelled') {
            return back()->with('error', 'This enrollment is already cancelled.');
        }

        $enrollment->update([
            'status' => 'cancelled',
            'completed_at' => $enrollment->completed_at ?? now()->toDateString(),
        ]);

        return back()->with('success', 'Enrollment cancelled successfully.');
    }

    private function finishEnrollment(Enrollment $enrollment, string $status, string $message)
    {
        if ($enrollment->status !== 'active') {
            return back()->with('error', 'Only active enrollments can be updated with this action.');
        }

        $enrollment->update([
            'status' => $status,
            'completed_at' => now()->toDateString(),
        ]);

        return back()->with('success', $message);
    }
}
