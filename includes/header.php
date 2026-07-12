<?php
/**
 * Shared header — included at the top of every protected (dashboard-layout) page.
 * Expects $pageTitle and $pageSub to be set before include.
 */
$pageTitle = $pageTitle ?? 'Dashboard';
$pageSub   = $pageSub ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle) ?> · CueMaster Pro</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<!-- App theme -->
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="app-wrapper">

  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <?php include __DIR__ . '/sidebar.php'; ?>

  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle-btn" id="sidebarToggle" type="button">
          <i class="fa-solid fa-bars"></i>
        </button>
        <div>
          <div class="page-title"><?= htmlspecialchars($pageTitle) ?></div>
          <?php if ($pageSub): ?><div class="page-sub"><?= htmlspecialchars($pageSub) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="dropdown">
        <div class="user-chip glass" role="button" data-bs-toggle="dropdown">
          <div class="avatar"><?= strtoupper(substr($_SESSION['full_name'] ?? 'A', 0, 1)) ?></div>
          <div class="d-none d-sm-block">
            <div style="font-size:13px; font-weight:600;"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Admin') ?></div>
            <div style="font-size:10.5px; color:var(--text-muted); text-transform:uppercase;"><?= htmlspecialchars($_SESSION['role'] ?? 'admin') ?></div>
          </div>
          <i class="fa-solid fa-chevron-down" style="font-size:10px; color:var(--text-muted);"></i>
        </div>
        <ul class="dropdown-menu dropdown-menu-end" style="background:var(--bg-panel); border:1px solid var(--glass-border);">
          <li><a class="dropdown-item text-light" href="<?= BASE_URL ?>auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
