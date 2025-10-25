<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<h3>Novo Pipeline</h3>
<form method="post" action="/pipelines/store">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input class="form-control" type="text" name="name" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Posição</label>
      <input class="form-control" type="number" name="position" value="0">
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Salvar</button>
    <a class="btn btn-secondary" href="/pipelines/index">Cancelar</a>
  </div>
</form>
