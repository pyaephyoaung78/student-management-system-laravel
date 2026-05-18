<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::with(['course', 'guardian'])
            ->when($search, function ($query) use ($search) {
                $query->where('student_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
                    ->orWhereHas('guardian', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('course', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->paginate(5)
            ->withQueryString();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        // Code to show a form for creating a new student
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    public function store(Request $request)
    {
        // Code to save a new student to the database
        $request->validate([
            'student_code' => ['required', 'string', 'max:50', 'unique:students,student_code'],
            'name' => ['required', 'string', 'max:255'],
            'email' => 'required|email|max:255|unique:students,email',
            'phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated', 'suspended', 'withdrawn'])],
            'course_id' => 'required|exists:courses,id',
            'guardian_name' => ['nullable', 'required_with:guardian_relationship,guardian_phone,guardian_email,guardian_address', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'guardian_address' => ['nullable', 'string', 'max:1000'],
        ], [
            'email.unique' => 'The email has already been taken.',
            'student_code.unique' => 'The student code has already been taken.',
        ]);

        DB::transaction(function () use ($request) {
            $student = Student::create($this->studentData($request));

            $this->startEnrollment($student, (int) $request->course_id);

            if ($this->hasGuardianData($request)) {
                $student->guardians()->create($this->guardianData($request));
            }
        });

        return back()->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $student = Student::with([
            'guardian',
            'enrollments' => function ($query) {
                $query->with('course')->latest('enrolled_at')->latest();
            },
        ])->findOrFail($id);
        $courses = Course::all();
        return view('students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_code' => ['required', 'string', 'max:50', Rule::unique('students', 'student_code')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('students', 'email')->ignore($id),],
            'phone' => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'address' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated', 'suspended', 'withdrawn'])],
            'course_id' => 'required|exists:courses,id',
            'guardian_name' => ['nullable', 'required_with:guardian_relationship,guardian_phone,guardian_email,guardian_address', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:100'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'guardian_address' => ['nullable', 'string', 'max:1000'],
        ], [
            'email.unique' => 'That email is already in use. Try a different one.',
            'student_code.unique' => 'That student code is already in use. Try a different one.',
        ]);

        $student = Student::findOrFail($id);
        DB::transaction(function () use ($request, $student) {
            $previousCourseId = $student->course_id;

            $student->update($this->studentData($request));
            $this->syncEnrollment($student, $previousCourseId, (int) $request->course_id);

            if ($this->hasGuardianData($request)) {
                $guardian = $student->guardian()->first();

                if ($guardian) {
                    $guardian->update($this->guardianData($request));
                } else {
                    $student->guardians()->create($this->guardianData($request));
                }
            } elseif ($guardian = $student->guardian()->first()) {
                $guardian->delete();
            }
        });

        return redirect()->route('students.edit' , $student->id)
            ->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
    
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    private function studentData(Request $request): array
    {
        return $request->only([
            'student_code',
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'status',
            'course_id',
        ]);
    }

    private function guardianData(Request $request): array
    {
        return [
            'name' => $request->guardian_name,
            'relationship' => $request->guardian_relationship,
            'phone' => $request->guardian_phone,
            'email' => $request->guardian_email,
            'address' => $request->guardian_address,
        ];
    }

    private function hasGuardianData(Request $request): bool
    {
        return collect($this->guardianData($request))
            ->filter()
            ->isNotEmpty();
    }

    private function syncEnrollment(Student $student, int|string|null $previousCourseId, int $newCourseId): void
    {
        if ((int) $previousCourseId === $newCourseId) {
            if (! $student->enrollments()->where('course_id', $newCourseId)->where('status', 'active')->exists()) {
                $this->startEnrollment($student, $newCourseId);
            }

            return;
        }

        $student->enrollments()
            ->where('status', 'active')
            ->update([
                'status' => 'completed',
                'completed_at' => now()->toDateString(),
            ]);

        $this->startEnrollment($student, $newCourseId);
    }

    private function startEnrollment(Student $student, int $courseId): void
    {
        $student->enrollments()->create([
            'course_id' => $courseId,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);
    }
}
