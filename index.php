<?php
// Front controller fallback at project root for hosts pointing DocumentRoot to repo root.
// Prefer configuring DocumentRoot to public/; this file includes public/index.php as a fallback.
require __DIR__ . '/public/index.php';
