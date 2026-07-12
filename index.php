<?php
require_once __DIR__ . '/config/constants.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'dashboard/index.php');
} else {
    header('Location: ' . BASE_URL . 'auth/login.php');
}
exit;
