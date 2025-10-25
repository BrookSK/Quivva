<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<h3>Novo Fluxo</h3>
<form method="post" action="/flows/store" onsubmit="return saveDefinition();">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
  <div class="mb-3">
    <label class="form-label">Nome do fluxo</label>
    <input class="form-control" type="text" name="name" placeholder="Ex: Boas-vindas" required>
  </div>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-header">Blocos</div>
        <div class="card-body">
          <button type="button" class="btn btn-outline-secondary w-100 mb-2" onclick="addBlock('text')">Texto</button>
          <button type="button" class="btn btn-outline-secondary w-100" onclick="addBlock('question')">Pergunta</button>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card h-100">
        <div class="card-header">Fluxo</div>
        <div id="flow-canvas" class="card-body min-vh-25" style="min-height:300px; background:#fff;">
          <p class="text-muted">Adicione blocos e edite seus conteúdos.</p>
        </div>
      </div>
    </div>
  </div>
  <textarea id="definition" name="definition" class="d-none"></textarea>
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary" type="submit">Salvar</button>
    <a class="btn btn-secondary" href="/flows/index">Cancelar</a>
  </div>
</form>
<script>
  const canvas = document.getElementById('flow-canvas');
  function addBlock(type) {
    const el = document.createElement('div');
    el.className = 'border rounded p-2 mb-2';
    if (type === 'text') {
      el.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <strong>Texto</strong>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('div.border').remove()">remover</button>
        </div>
        <div class="mt-2">
          <textarea class="form-control" placeholder="Mensagem de texto, ex: Olá {{nome}}"></textarea>
        </div>`;
      el.dataset.type = 'text';
    } else if (type === 'question') {
      el.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
          <strong>Pergunta</strong>
          <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('div.border').remove()">remover</button>
        </div>
        <div class="row g-2 mt-2">
          <div class="col-md-6"><input class="form-control" placeholder="Nome da variável, ex: produto"></div>
          <div class="col-md-6"><input class="form-control" placeholder="Pergunta, ex: Qual produto?"></div>
        </div>`;
      el.dataset.type = 'question';
    }
    makeDraggable(el);
    canvas.appendChild(el);
  }
  function saveDefinition() {
    const blocks = [];
    canvas.querySelectorAll('div.border').forEach(b => {
      const type = b.dataset.type;
      if (type === 'text') {
        blocks.push({ type, text: b.querySelector('textarea').value || '' });
      } else if (type === 'question') {
        const inputs = b.querySelectorAll('input');
        blocks.push({ type, variable: inputs[0].value || '', text: inputs[1].value || '' });
      }
    });
    document.getElementById('definition').value = JSON.stringify({ blocks });
    return true;
  }
  function makeDraggable(item) {
    item.draggable = true;
    item.addEventListener('dragstart', e => { e.dataTransfer.setData('text/plain', 'drag'); item.classList.add('opacity-50'); });
    item.addEventListener('dragend', e => { item.classList.remove('opacity-50'); });
  }
  canvas.addEventListener('dragover', e => e.preventDefault());
  canvas.addEventListener('drop', e => {
    e.preventDefault();
    const after = document.elementFromPoint(e.clientX, e.clientY)?.closest('#flow-canvas > div.border');
    const dragging = canvas.querySelector('div.border.opacity-50');
    if (dragging && after && after !== dragging) {
      canvas.insertBefore(dragging, after);
    }
  });
</script>
