<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Fluxos</h3>
  <a class="btn btn-primary" href="/flows/create">Novo fluxo</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Nome</th>
      <th>Criado</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach (($flows ?? []) as $f): ?>
    <tr>
      <td><?= (int)$f['id'] ?></td>
      <td><?= htmlspecialchars($f['name']) ?></td>
      <td><?= htmlspecialchars($f['created_at'] ?? '') ?></td>
      <td class="d-flex gap-2">
        <a class="btn btn-sm btn-secondary" href="/flows/edit/<?= (int)$f['id'] ?>">Editar</a>
        <a class="btn btn-sm btn-info" href="/flows/run/<?= (int)$f['id'] ?>">Rodar</a>
        <form method="post" action="/flows/delete/<?= (int)$f['id'] ?>" onsubmit="return confirm('Excluir fluxo?')">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
          <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
