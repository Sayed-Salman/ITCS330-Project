<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_login();

$errors = [];

$stmt = $pdo->prepare('SELECT id, name, email, role, created_at FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => current_user_id()]);
$user = $stmt->fetch();

if (!$user) {
    set_flash('error', 'Your account could not be found.');
    redirect('logout.php');
}

$name = $user['name'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf('profile.php');

    $name = trim($_POST['name'] ?? '');

    if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Name must be between 2 and 100 characters.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('UPDATE users SET name = :name WHERE id = :id');
        $stmt->execute([
            'name' => $name,
            'id' => current_user_id(),
        ]);

        $_SESSION['user_name'] = $name;
        set_flash('success', 'Profile updated successfully.');
        redirect('profile.php');
    }
}

$pageTitle = 'Profile | College Course Management System';
require_once 'includes/header.php';
?>

<section class="page-heading">
    <div>
        <p class="eyebrow">Account</p>
        <h1>Profile</h1>
        <p>Review your account details and update your display name.</p>
    </div>
</section>

<section class="profile-grid">
    <form class="form-card" method="POST" action="profile.php" data-validate>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

        <h2>Update Profile</h2>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="name">Full Name</label>
        <input id="name" name="name" type="text" value="<?php echo e($name); ?>" required minlength="2" maxlength="100">

        <button class="btn primary full" type="submit">Save Changes</button>
    </form>

    <aside class="profile-summary">
        <h2>Account Details</h2>
        <dl>
            <dt>Email</dt>
            <dd><?php echo e($user['email']); ?></dd>

            <dt>Role</dt>
            <dd><?php echo e($user['role']); ?></dd>

            <dt>Created At</dt>
            <dd><?php echo e($user['created_at']); ?></dd>
        </dl>
    </aside>
</section>

<?php require_once 'includes/footer.php'; ?>
