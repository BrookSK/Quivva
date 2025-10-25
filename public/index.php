<?php
require __DIR__ . '/../vendor/autoload.php';

use App\core\App;
use App\core\Env;

$envPath = __DIR__ . '/../.env';
Env::load($envPath);

$app = new App();
$app->run();
