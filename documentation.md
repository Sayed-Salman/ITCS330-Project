# Documentation: College Course Management System

## System Overview

The College Course Management System is a database-driven website for managing college courses. It supports account registration, login, logout, sessions, user roles, course browsing, searching, and admin course management.

The application has two main user types:

- Admin users manage course records.
- Regular users view and search available courses.

## System Architecture

The system uses a simple three-layer structure:

- Presentation layer: HTML pages styled with custom CSS and enhanced with JavaScript.
- Application layer: Plain PHP pages that validate input, manage sessions, enforce access control, and process forms.
- Database layer: MySQL tables accessed through PDO prepared statements.

Shared files are stored in the `includes` folder:

- `db.php`: Creates the PDO database connection.
- `auth.php`: Handles sessions, role checks, flash messages, redirects, escaping, and CSRF token helpers.
- `header.php`: Shared page header and navigation.
- `footer.php`: Shared closing layout file.

## Database Schema

### users table

| Column | Type | Purpose |
| --- | --- | --- |
| id | INT AUTO_INCREMENT PRIMARY KEY | Unique user ID |
| name | VARCHAR(100) | User full name |
| email | VARCHAR(150) UNIQUE | Login email address |
| password | VARCHAR(255) | Hashed password |
| role | ENUM('admin', 'user') | Access role |
| created_at | TIMESTAMP | Account creation date |

### courses table

| Column | Type | Purpose |
| --- | --- | --- |
| id | INT AUTO_INCREMENT PRIMARY KEY | Unique course ID |
| course_code | VARCHAR(20) UNIQUE | Course code |
| course_name | VARCHAR(120) | Course title |
| instructor | VARCHAR(100) | Instructor name |
| credits | INT | Credit hours |
| description | TEXT | Course description |
| created_at | TIMESTAMP | Course creation date |

## Main Pages

- `index.php`: Public home page with project introduction.
- `register.php`: Allows new regular users to create accounts.
- `login.php`: Authenticates users and starts a session.
- `logout.php`: Ends the current user session.
- `dashboard.php`: Shows role-specific dashboard content.
- `courses.php`: Shows the course catalog with search and credit filtering.
- `add_course.php`: Admin-only page for adding courses.
- `edit_course.php`: Admin-only page for editing courses.
- `delete_course.php`: Admin-only page for deleting courses after confirmation.
- `profile.php`: Shows account details and allows name updates.
- `admin_only.php`: Demonstrates a protected admin-only page.

## CRUD Operations

Admin users can perform full course CRUD operations:

- Create: `add_course.php` inserts a new course.
- Read: `courses.php` and `dashboard.php` display course records.
- Update: `edit_course.php` modifies course details.
- Delete: `delete_course.php` removes a course after confirmation.

Regular users can only read course records. They cannot access add, edit, or delete pages.

## User Roles

### Admin

Admin users can:

- View the admin dashboard.
- Add courses.
- Edit courses.
- Delete courses.
- View all courses.
- Use the profile page.

### Regular User

Regular users can:

- View the student dashboard.
- View courses.
- Search courses by keyword.
- Filter courses by credits.
- Use the profile page.

## Security Features

- PHP sessions track login state.
- `password_hash()` stores secure password hashes during registration.
- `password_verify()` checks passwords during login.
- `session_regenerate_id(true)` runs after successful login to reduce session fixation risk.
- PDO prepared statements are used for all database queries.
- Server-side validation checks all important form inputs.
- Output is escaped with `htmlspecialchars()` through the `e()` helper.
- Admin-only pages call `require_admin()` to prevent regular users from accessing protected actions.
- CSRF tokens are included on POST forms.
- Clear flash messages show success and error feedback.

## Screenshots Placeholders

Add screenshots here after running the project locally:

1. Home page screenshot
2. Registration page screenshot
3. Login page screenshot
4. Admin dashboard screenshot
5. Course catalog screenshot
6. Add course page screenshot
7. Edit course page screenshot
8. Delete confirmation screenshot
9. Regular user dashboard screenshot
10. Profile page screenshot

## Conclusion

This project demonstrates the main requirements of a database-driven website: user authentication, session management, role-based authorization, CRUD operations, secure database queries, input validation, responsive design, and documentation. It is intentionally built with plain PHP and MySQL so the core web programming concepts are clear and suitable for an ITCS330 university project.
