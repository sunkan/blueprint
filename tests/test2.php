<?php

require 'test.base.php';

$engine = $di->newInstance('Blueprint\Extended');
$engine->name = "Sunkan";

$rs = $engine->render('test2.php');
echo $rs;

echo "\n";
