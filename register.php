<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf('register.php');

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || strlen($name) < 2 || strlen($name) > 100) {
        $errors[] = 'Name must be between 2 and 100 characters.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 150) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Password confirmation does not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $errors[] = 'An account with that email already exists.';
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
        ]);

        set_flash('success', 'Registration successful. You can now log in.');
        redirect('login.php');
    }
}

$pageTitle = 'Register | College Course Management System';
require_once 'includes/header.php';
?>

<section class="auth-layout">
    <div class="auth-copy">
        <p class="eyebrow">Student Access</p>
        <h1>Create an Account</h1>
        <p>Register as a regular user to browse and search available college courses.</p>
    </div>

    <form class="form-card" method="POST" action="register.php" data-validate>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

        <h2>Registration</h2>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="name">Full Name</label>
        <input id="name" name="name" type="text" value="<?php echo e($name); ?>" required minlength="2" maxlength="100">

        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" value="<?php echo e($email); ?>" required maxlength="150">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required minlength="6">

        <label for="confirm_password">Confirm Password</label>
        <input id="confirm_password" name="confirm_password" type="password" required minlength="6">

        <button class="btn primary full" type="submit">Create Account</button>
        <p class="form-note">Already have an account? <a href="login.php">Log in</a>.</p>
    </form>
</section>

<?php require_once 'includes/footer.php'; ?>
