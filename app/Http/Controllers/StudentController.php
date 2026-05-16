<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::with('course')
            ->when($search, function ($query) use ($search) {
                $query->where('student_code', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('status', 'LIKE', "%{$search}%")
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
        ], [
            'email.unique' => 'The email has already been taken.',
            'student_code.unique' => 'The student code has already been taken.',
        ]);

        Student::create($request->only([
            'student_code',
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'status',
            'course_id',
        ]));

        return back()->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
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
        ], [
            'email.unique' => 'That email is already in use. Try a different one.',
            'student_code.unique' => 'That student code is already in use. Try a different one.',
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->only([
            'student_code',
            'name',
            'email',
            'phone',
            'date_of_birth',
            'address',
            'status',
            'course_id',
        ]));

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
}
