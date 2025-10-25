<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Leads</h3>
  <a class="btn btn-primary" href="/leads/create">Novo lead</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Nome</th>
      <th>Telefone</th>
      <th>Email</th>
      <th>Estágio</th>
      <th>Origem</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach (($leads ?? []) as $l): ?>
    <tr>
      <td><?= (int)$l['id'] ?></td>
      <td><?= htmlspecialchars($l['name']) ?></td>
      <td><?= htmlspecialchars($l['phone'] ?? '') ?></td>
      <td><?= htmlspecialchars($l['email'] ?? '') ?></td>
      <td><?= htmlspecialchars($l['stage'] ?? '') ?></td>
      <td><?= htmlspecialchars($l['source'] ?? '') ?></td>
      <td class="d-flex gap-2">
        <a class="btn btn-sm btn-secondary" href="/leads/edit/<?= (int)$l['id'] ?>">Editar</a>
        <a class="btn btn-sm btn-info" href="/chat/index/<?= (int)$l['id'] ?>">Chat</a>
        <form method="post" action="/leads/delete/<?= (int)$l['id'] ?>" onsubmit="return confirm('Excluir lead?')">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
          <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
