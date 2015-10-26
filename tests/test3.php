<?php

require 'test.base.php';


$content = $di->newInstance('Blueprint\Extended');
$content->name = "Sunkan";
$engine = $di->newInstance('Blueprint\Layout');
$engine->setContent($content);

$content->setTemplate('test3.php');

$rs = $engine->render('layout/main.php');
echo $rs;

echo "\n";
