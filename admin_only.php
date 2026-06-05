<?php
require_once 'includes/auth.php';

require_admin();

$pageTitle = 'Admin Only | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Access Control</p>
        <h1>Admin Only</h1>
        <p>This page confirms that only users with the admin role can access protected admin pages.</p>
    </div>
</section>

<section class="action-panel">
    <div>
        <h2>Protected Tools</h2>
        <p>Use the admin dashboard and course management pages to maintain course records.</p>
    </div>
    <div class="button-row">
        <a class="btn primary" href="add_course.php">Add Course</a>
        <a class="btn secondary" href="courses.php">Manage Courses</a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
