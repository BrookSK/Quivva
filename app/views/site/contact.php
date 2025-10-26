<?php
use App\core\Auth;
?>
<h1>Fale com a equipe</h1>
<p>Tem dúvidas sobre planos, integrações ou implantação? Envie uma mensagem.</p>
<form class="row g-3" method="post" action="#" onsubmit="alert('Mensagem enviada! (simulação)'); return false;">
  <div class="col-md-6">
    <label class="form-label">Nome</label>
    <input class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">E-mail</label>
    <input type="email" class="form-control" required>
  </div>
  <div class="col-12">
    <label class="form-label">Mensagem</label>
    <textarea class="form-control" rows="4" required></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" type="submit">Enviar</button>
    <a class="btn btn-link" href="/auth/register">Criar conta</a>
  </div>
</form>
