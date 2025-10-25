<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Leads</h5>
        <p class="display-6 mb-0"><?= (int)($leads_count ?? 0) ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Mensagens hoje</h5>
        <p class="display-6 mb-0"><?= (int)($messages_today ?? 0) ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Taxa de conversão</h5>
        <p class="display-6 mb-0"><?= htmlspecialchars($conversion_rate ?? '—') ?></p>
      </div>
    </div>
  </div>
</div>
