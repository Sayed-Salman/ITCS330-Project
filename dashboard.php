<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_login();

$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM courses');
$stmt->execute();
$courseTotal = (int) $stmt->fetch()['total'];

if (is_admin()) {
    $stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM users WHERE role = :role');
    $stmt->execute(['role' => 'user']);
    $studentTotal = (int) $stmt->fetch()['total'];

    $stmt = $pdo->prepare('SELECT id, course_code, course_name, instructor, credits FROM courses ORDER BY created_at DESC, id DESC LIMIT 5');
    $stmt->execute();
    $latestCourses = $stmt->fetchAll();
} else {
    $stmt = $pdo->prepare('SELECT id, course_code, course_name, instructor, credits, description FROM courses ORDER BY course_code ASC LIMIT 6');
    $stmt->execute();
    $latestCourses = $stmt->fetchAll();
}

$pageTitle = 'Dashboard | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow"><?php echo is_admin() ? 'Admin Dashboard' : 'Student Dashboard'; ?></p>
        <h1>Welcome, <?php echo e(current_user_name()); ?></h1>
        <p>Your role is <strong><?php echo e(current_user_role()); ?></strong>.</p>
    </div>
    <a class="btn secondary" href="courses.php">View Courses</a>
</section>

<?php if (is_admin()): ?>
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-number"><?php echo $courseTotal; ?></span>
            <span class="stat-label">Courses</span>
        </article>
        <article class="stat-card">
            <span class="stat-number"><?php echo $studentTotal; ?></span>
            <span class="stat-label">Regular Users</span>
        </article>
        <article class="stat-card">
            <span class="stat-number">4</span>
            <span class="stat-label">Admin Actions</span>
        </article>
    </section>

    <section class="action-panel">
        <div>
            <h2>Course Management</h2>
            <p>Add new courses, update course information, or remove courses that are no longer offered.</p>
        </div>
        <div class="button-row">
            <a class="btn primary" href="add_course.php">Add Course</a>
            <a class="btn secondary" href="courses.php">Manage Courses</a>
        </div>
    </section>

    <section class="content-section">
        <div class="section-header">
            <h2>Recently Added Courses</h2>
        </div>

        <?php if (empty($latestCourses)): ?>
            <div class="empty-state">No courses have been added yet.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Course</th>
                            <th>Instructor</th>
                            <th>Credits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($latestCourses as $course): ?>
                            <tr>
                                <td><?php echo e($course['course_code']); ?></td>
                                <td><?php echo e($course['course_name']); ?></td>
                                <td><?php echo e($course['instructor']); ?></td>
                                <td><?php echo e($course['credits']); ?></td>
                                <td>
                                    <a class="text-link" href="edit_course.php?id=<?php echo e($course['id']); ?>">Edit</a>
                                    <a class="text-link danger-text" href="delete_course.php?id=<?php echo e($course['id']); ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
<?php else: ?>
    <section class="stats-grid">
        <article class="stat-card">
            <span class="stat-number"><?php echo $courseTotal; ?></span>
            <span class="stat-label">Available Courses</span>
        </article>
        <article class="stat-card">
            <span class="stat-number">2</span>
            <span class="stat-label">Search Filters</span>
        </article>
    </section>

    <section class="content-section">
        <div class="section-header">
            <h2>Available Courses</h2>
            <a class="text-link" href="courses.php">Search all courses</a>
        </div>

        <?php if (empty($latestCourses)): ?>
            <div class="empty-state">No courses are currently available.</div>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach ($latestCourses as $course): ?>
                    <article class="course-card">
                        <div class="course-card-header">
                            <span class="course-code"><?php echo e($course['course_code']); ?></span>
                            <span class="credit-badge"><?php echo e($course['credits']); ?> credits</span>
                        </div>
                        <h2><?php echo e($course['course_name']); ?></h2>
                        <p class="muted">Instructor: <?php echo e($course['instructor']); ?></p>
                        <p><?php echo e($course['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
