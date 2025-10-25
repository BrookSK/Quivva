<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<h3>Novo Lead</h3>
<form method="post" action="/leads/store">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input class="form-control" type="text" name="name" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Telefone</label>
      <input class="form-control" type="text" name="phone">
    </div>
    <div class="col-md-3">
      <label class="form-label">Email</label>
      <input class="form-control" type="email" name="email">
    </div>
    <div class="col-md-3">
      <label class="form-label">Estágio</label>
      <input class="form-control" type="text" name="stage" value="Novo">
    </div>
    <div class="col-md-3">
      <label class="form-label">Origem</label>
      <input class="form-control" type="text" name="source">
    </div>
  </div>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Salvar</button>
    <a class="btn btn-secondary" href="/leads/index">Cancelar</a>
  </div>
</form>
