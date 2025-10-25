<?php
use App\core\Auth;
$csrf = Auth::csrfToken();
?>
<h3>Chat com <?= htmlspecialchars($lead['name']) ?></h3>
<div id="chat-box" class="border rounded p-3 mb-3 chat-box" data-lead-id="<?= (int)$lead['id'] ?>">
  <?php foreach (($messages ?? []) as $m): ?>
    <div class="mb-2">
      <span class="badge bg-<?= $m['sender']==='user'?'primary':($m['sender']==='system'?'secondary':'success') ?>">
        <?= htmlspecialchars(ucfirst($m['sender'])) ?>
      </span>
      <span class="ms-2"><?= nl2br(htmlspecialchars($m['message'])) ?></span>
      <small class="text-muted ms-2"><?= htmlspecialchars($m['created_at']) ?></small>
    </div>
  <?php endforeach; ?>
</div>
<form id="chat-form" class="d-flex gap-2" method="post" action="/chat/send" onsubmit="return false;">
  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
  <input type="hidden" name="lead_id" value="<?= (int)$lead['id'] ?>">
  <input id="chat-input" class="form-control" type="text" name="message" placeholder="Digite sua mensagem..." required>
  <button id="chat-send" class="btn btn-primary" type="submit">Enviar</button>
</form>
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('chat-box');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const leadId = box.getAttribute('data-lead-id');

    async function poll() {
      try {
        const res = await fetch(`/chat/poll/${leadId}`);
        if (!res.ok) return;
        const data = await res.json();
        box.innerHTML = '';
        for (const m of data.messages) {
          const badgeClass = m.sender === 'user' ? 'primary' : (m.sender === 'system' ? 'secondary' : 'success');
          const div = document.createElement('div');
          div.className = 'mb-2';
          div.innerHTML = `<span class="badge bg-${badgeClass}">${m.sender.charAt(0).toUpperCase()+m.sender.slice(1)}</span>`+
            `<span class="ms-2"></span>`+
            `<small class="text-muted ms-2">${m.created_at ?? ''}</small>`;
          div.querySelector('span.ms-2').textContent = m.message;
          box.appendChild(div);
        }
        box.scrollTop = box.scrollHeight;
      } catch (e) { /* silent */ }
    }

    form.addEventListener('submit', async () => {
      const formData = new FormData(form);
      const res = await fetch('/chat/send', { method: 'POST', body: formData });
      if (res.ok) {
        input.value = '';
        poll();
      }
    });

    poll();
    setInterval(poll, 3000);
  });
</script>
