<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_login();

$search = trim($_GET['search'] ?? '');
$credits = trim($_GET['credits'] ?? '');
$filterErrors = [];

if (strlen($search) > 100) {
    $search = substr($search, 0, 100);
    $filterErrors[] = 'Search text was shortened to 100 characters.';
}

if ($credits !== '' && (!ctype_digit($credits) || (int) $credits < 1 || (int) $credits > 6)) {
    $credits = '';
    $filterErrors[] = 'Credit filter must be between 1 and 6.';
}

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(course_code LIKE :search OR course_name LIKE :search OR instructor LIKE :search OR description LIKE :search)';
    $params['search'] = '%' . $search . '%';
}

if ($credits !== '') {
    $where[] = 'credits = :credits';
    $params['credits'] = (int) $credits;
}

$sql = 'SELECT id, course_code, course_name, instructor, credits, description, created_at FROM courses';

if (!empty($where)) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY course_code ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

$pageTitle = 'Courses | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow"><?php echo is_admin() ? 'Course Administration' : 'Course Catalog'; ?></p>
        <h1>Courses</h1>
        <p><?php echo is_admin() ? 'View, edit, and delete course records.' : 'Search and filter available college courses.'; ?></p>
    </div>

    <?php if (is_admin()): ?>
        <a class="btn primary" href="add_course.php">Add Course</a>
    <?php endif; ?>
</section>

<?php if (!empty($filterErrors)): ?>
    <div class="message warning">
        <?php foreach ($filterErrors as $error): ?>
            <p><?php echo e($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<section class="toolbar">
    <form class="search-form" method="GET" action="courses.php" data-validate>
        <div class="field-inline">
            <label for="search">Search</label>
            <input id="search" name="search" type="search" value="<?php echo e($search); ?>" maxlength="100" placeholder="Code, name, instructor" data-live-course-search>
        </div>

        <div class="field-inline">
            <label for="credits">Credits</label>
            <select id="credits" name="credits">
                <option value="">All</option>
                <?php for ($i = 1; $i <= 6; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php echo $credits === (string) $i ? 'selected' : ''; ?>>
                        <?php echo $i; ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <button class="btn primary" type="submit">Search</button>
        <a class="btn secondary" href="courses.php">Reset</a>
    </form>
    <p class="result-count" data-course-result-count>
        Showing <?php echo count($courses); ?> course<?php echo count($courses) === 1 ? '' : 's'; ?>
    </p>
</section>

<?php if (empty($courses)): ?>
    <div class="empty-state">No courses matched your search.</div>
<?php else: ?>
    <section class="course-grid" data-course-list>
        <?php foreach ($courses as $course): ?>
            <article
                class="course-card"
                data-course-card
                data-search-text="<?php echo e(strtolower($course['course_code'] . ' ' . $course['course_name'] . ' ' . $course['instructor'] . ' ' . $course['description'])); ?>"
            >
                <div class="course-card-header">
                    <span class="course-code"><?php echo e($course['course_code']); ?></span>
                    <span class="credit-badge"><?php echo e($course['credits']); ?> credits</span>
                </div>

                <h2><?php echo e($course['course_name']); ?></h2>
                <p class="muted">Instructor: <?php echo e($course['instructor']); ?></p>
                <p><?php echo e($course['description']); ?></p>

                <?php if (is_admin()): ?>
                    <div class="card-actions">
                        <a class="btn small secondary" href="edit_course.php?id=<?php echo e($course['id']); ?>">Edit</a>
                        <a class="btn small danger" href="delete_course.php?id=<?php echo e($course['id']); ?>">Delete</a>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
