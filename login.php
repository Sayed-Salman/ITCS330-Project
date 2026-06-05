<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if (is_logged_in()) {
    redirect('dashboard.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validate_csrf('login.php');

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            set_flash('success', 'Login successful.');
            redirect('dashboard.php');
        }

        $errors[] = 'Invalid email or password.';
    }
}

$pageTitle = 'Login | College Course Management System';
require_once 'includes/header.php';
?>

<section class="auth-layout">
    <div class="auth-copy">
        <p class="eyebrow">Secure Login</p>
        <h1>Access Your Dashboard</h1>
        <p>Admins can manage course records. Regular users can browse and search courses.</p>
    </div>

    <form class="form-card" method="POST" action="login.php" data-validate>
        <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">

        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" value="<?php echo e($email); ?>" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button class="btn primary full" type="submit">Login</button>
        <p class="form-note">Need an account? <a href="register.php">Register here</a>.</p>
    </form>
</section>

<?php require_once 'includes/footer.php'; ?>
