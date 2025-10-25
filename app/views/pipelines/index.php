<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Pipelines</h3>
  <a class="btn btn-primary" href="/pipelines/create">Novo pipeline</a>
</div>
<p class="text-muted">Arraste para reordenar.</p>
<ul id="pipeline-list" class="list-group mb-3">
  <?php foreach (($pipelines ?? []) as $p): ?>
    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= (int)$p['id'] ?>">
      <span class="handle me-2">☰</span>
      <span><?= htmlspecialchars($p['name']) ?></span>
      <div>
        <a class="btn btn-sm btn-secondary" href="/pipelines/edit/<?= (int)$p['id'] ?>">Editar</a>
        <form class="d-inline" method="post" action="/pipelines/delete/<?= (int)$p['id'] ?>" onsubmit="return confirm('Excluir pipeline?')">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
          <button class="btn btn-sm btn-danger" type="submit">Excluir</button>
        </form>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
<button id="save-order" class="btn btn-outline-primary btn-sm">Salvar ordem</button>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('pipeline-list');
    let dragSrc;
    list.querySelectorAll('li').forEach(item => {
      item.draggable = true;
      item.addEventListener('dragstart', e => { dragSrc = item; e.dataTransfer.effectAllowed = 'move'; });
      item.addEventListener('dragover', e => { e.preventDefault(); });
      item.addEventListener('drop', e => { e.preventDefault(); if (dragSrc && dragSrc !== item) { item.parentNode.insertBefore(dragSrc, item); } });
    });
    document.getElementById('save-order').addEventListener('click', async () => {
      const order = Array.from(list.querySelectorAll('li')).map(li => li.getAttribute('data-id'));
      const res = await fetch('/pipelines/reorder', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ order }) });
      if (res.ok) alert('Ordem salva');
    });
  });
</script>
