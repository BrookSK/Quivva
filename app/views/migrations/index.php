<?php
use App\core\Auth;
$applied = $applied ?? [];
$pending = $pending ?? [];
?>
<h3>Migrations</h3>
<div class="row g-3">
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header">Aplicadas (<?= count($applied) ?>)</div>
      <div class="card-body">
        <?php if (!$applied): ?>
          <p class="text-muted mb-0">Nenhuma migration aplicada.</p>
        <?php else: ?>
          <ul class="list-group">
            <?php foreach ($applied as $m): ?>
              <li class="list-group-item small"><?= htmlspecialchars($m) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Pendentes (<?= count($pending) ?>)</span>
        <button id="btn-run" class="btn btn-sm btn-primary">Aplicar</button>
      </div>
      <div class="card-body">
        <?php if (!$pending): ?>
          <p class="text-muted mb-0">Nenhuma migration pendente.</p>
        <?php else: ?>
          <ul class="list-group" id="pending-list">
            <?php foreach ($pending as $m): ?>
              <li class="list-group-item small"><?= htmlspecialchars($m) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('btn-run')?.addEventListener('click', async () => {
    const res = await fetch('/migrations/up');
    if (!res.ok) { alert('Erro ao aplicar migrations'); return; }
    const data = await res.json();
    alert('Migrations aplicadas: ' + data.map(d => d.name + ' [' + d.status + ']').join('\n'));
    location.reload();
  });
</script>
