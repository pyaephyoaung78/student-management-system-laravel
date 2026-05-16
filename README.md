# Student Management System

A Laravel-based student management system for practicing real-world backend development concepts such as authentication, role-based access control, CRUD operations, database relationships, validation, Blade templates, and feature testing.

This project is a learning project, but it is structured around ideas used in professional Laravel applications.

## Features

- User authentication using Laravel Breeze-style auth screens
- Dashboard with student and course statistics
- Student management
  - View students
  - Search students by name, email, or course
  - Create students
  - Edit students
  - Delete students
- Course relationship support
  - Each student belongs to a course
  - Each course can have many students
- Admin user management
  - View users
  - Search users
  - Create users
  - Edit users
  - Delete users
- Role-based permissions
  - Admin
  - Manager
  - Staff
- Pagination for student and user lists
- Validation for form input
- Flash messages for successful actions

## User Roles

The system currently uses three main roles.

| Role | Permissions |
| --- | --- |
| Admin | Can manage students and users. Can delete students and users. |
| Manager | Can view, create, and edit students. |
| Staff | Can view students only. |

Backend route protection is handled with custom role middleware.

## Tech Stack

- PHP 8.3+
- Laravel 13
- Blade templates
- Tailwind CSS
- Vite
- SQLite or another Laravel-supported database
- PHPUnit for testing

## Main Project Structure

```text
app/
  Http/
    Controllers/
      StudentController.php
      Admin/UserController.php
    Middleware/
      RoleMiddleware.php
  Models/
    Student.php
    Course.php
    User.php

database/
  migrations/
  seeders/

resources/
  views/
    dashboard.blade.php
    students/
    admin/users/
    layouts/admin.blade.php

routes/
  web.php

tests/
  Feature/
```

## Installation

Clone the project and install dependencies.

```bash
composer install
npm install
```

Create the environment file.

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`.

For SQLite, create the database file:

```bash
touch database/database.sqlite
```

Then set:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

Run migrations.

```bash
php artisan migrate
```

Start the development servers.

```bash
composer run dev
```

Or run Laravel and Vite separately:

```bash
php artisan serve
npm run dev
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
| `/students/{student}/edit` | Edit student form |
| `/admin/users` | Admin user list |
| `/admin/users/create` | Create user form |
| `/admin/users/{user}/edit` | Edit user form |

## Reliability Notes

These are important engineering improvements to keep the project stable:

- Keep migration `down()` methods complete so rollbacks work.
- Use strong validation rules for names, emails, passwords, course IDs, and roles.
- Use role middleware for backend security.
- Hide UI links that the current role is not allowed to use.
- Eager-load relationships like `Student::with('course')` to avoid extra database queries.
- Add feature tests for permission rules and CRUD behavior.
- Do not allow weak passwords in admin-created users.
- Restrict roles to known values: `admin`, `manager`, and `staff`.

## Feature Tests

The project includes feature tests for the most important permission rules:

- Staff can view students.
- Staff cannot create, edit, or delete students.
- Manager can create and edit students.
- Manager cannot delete students.
- Admin can delete students.
- Admin can manage users.
- Non-admin users cannot access `/admin/users`.

Run them with:

```bash
php artisan test
```

Or run only the new permission tests:

```bash
php artisan test --filter=StudentPermissionTest
php artisan test --filter=AdminUserPermissionTest
```

## Known Development Notes

If tests fail with an SQLite error like:

```text
could not find driver
```

your PHP installation is missing the SQLite PDO extension. Install or enable the SQLite extension for your PHP version, then run tests again.

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
- Testing with PHPUnit
- Clean CRUD application structure

## Next Improvements

- Add a course management CRUD section.
- Add factories for `Student` and `Course`.
- Move validation into Form Request classes.
- Add policies for authorization instead of checking roles directly in many places.
- Improve dashboard stats for managers and staff.
- Improve responsive UI for mobile screens.
- Add Git version control if not already initialized.

## Project Status

This is an active learning project. The main goal is not only to make the app work, but to improve it step by step using professional software engineering habits.
