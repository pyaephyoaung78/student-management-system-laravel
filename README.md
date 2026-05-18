# Student Management System

A Laravel-based student management system built to practice real-world backend development concepts: authentication, role-based access control, CRUD workflows, database relationships, validation, Blade templates, migrations, and feature testing.

This started as a learning project, but it now includes more realistic school-management features such as guardians and enrollment history.

## Features

- User authentication using Laravel Breeze-style auth screens
- Dashboard with student, course, and user statistics
- Student management
  - View, search, create, edit, and delete students
  - Store real-world student details such as student code, phone, date of birth, address, and status
  - Store primary guardian information from the student form
  - View enrollment history on the student edit page
- Course management
  - View, search, create, edit, and delete courses
  - Store course code, description, duration, and status
  - Prevent deleting courses that still have students or enrollment history
- Guardian support
  - Guardians are stored in their own table
  - A student can have many guardians
  - The current UI manages the primary guardian through the student form
- Enrollment workflow management
  - View enrollments
  - Enroll a student in a course
  - Automatically complete an old active enrollment when a student is enrolled in a new course
  - Complete active enrollments
  - Withdraw active enrollments
  - Cancel enrollment records as admin
- Admin user management
  - View, search, create, edit, and delete users
- Role-based permissions
  - Admin
  - Manager
  - Staff
- Pagination for list pages
- Validation for form input
- Feature tests for important permission and workflow behavior

## User Roles

| Role | Permissions |
| --- | --- |
| Admin | Can manage students, courses, enrollments, and users. Can delete students/courses and cancel enrollment records. |
| Manager | Can create and edit students/courses. Can enroll students and complete/withdraw enrollments. |
| Staff | Can view students, courses, and enrollments only. |

Backend route protection is handled with custom role middleware.

## Tech Stack

- PHP 8.3+
- Laravel 13
- Blade templates
- Tailwind CSS
- Vite
- MySQL or another Laravel-supported database
- SQLite in-memory database for tests
- PHPUnit for testing

## Main Project Structure

```text
app/
  Http/
    Controllers/
      Admin/UserController.php
      CourseController.php
      EnrollmentController.php
      StudentController.php
    Middleware/
      RoleMiddleware.php
  Models/
    Course.php
    Enrollment.php
    Guardian.php
    Student.php
    User.php

database/
  migrations/
  seeders/

resources/
  views/
    admin/users/
    courses/
    enrollments/
    students/
    dashboard.blade.php
    layouts/admin.blade.php

routes/
  web.php

tests/
  Feature/
```

## Core Database Design

Main tables:

- `users`
- `students`
- `courses`
- `guardians`
- `enrollments`

Important relationships:

- A student belongs to a course as the current course.
- A course has many students.
- A student has many guardians.
- A student has many enrollments.
- A course has many enrollments.
- An enrollment belongs to one student and one course.

The `students.course_id` field represents the student’s current course. The `enrollments` table stores historical course movement over time.

## Installation

Install dependencies:

```bash
composer install
npm install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`.

Example MySQL settings:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_student
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations:

```bash
php artisan migrate
```

Start the development server:

```bash
php artisan serve
```

Start Vite in another terminal:

```bash
npm run dev
```

Or use the combined Laravel script:

```bash
composer run dev
```

## Common Commands

Run migrations:

```bash
php artisan migrate
```

Rollback the latest migration batch:

```bash
php artisan migrate:rollback
```

Refresh the database:

```bash
php artisan migrate:fresh
```

Run tests:

```bash
php artisan test
```

Build frontend assets:

```bash
npm run build
```

## Important Routes

| URL | Purpose |
| --- | --- |
| `/` | Redirects to dashboard |
| `/dashboard` | Main dashboard |
| `/students` | Student list |
| `/students/create` | Create student form |
| `/students/{student}/edit` | Edit student and view enrollment history |
| `/courses` | Course list |
| `/courses/create` | Create course form |
| `/courses/{course}/edit` | Edit course form |
| `/enrollments` | Enrollment list |
| `/enrollments/create` | Enroll student form |
| `/admin/users` | Admin user list |
| `/admin/users/create` | Create user form |
| `/admin/users/{user}/edit` | Edit user form |

## Enrollment Workflow

Enrollments are treated as a workflow, not simple CRUD.

Supported actions:

- Enroll a student in a course
- Complete an active enrollment
- Withdraw an active enrollment
- Cancel an enrollment record as admin

When a student is enrolled in a new course:

- The previous active enrollment is marked as `completed`.
- A new active enrollment is created.
- The student’s current `course_id` is updated.

Enrollment statuses:

```text
active
completed
withdrawn
cancelled
```

This preserves course history instead of overwriting it.

## Feature Tests

The project includes feature tests for:

- Authentication behavior
- Admin user management permissions
- Student permissions
- Course permissions
- Guardian creation/update through the student workflow
- Enrollment history display
- Enrollment workflow actions

Run all tests:

```bash
php artisan test
```

Run focused tests:

```bash
php artisan test --filter=StudentPermissionTest
php artisan test --filter=CoursePermissionTest
php artisan test --filter=EnrollmentWorkflowTest
php artisan test --filter=AdminUserPermissionTest
```

## Known Development Notes

If tests fail with an SQLite error like:

```text
could not find driver
```

your PHP installation is missing the SQLite PDO extension. Install or enable the SQLite extension for your PHP version, then run tests again.

If `php artisan migrate` fails with a MySQL connection error, make sure your MySQL server is running and your `.env` database settings are correct.

## Learning Goals

This project is useful for practicing:

- Laravel routing
- Controllers and resource controllers
- Eloquent models and relationships
- Database migrations
- Form validation
- Blade templates
- Middleware
- Role-based authorization
- Workflow-based application design
- Testing with PHPUnit
- Clean CRUD and non-CRUD business flows

## Next Improvements

- Move validation into Form Request classes.
- Add Laravel policies for authorization.
- Add factories for `Student`, `Course`, `Guardian`, and `Enrollment`.
- Add soft deletes for students and courses.
- Add audit fields such as `created_by` and `updated_by`.
- Add filters for student status, course, and enrollment status.
- Improve dashboard reports with active enrollments and students by course.
- Improve responsive UI for mobile screens.

## Project Status

This is an active learning project. The goal is not only to make the app work, but to improve it step by step using professional software engineering habits.
