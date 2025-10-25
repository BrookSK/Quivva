<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <h3>Entrar</h3>
    <form method="post" action="/auth/login">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
      <div class="mb-3">
        <label class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary" type="submit">Entrar</button>
        <a class="btn btn-link" href="/auth/register">Criar conta</a>
      </div>
    </form>
  </div>
</div>
