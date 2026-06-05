<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_admin();

$id = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT)
    : filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    set_flash('error', 'Invalid course ID.');
    redirect('courses.php');
}

$stmt = $pdo->prepare('SELECT id, course_code, course_name, instructor, credits FROM courses WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$course = $stmt->fetch();

if (!$course) {
    set_flash('error', 'Course not found.');
    redirect('courses.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf('delete_course.php?id=' . $id);

    $stmt = $pdo->prepare('DELETE FROM courses WHERE id = :id');
    $stmt->execute(['id' => $id]);

    set_flash('success', 'Course deleted successfully.');
    redirect('courses.php');
}

$pageTitle = 'Delete Course | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Admin</p>
        <h1>Delete Course</h1>
        <p>Confirm that this course should be removed from the catalog.</p>
    </div>
    <a class="btn secondary" href="courses.php">Back to Courses</a>
</section>

<section class="confirm-panel">
    <h2><?php echo e($course['course_code']); ?> - <?php echo e($course['course_name']); ?></h2>
    <p>Instructor: <?php echo e($course['instructor']); ?></p>
    <p>Credits: <?php echo e($course['credits']); ?></p>
    <p class="danger-text">This action cannot be undone.</p>

    <form method="POST" action="delete_course.php?id=<?php echo e($id); ?>" data-confirm="Delete this course?">
        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
        <input type="hidden" name="id" value="<?php echo e($id); ?>">

        <div class="button-row">
            <button class="btn danger" type="submit">Delete Course</button>
            <a class="btn secondary" href="courses.php">Cancel</a>
        </div>
    </form>
</section>

<?php require_once 'includes/footer.php'; ?>
