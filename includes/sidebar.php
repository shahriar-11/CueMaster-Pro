<?php
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$currentDir  = basename(dirname($_SERVER['SCRIPT_NAME']));
function navActive($dir, $target) { return $dir === $target ? 'active' : ''; }
?>
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="cue-mark"><i class="fa-solid fa-circle-dot"></i></div>
    <div class="brand-text">
      <div class="title">CueMaster Pro</div>
      <div class="subtitle">Club Management</div>
    </div>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Overview</div>
    <a href="<?= BASE_URL ?>dashboard/index.php" class="nav-item <?= navActive($currentDir, 'dashboard') ?>">
      <i class="fa-solid fa-gauge-high"></i> Dashboard
    </a>

    <div class="nav-section-label">Operations</div>
    <span class="nav-item disabled"><i class="fa-solid fa-table-cells"></i> Tables <span class="nav-badge">M2</span></span>
    <span class="nav-item disabled"><i class="fa-solid fa-stopwatch"></i> Live Sessions <span class="nav-badge">M2</span></span>
    <span class="nav-item disabled"><i class="fa-solid fa-users"></i> Members <span class="nav-badge">M2</span></span>
    <span class="nav-item disabled"><i class="fa-solid fa-file-invoice-dollar"></i> Invoices <span class="nav-badge">M2</span></span>

    <div class="nav-section-label">Club</div>
    <span class="nav-item disabled"><i class="fa-solid fa-trophy"></i> Tournaments <span class="nav-badge">M3</span></span>
    <span class="nav-item disabled"><i class="fa-solid fa-id-badge"></i> Staff <span class="nav-badge">M3</span></span>

    <div class="nav-section-label">Finance</div>
    <span class="nav-item disabled"><i class="fa-solid fa-money-bill-wave"></i> Expenses <span class="nav-badge">M4</span></span>
    <span class="nav-item disabled"><i class="fa-solid fa-chart-line"></i> Reports <span class="nav-badge">M4</span></span>

    <div class="nav-section-label">System</div>
    <span class="nav-item disabled"><i class="fa-solid fa-gear"></i> Settings <span class="nav-badge">Soon</span></span>
  </nav>

  <div class="sidebar-footer">
    CueMaster Pro v1.0<br>Milestone 1 — Foundation
  </div>
</aside>
