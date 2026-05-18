<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_students(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create(['name' => 'Computer Science']);
        $student = Student::create([
            'student_code' => 'STU-2026-0001',
            'name' => 'Mg Mg',
            'email' => 'mgmg@example.com',
            'phone' => '09123456789',
            'date_of_birth' => '2005-01-15',
            'address' => 'Yangon',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($staff)->get(route('students.index'));

        $response->assertOk();
        $response->assertSee($student->name);
        $response->assertSee($course->name);
    }

    public function test_staff_cannot_create_edit_or_delete_students(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create(['name' => 'Computer Science']);
        $student = Student::create([
            'student_code' => 'STU-2026-0002',
            'name' => 'Aye Aye',
            'email' => 'ayeaye@example.com',
            'phone' => '09987654321',
            'date_of_birth' => '2004-06-20',
            'address' => 'Mandalay',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        $this->actingAs($staff)
            ->get(route('students.create'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('students.store'), [
                'student_code' => 'STU-2026-0003',
                'name' => 'New Student',
                'email' => 'new-student@example.com',
                'phone' => '09111222333',
                'date_of_birth' => '2006-03-10',
                'address' => 'Naypyidaw',
                'status' => 'active',
                'course_id' => $course->id,
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->get(route('students.edit', $student))
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('students.update', $student), [
                'student_code' => 'STU-2026-0002',
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'phone' => '09444555666',
                'date_of_birth' => '2004-06-20',
                'address' => 'Updated Address',
                'status' => 'inactive',
                'course_id' => $course->id,
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('students.destroy', $student))
            ->assertForbidden();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Aye Aye',
        ]);
    }

    public function test_manager_can_create_and_edit_students(): void
    {
        $manager = $this->userWithRole('manager');
        $course = Course::create(['name' => 'Software Engineering']);
        $newCourse = Course::create(['name' => 'Information Technology']);

        $this->actingAs($manager)
            ->post(route('students.store'), [
                'student_code' => 'STU-2026-0004',
                'name' => 'New Student',
                'email' => 'new-student@example.com',
                'phone' => '09111222333',
                'date_of_birth' => '2006-03-10',
                'address' => 'Naypyidaw',
                'status' => 'active',
                'course_id' => $course->id,
                'guardian_name' => 'Daw Guardian',
                'guardian_relationship' => 'Mother',
                'guardian_phone' => '09777888999',
                'guardian_email' => 'guardian@example.com',
                'guardian_address' => 'Yangon',
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $student = Student::where('email', 'new-student@example.com')->firstOrFail();

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);

        $this->actingAs($manager)
            ->put(route('students.update', $student), [
                'student_code' => 'STU-2026-0004',
                'name' => 'Updated Student',
                'email' => 'updated-student@example.com',
                'phone' => '09444555666',
                'date_of_birth' => '2006-03-10',
                'address' => 'Updated Address',
                'status' => 'graduated',
                'course_id' => $newCourse->id,
                'guardian_name' => 'U Updated Guardian',
                'guardian_relationship' => 'Father',
                'guardian_phone' => '09666555444',
                'guardian_email' => 'updated-guardian@example.com',
                'guardian_address' => 'Mandalay',
            ])
            ->assertRedirect(route('students.edit', $student->id));

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'student_code' => 'STU-2026-0004',
            'name' => 'Updated Student',
            'email' => 'updated-student@example.com',
            'status' => 'graduated',
        ]);

        $this->assertDatabaseHas('guardians', [
            'student_id' => $student->id,
            'name' => 'U Updated Guardian',
            'relationship' => 'Father',
            'phone' => '09666555444',
            'email' => 'updated-guardian@example.com',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'completed',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $newCourse->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($manager)->get(route('students.edit', $student));

        $response->assertOk();
        $response->assertSee('Enrollment History');
        $response->assertSee($course->name);
        $response->assertSee($newCourse->name);
        $response->assertSee('completed');
        $response->assertSee('active');
    }

    public function test_manager_cannot_delete_students(): void
    {
        $manager = $this->userWithRole('manager');
        $course = Course::create(['name' => 'Software Engineering']);
        $student = Student::create([
            'student_code' => 'STU-2026-0005',
            'name' => 'Protected Student',
            'email' => 'protected@example.com',
            'phone' => '09123456789',
            'date_of_birth' => '2005-01-15',
            'address' => 'Yangon',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        $this->actingAs($manager)
            ->delete(route('students.destroy', $student))
            ->assertForbidden();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
        ]);
    }

    public function test_admin_can_delete_students(): void
    {
        $admin = $this->userWithRole('admin');
        $course = Course::create(['name' => 'Information Technology']);
        $student = Student::create([
            'student_code' => 'STU-2026-0006',
            'name' => 'Delete Me',
            'email' => 'delete-me@example.com',
            'phone' => '09123456789',
            'date_of_birth' => '2005-01-15',
            'address' => 'Yangon',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }
}
