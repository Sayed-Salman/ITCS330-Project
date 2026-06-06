<?php
require_once 'includes/auth.php';

$pageTitle = 'Home | College Course Management System';
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">ITCS330 Course Project</p>
        <h1>College Course Management System</h1>
        <p class="hero-text">
            A database-driven website for managing university courses with secure login,
            role-based access, course search, and complete admin CRUD tools.
        </p>
        <div class="hero-actions">
            <?php if (is_logged_in()): ?>
                <a class="btn primary" href="dashboard.php">Go to Dashboard</a>
                <a class="btn secondary" href="courses.php">Browse Courses</a>
            <?php else: ?>
                <a class="btn primary" href="login.php">Login</a>
                <a class="btn secondary" href="register.php">Create Account</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="hero-panel" aria-label="Course management preview">
        <div class="panel-top">
            <span class="status-dot"></span>
            <span>Academic Portal</span>
        </div>
        <div class="preview-row">
            <strong>ITCS330</strong>
            <span>Database Driven Websites</span>
        </div>
        <div class="preview-stats">
            <div>
                <span class="stat-number">2</span>
                <span class="stat-label">User Roles</span>
            </div>
            <div>
                <span class="stat-number">4</span>
                <span class="stat-label">CRUD Actions</span>
            </div>
        </div>
    </div>
</section>

<section class="feature-grid">
    <article class="feature-card">
        <h2>Secure Accounts</h2>
        <p>Registration and login use PHP sessions, password hashing, and password verification.</p>
    </article>
    <article class="feature-card">
        <h2>Admin Management</h2>
        <p>Administrators can add, view, edit, and delete course records from protected pages.</p>
    </article>
    <article class="feature-card">
        <h2>Course Search</h2>
        <p>Regular users can view available courses and filter them by keyword or credit value.</p>
    </article>
</section>

<?php require_once 'includes/footer.php'; ?>
