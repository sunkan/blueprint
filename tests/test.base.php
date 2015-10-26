<?php

include '../vendor/autoload.php';

use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory);

$config = $di->newInstance('Blueprint\_Config\Common');
$config->define($di);

$base = __DIR__;

$di->setter['Blueprint\Extended']['setBasePath'] = [
    $base . '/tpls/'
];
