<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth_check.php';

// ---- Fetch settings ----
$settings = $pdo->query("SELECT * FROM settings LIMIT 1")->fetch();
$currency = $settings['currency_symbol'] ?? 'TK';

// ---- Stat: Total tables ----
$totalTables = $pdo->query("SELECT COUNT(*) c FROM tables")->fetch()['c'];

// ---- Stat: Occupied now ----
$occupiedNow = $pdo->query("SELECT COUNT(*) c FROM tables WHERE status = 'occupied'")->fetch()['c'];

// ---- Stat: Today's revenue ----
$todayRevenue = $pdo->query("
    SELECT COALESCE(SUM(amount), 0) t FROM sessions
    WHERE status = 'completed' AND DATE(end_time) = CURDATE()
")->fetch()['t'];

// ---- Stat: Active sessions ----
$activeSessions = $pdo->query("SELECT COUNT(*) c FROM sessions WHERE status = 'active'")->fetch()['c'];

// ---- Live floor view: all tables + their active session if any ----
$tables = $pdo->query("
    SELECT t.*, s.id AS session_id, s.start_time
    FROM tables t
    LEFT JOIN sessions s ON s.table_id = t.id AND s.status = 'active'
    ORDER BY t.id ASC
")->fetchAll();

$pageTitle = 'Dashboard';
$pageSub   = 'Live overview of ' . htmlspecialchars($settings['club_name'] ?? 'your club');
include __DIR__ . '/../includes/header.php';
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="glass-card stat-card">
      <div class="stat-icon"><i class="fa-solid fa-table-cells"></i></div>
      <div class="stat-value"><?= (int)$totalTables ?></div>
      <div class="stat-label">Total Tables</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="glass-card stat-card" style="animation-delay:.05s">
      <div class="stat-icon"><i class="fa-solid fa-fire"></i></div>
      <div class="stat-value"><?= (int)$occupiedNow ?></div>
      <div class="stat-label">Occupied Now</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="glass-card stat-card" style="animation-delay:.1s">
      <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
      <div class="stat-value"><?= $currency ?><?= number_format($todayRevenue, 0) ?></div>
      <div class="stat-label">Today's Revenue</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="glass-card stat-card" style="animation-delay:.15s">
      <div class="stat-icon"><i class="fa-solid fa-stopwatch"></i></div>
      <div class="stat-value"><?= (int)$activeSessions ?></div>
      <div class="stat-label">Active Sessions</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <!-- Revenue chart -->
  <div class="col-lg-7">
    <div class="glass-card chart-panel h-100">
      <div class="panel-title">Revenue Trend</div>
      <div class="panel-sub">Last 7 days · completed sessions</div>
      <canvas id="revenueChart" height="130"></canvas>
    </div>
  </div>

  <!-- Rate / club info -->
  <div class="col-lg-5">
    <div class="glass-card chart-panel h-100 d-flex flex-column justify-content-between">
      <div>
        <div class="panel-title">Billing Rate</div>
        <div class="panel-sub">Applied to every table club-wide</div>
      </div>
      <div class="text-center py-3">
        <div class="live-timer" style="font-size:38px;"><?= $currency ?><?= number_format($settings['hourly_rate'], 0) ?></div>
        <div class="stat-label mt-2">per hour</div>
      </div>
      <div class="d-flex justify-content-between" style="font-size:12.5px; color:var(--text-secondary);">
        <span><i class="fa-solid fa-building me-1"></i> <?= htmlspecialchars($settings['club_name']) ?></span>
        <span><?= (int)$totalTables ?> tables active</span>
      </div>
    </div>
  </div>
</div>

<!-- Live floor view -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <div class="panel-title">Live Table Floor</div>
    <div class="panel-sub" style="margin-bottom:0;">Real-time status — full table management arrives in Milestone 2</div>
  </div>
</div>

<div class="row g-3">
  <?php foreach ($tables as $t): ?>
    <div class="col-6 col-lg-4 col-xl-3">
      <div class="glass-card table-card">
        <div class="table-card-head">
          <div>
            <div class="table-name"><?= htmlspecialchars($t['table_name']) ?></div>
            <div class="table-type"><?= htmlspecialchars($t['table_type']) ?></div>
          </div>
          <span class="status-pill <?= $t['status'] ?>">
            <span class="dot"></span><?= ucfirst($t['status']) ?>
          </span>
        </div>

        <?php if ($t['status'] === 'occupied' && $t['start_time']): ?>
          <div class="live-timer" data-start="<?= htmlspecialchars($t['start_time']) ?>">00:00:00</div>
          <div class="meta-row"><span>Started</span><span><?= date('h:i A', strtotime($t['start_time'])) ?></span></div>
        <?php elseif ($t['status'] === 'maintenance'): ?>
          <div style="color:var(--text-muted); font-size:13px; padding:10px 0;">
            <i class="fa-solid fa-wrench me-1"></i> Under maintenance
          </div>
        <?php else: ?>
          <div style="color:var(--text-secondary); font-size:13px; padding:10px 0;">
            <i class="fa-regular fa-circle-check me-1"></i> Ready to seat players
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<script>
  // Revenue chart
  fetch('<?= BASE_URL ?>api/revenue_chart.php')
    .then(r => r.json())
    .then(data => {
      const ctx = document.getElementById('revenueChart');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            label: 'Revenue',
            data: data.data,
            borderColor: '#00ff9c',
            backgroundColor: 'rgba(0,255,156,0.12)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: '#00ff9c',
            pointBorderColor: '#08090b',
          }]
        },
        options: {
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#93a0ad' } },
            y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#93a0ad' }, beginAtZero: true }
          }
        }
      });
    });
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
