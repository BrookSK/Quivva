<?php
use App\core\Auth;
$config = require __DIR__ . '/../../../config/config.php';
$base = rtrim($config['base_url'] ?? '/', '/');
?><!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($config['app_name'] ?? 'Quivva') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= $base ?>/assets/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $base ?>/dashboard/index">Quivva</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <?php if (!Auth::check()): ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/site/home">Início</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/site/plans">Planos</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/site/contact">Contato</a></li>
        <?php else: $u = Auth::user(); $role = $u['role'] ?? 'user'; ?>
          <li class="nav-item"><a class="nav-link" href="<?= $base ?>/leads/index">Leads</a></li>
          <?php if ($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/pipelines/index">Pipelines</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/whatsapp/connect">WhatsApp</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= $base ?>/flows/index">Fluxos</a></li>
          <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (Auth::check()): $u = Auth::user(); ?>
          <li class="nav-item"><span class="navbar-text me-3">Olá, <?= htmlspecialchars($u['name'] ?? 'Usuário') ?></span></li>
          <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="<?= $base ?>/auth/logout">Sair</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="btn btn-outline-light btn-sm me-2" href="<?= $base ?>/auth/login">Entrar</a></li>
          <li class="nav-item"><a class="btn btn-primary btn-sm" href="<?= $base ?>/auth/register">Criar conta</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
  <?php if ($msg = Auth::flash('success')): ?>
    <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
  <?php if ($msg = Auth::flash('error')): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>
