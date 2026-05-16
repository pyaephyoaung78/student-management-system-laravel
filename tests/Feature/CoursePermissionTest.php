<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_view_courses(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create([
            'code' => 'CS-101',
            'name' => 'Computer Science',
            'description' => 'Programming fundamentals',
            'duration_months' => 12,
            'status' => 'active',
        ]);

        $response = $this->actingAs($staff)->get(route('courses.index'));

        $response->assertOk();
        $response->assertSee($course->code);
        $response->assertSee($course->name);
    }

    public function test_staff_cannot_create_edit_or_delete_courses(): void
    {
        $staff = $this->userWithRole('staff');
        $course = Course::create([
            'code' => 'CS-102',
            'name' => 'Web Development',
            'status' => 'active',
        ]);

        $this->actingAs($staff)
            ->get(route('courses.create'))
            ->assertForbidden();

        $this->actingAs($staff)
            ->post(route('courses.store'), [
                'code' => 'CS-103',
                'name' => 'Database Systems',
                'description' => 'Relational database design',
                'duration_months' => 6,
                'status' => 'active',
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->get(route('courses.edit', $course))
            ->assertForbidden();

        $this->actingAs($staff)
            ->put(route('courses.update', $course), [
                'code' => 'CS-102',
                'name' => 'Updated Web Development',
                'description' => 'Updated description',
                'duration_months' => 8,
                'status' => 'inactive',
            ])
            ->assertForbidden();

        $this->actingAs($staff)
            ->delete(route('courses.destroy', $course))
            ->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Web Development',
        ]);
    }

    public function test_manager_can_create_and_edit_courses(): void
    {
        $manager = $this->userWithRole('manager');

        $this->actingAs($manager)
            ->post(route('courses.store'), [
                'code' => 'SE-101',
                'name' => 'Software Engineering',
                'description' => 'Building maintainable software',
                'duration_months' => 10,
                'status' => 'active',
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('success');

        $course = Course::where('code', 'SE-101')->firstOrFail();

        $this->actingAs($manager)
            ->put(route('courses.update', $course), [
                'code' => 'SE-101',
                'name' => 'Advanced Software Engineering',
                'description' => 'Design, testing, and teamwork',
                'duration_months' => 12,
                'status' => 'inactive',
            ])
            ->assertRedirect(route('courses.edit', $course->id));

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'name' => 'Advanced Software Engineering',
            'duration_months' => 12,
            'status' => 'inactive',
        ]);
    }

    public function test_manager_cannot_delete_courses(): void
    {
        $manager = $this->userWithRole('manager');
        $course = Course::create([
            'code' => 'IT-101',
            'name' => 'Information Technology',
            'status' => 'active',
        ]);

        $this->actingAs($manager)
            ->delete(route('courses.destroy', $course))
            ->assertForbidden();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
        ]);
    }

    public function test_admin_can_delete_courses_without_students(): void
    {
        $admin = $this->userWithRole('admin');
        $course = Course::create([
            'code' => 'IT-102',
            'name' => 'Networking',
            'status' => 'active',
        ]);

        $this->actingAs($admin)
            ->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'));

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
        ]);
    }

    public function test_admin_cannot_delete_courses_with_students(): void
    {
        $admin = $this->userWithRole('admin');
        $course = Course::create([
            'code' => 'IT-103',
            'name' => 'Cyber Security',
            'status' => 'active',
        ]);

        Student::create([
            'student_code' => 'STU-2026-0100',
            'name' => 'Protected Student',
            'email' => 'protected-course@example.com',
            'status' => 'active',
            'course_id' => $course->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
        ]);
    }

    private function userWithRole(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }
}
