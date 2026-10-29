<?php
declare(strict_types=1);
require_once __DIR__ . '/session.php';
sh_boot();
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !sh_valid_csrf($_POST['csrf_token'] ?? null)) {
    header('Location: /PRACTICAL-10/dashboard.php'); exit;
}
sh_logout();
