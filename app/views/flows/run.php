<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
$def = json_decode($flow['definition'] ?? '{}', true);
?>
<h3>Rodar Fluxo: <?= htmlspecialchars($flow['name']) ?></h3>
<form method="post" action="/flows/execute">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
  <input type="hidden" name="flow_id" value="<?= (int)$flow['id'] ?>">
  <div class="mb-3">
    <label class="form-label">Selecione o Lead</label>
    <select class="form-select" name="lead_id" required>
      <option value="">-- Selecione --</option>
      <?php foreach (($leads ?? []) as $l): ?>
        <option value="<?= (int)$l['id'] ?>"><?= htmlspecialchars($l['name']) ?> (<?= htmlspecialchars($l['email'] ?? $l['phone'] ?? '') ?>)</option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Prévia</label>
    <pre class="bg-light p-2 border rounded" style="max-height:220px;overflow:auto;"><?= htmlspecialchars(json_encode($def, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) ?></pre>
  </div>
  <div class="d-flex gap-2">
    <button class="btn btn-primary" type="submit">Executar no Lead</button>
    <a class="btn btn-secondary" href="/flows/index">Voltar</a>
  </div>
</form>
