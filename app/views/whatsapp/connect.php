<?php
use App\core\Auth;
$connected = (bool)($connected ?? false);
$qr = urlencode((string)($qr_data ?? ''));
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Conectar WhatsApp</h3>
  <?php if ($connected): ?>
    <a class="btn btn-outline-danger" href="/whatsapp/disconnect">Desconectar</a>
  <?php endif; ?>
</div>
<?php if (!$connected): ?>
  <p>Escaneie o QR Code abaixo com o WhatsApp para conectar (simulado).</p>
  <div class="text-center">
    <img alt="QR" class="img-thumbnail" src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?= $qr ?>">
    <div class="mt-3">
      <button id="btn-simulate" class="btn btn-success">Simular leitura do QR</button>
    </div>
  </div>
  <script>
    document.getElementById('btn-simulate').addEventListener('click', async () => {
      const res = await fetch('/whatsapp/simulate');
      if (res.ok) location.reload();
    });
  </script>
<?php else: ?>
  <div class="alert alert-success">WhatsApp conectado com sucesso (simulado).</div>
<?php endif; ?>
