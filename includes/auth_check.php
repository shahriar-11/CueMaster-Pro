<?php
/**
 * Auth Guard — include at the very top of every protected page.
 * Redirects to login if no valid session exists.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'auth/login.php');
    exit;
}
