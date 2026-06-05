<?php
require_once __DIR__ . '/auth.php';

$pageTitle = $pageTitle ?? 'College Course Management System';
$currentPage = basename($_SERVER['PHP_SELF']);
$flashMessages = get_flash_messages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a class="brand" href="index.php">
                <span class="brand-mark">CC</span>
                <span>College Courses</span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="nav-links">
                <a class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>

                <?php if (is_logged_in()): ?>
                    <a class="<?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                    <a class="<?php echo $currentPage === 'courses.php' ? 'active' : ''; ?>" href="courses.php">Courses</a>

                    <?php if (is_admin()): ?>
                        <a class="<?php echo $currentPage === 'add_course.php' ? 'active' : ''; ?>" href="add_course.php">Add Course</a>
                    <?php endif; ?>

                    <a class="<?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>" href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="<?php echo $currentPage === 'login.php' ? 'active' : ''; ?>" href="login.php">Login</a>
                    <a class="<?php echo $currentPage === 'register.php' ? 'active' : ''; ?>" href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="page-shell">
        <?php if (!empty($flashMessages)): ?>
            <section class="messages" aria-live="polite">
                <?php foreach ($flashMessages as $type => $messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?php echo e($type); ?>">
                            <?php echo e($message); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
