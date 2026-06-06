# College Course Management System

## Team Members

| ID | Name |
| --- | --- |
| 202405648 | AHMED ABDULSALAM AHMED ALOMRI |
| 202302855 | HASAN AHMED HASAN MAYOOF |
| 202201576 | SAYED SALMAN ADNAN ABDULLA SHUBAR |
| 202002168 | SAYED ADNAN MUSTAFA MOHD.SALEH ALMOSAWI |

## Project Description

This is an ITCS330 Database Driven Websites course project built with plain PHP, MySQL, HTML, CSS, and JavaScript. The system allows users to register, log in, view courses, and search the course catalog. Admin users can manage course records with full CRUD operations.

## Features

- User registration, login, logout, and session management
- Password hashing with `password_hash()`
- Login verification with `password_verify()`
- Role-based access control for admin and regular users
- Admin course CRUD: create, read, update, delete
- Regular user course viewing and searching
- Server-side form validation with clear messages
- PDO prepared statements for database access
- Responsive custom CSS design
- JavaScript mobile navigation, form validation support, and live course filtering

## Technologies Used

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- PDO
- XAMPP

## Database Setup Instructions

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
3. Click Import.
4. Select `database.sql` from this project folder.
5. Run the import. It creates a database named `college_course_management`.
6. If your MySQL username or password is different, update `includes/db.php`.

You can also import from the command line:

```bash
mysql -u root -p < database.sql
```

## How to Run Using XAMPP

1. Copy the `college-course-management-system` folder into your XAMPP `htdocs` directory.
   - Windows example: `C:\xampp\htdocs\college-course-management-system`
   - macOS example: `/Applications/XAMPP/htdocs/college-course-management-system`
   - Linux example: `/opt/lampp/htdocs/college-course-management-system`
2. Start Apache and MySQL.
3. Import `database.sql`.
4. Visit:

```text
http://localhost/college-course-management-system/
```

## Sample Login Details

Admin account:

- Email: `admin@university.edu`
- Password: `admin123`

Regular user account:

- Email: `user@student.edu`
- Password: `user123`

The sample passwords are already stored as secure hashes in `database.sql`.

## Database Schema

### users

Stores website accounts.

- `id`: Primary key
- `name`: User full name
- `email`: Unique login email
- `password`: Hashed password
- `role`: `admin` or `user`
- `created_at`: Account creation date

### courses

Stores college course records.

- `id`: Primary key
- `course_code`: Unique course code
- `course_name`: Course title
- `instructor`: Instructor name
- `credits`: Number of credits
- `description`: Course description
- `created_at`: Course creation date

## Project Structure

```text
college-course-management-system/
  assets/
    css/
      style.css
    js/
      script.js
  includes/
    db.php
    auth.php
    header.php
    footer.php
  index.php
  register.php
  login.php
  logout.php
  dashboard.php
  courses.php
  add_course.php
  edit_course.php
  delete_course.php
  profile.php
  admin_only.php
  database.sql
  documentation.md
  README.md
```
