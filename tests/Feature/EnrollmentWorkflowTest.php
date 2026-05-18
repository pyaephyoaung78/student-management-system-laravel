<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_enrollments(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create([
            'code' => 'CS-201',
            'name' => 'Computer Science',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0201',
            'name' => 'Enrollment Viewer',
            'email' => 'enrollment-viewer@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);

        $response = $this->actingAs($staff)->get(route('enrollments.index'));

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertSee($course->name);
    }

    public function test_staff_cannot_manage_enrollment_workflows(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create([
            'code' => 'CS-202',
            'name' => 'Web Foundations',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0202',
            'name' => 'Read Only Student',
            'email' => 'read-only@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($staff)
            ->get(route('enrollments.create'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'enrolled_at' => now()->toDateString(),
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->patch(route('enrollments.complete', $enrollment))
            ->assertForbidden();

        $this->actingAs($staff)
            ->patch(route('enrollments.withdraw', $enrollment))
            ->assertForbidden();
    }

    public function test_manager_can_enroll_student_and_complete_previous_active_enrollment(): void
    {
        $manager = $this->userWithRole('manager');
        $oldCourse = Course::create([
            'code' => 'CS-203',
            'name' => 'Old Course',
            'status' => 'active',
        ]);
        $newCourse = Course::create([
            'code' => 'CS-204',
            'name' => 'New Course',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0203',
            'name' => 'Workflow Student',
            'email' => 'workflow@example.com',
            'status' => 'active',
            'course_id' => $oldCourse->id,
        ]);

        Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $oldCourse->id,
            'enrolled_at' => now()->subMonth()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($manager)
            ->post(route('enrollments.store'), [
                'student_id' => $student->id,
                'course_id' => $newCourse->id,
                'enrolled_at' => now()->toDateString(),
                'notes' => 'Transferred by manager',
            ])
            ->assertRedirect(route('enrollments.index'));

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $oldCourse->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $newCourse->id,
            'status' => 'active',
            'notes' => 'Transferred by manager',
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'course_id' => $newCourse->id,
            'status' => 'active',
        ]);
    }

    public function test_manager_can_complete_and_withdraw_active_enrollments(): void
    {
        $manager = $this->userWithRole('manager');
        $course = Course::create([
            'code' => 'CS-205',
            'name' => 'Workflow Course',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0204',
            'name' => 'Action Student',
            'email' => 'action@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);
        $completeEnrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);
        $withdrawEnrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($manager)
            ->patch(route('enrollments.complete', $completeEnrollment))
            ->assertSessionHas('success');

        $this->actingAs($manager)
            ->patch(route('enrollments.withdraw', $withdrawEnrollment))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'id' => $completeEnrollment->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $withdrawEnrollment->id,
            'status' => 'withdrawn',
        ]);
    }

    public function test_admin_can_cancel_enrollment_records(): void
    {
        $admin = $this->userWithRole('admin');
        $course = Course::create([
            'code' => 'CS-206',
            'name' => 'Mistake Course',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0205',
            'name' => 'Cancel Student',
            'email' => 'cancel@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->patch(route('enrollments.cancel', $enrollment))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'status' => 'cancelled',
        ]);
    }

    public function test_manager_cannot_cancel_enrollment_records(): void
    {
        $manager = $this->userWithRole('manager');
        $course = Course::create([
            'code' => 'CS-207',
            'name' => 'Admin Only Course',
            'status' => 'active',
        ]);
        $student = Student::create([
            'student_code' => 'STU-2026-0206',
            'name' => 'Manager Student',
            'email' => 'manager-cancel@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now()->toDateString(),
            'status' => 'active',
        ]);

        $this->actingAs($manager)
            ->patch(route('enrollments.cancel', $enrollment))
            ->assertForbidden();
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }
}
