  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?php $config = require __DIR__ . '/../../../config/config.php'; $base = rtrim($config['base_url'] ?? '/', '/'); ?>
  <script src="<?= $base ?>/assets/js/app.js"></script>
</body>
</html>
