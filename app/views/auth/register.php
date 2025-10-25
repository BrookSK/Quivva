<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h3>Criar conta</h3>
    <form method="post" action="/auth/register">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Empresa (ID)</label>
        <input type="number" name="company_id" class="form-control" value="1" required>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Registrar</button>
        <a class="btn btn-link" href="/auth/login">Já tenho conta</a>
      </div>
    </form>
  </div>
</div>
