<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    header("Location: $path");
    exit;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

function current_user_name()
{
    return $_SESSION['user_name'] ?? '';
}

function current_user_role()
{
    return $_SESSION['user_role'] ?? 'guest';
}

function is_admin()
{
    return current_user_role() === 'admin';
}

function set_flash($type, $message)
{
    $_SESSION['flash_messages'][$type][] = $message;
}

function get_flash_messages()
{
    $messages = $_SESSION['flash_messages'] ?? [];
    unset($_SESSION['flash_messages']);
    return $messages;
}

function require_login()
{
    if (!is_logged_in()) {
        set_flash('error', 'Please log in to continue.');
        redirect('login.php');
    }
}

function require_admin()
{
    require_login();

    if (!is_admin()) {
        set_flash('error', 'You do not have permission to access that page.');
        redirect('dashboard.php');
    }
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function validate_csrf($fallback = 'dashboard.php')
{
    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        set_flash('error', 'Invalid form submission. Please try again.');
        redirect($fallback);
    }
}
