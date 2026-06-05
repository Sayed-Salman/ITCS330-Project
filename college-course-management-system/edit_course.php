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

$stmt = $pdo->prepare('SELECT id, course_code, course_name, instructor, credits, description FROM courses WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$course = $stmt->fetch();

if (!$course) {
    set_flash('error', 'Course not found.');
    redirect('courses.php');
}

$errors = [];
$courseCode = $course['course_code'];
$courseName = $course['course_name'];
$instructor = $course['instructor'];
$credits = (string) $course['credits'];
$description = $course['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf('edit_course.php?id=' . $id);

    $courseCode = strtoupper(trim($_POST['course_code'] ?? ''));
    $courseName = trim($_POST['course_name'] ?? '');
    $instructor = trim($_POST['instructor'] ?? '');
    $credits = trim($_POST['credits'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($courseCode === '' || !preg_match('/^[A-Z0-9-]{2,20}$/', $courseCode)) {
        $errors[] = 'Course code must be 2 to 20 characters and use letters, numbers, or hyphens only.';
    }

    if ($courseName === '' || strlen($courseName) < 3 || strlen($courseName) > 120) {
        $errors[] = 'Course name must be between 3 and 120 characters.';
    }

    if ($instructor === '' || strlen($instructor) < 2 || strlen($instructor) > 100) {
        $errors[] = 'Instructor name must be between 2 and 100 characters.';
    }

    if (!ctype_digit($credits) || (int) $credits < 1 || (int) $credits > 6) {
        $errors[] = 'Credits must be a whole number between 1 and 6.';
    }

    if ($description === '' || strlen($description) > 1000) {
        $errors[] = 'Description is required and must be 1000 characters or less.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM courses WHERE course_code = :course_code AND id <> :id LIMIT 1');
        $stmt->execute([
            'course_code' => $courseCode,
            'id' => $id,
        ]);

        if ($stmt->fetch()) {
            $errors[] = 'Another course already uses this course code.';
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'UPDATE courses
             SET course_code = :course_code,
                 course_name = :course_name,
                 instructor = :instructor,
                 credits = :credits,
                 description = :description
             WHERE id = :id'
        );
        $stmt->execute([
            'course_code' => $courseCode,
            'course_name' => $courseName,
            'instructor' => $instructor,
            'credits' => (int) $credits,
            'description' => $description,
            'id' => $id,
        ]);

        set_flash('success', 'Course updated successfully.');
        redirect('courses.php');
    }
}

$pageTitle = 'Edit Course | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Admin</p>
        <h1>Edit Course</h1>
        <p>Update course information for <?php echo e($course['course_code']); ?>.</p>
    </div>
    <a class="btn secondary" href="courses.php">Back to Courses</a>
</section>

<form class="form-card wide" method="POST" action="edit_course.php?id=<?php echo e($id); ?>" data-validate>
    <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
    <input type="hidden" name="id" value="<?php echo e($id); ?>">

    <?php if (!empty($errors)): ?>
        <div class="message error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo e($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-grid">
        <div>
            <label for="course_code">Course Code</label>
            <input id="course_code" name="course_code" type="text" value="<?php echo e($courseCode); ?>" required maxlength="20" pattern="[A-Za-z0-9-]{2,20}">
        </div>

        <div>
            <label for="credits">Credits</label>
            <input id="credits" name="credits" type="number" value="<?php echo e($credits); ?>" required min="1" max="6">
        </div>
    </div>

    <label for="course_name">Course Name</label>
    <input id="course_name" name="course_name" type="text" value="<?php echo e($courseName); ?>" required minlength="3" maxlength="120">

    <label for="instructor">Instructor</label>
    <input id="instructor" name="instructor" type="text" value="<?php echo e($instructor); ?>" required minlength="2" maxlength="100">

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="5" required maxlength="1000"><?php echo e($description); ?></textarea>

    <div class="button-row">
        <button class="btn primary" type="submit">Update Course</button>
        <a class="btn secondary" href="courses.php">Cancel</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
