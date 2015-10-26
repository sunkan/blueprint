# Blueprint

## Installation

To add this package as a local, per-project dependency to your project, simply add a dependency on `sunkan/blueprint` to your project's `composer.json` file. Here is a minimal example of a `composer.json` file that just defines a dependency on Text_Template:

    {
        "require": {
            "sunkan/blueprint": "~1.*"
        }
    }

## Using

### Simple example

With Aura.Di and Composer

```php
<!--index.php-->
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

$blueprint = $di->newInstance('Blueprint\Extended');
$blueprint->name = "Blueprint test";

echo $blueprint->render('index.php');

```

And the tpl file

```html
<!--tpls/index.php-->
<html>
<head>
<title>Index</title>
</head>
<body>
<h1>Welcome: <?=$name?></h1>

</body>
</html>
```

### Layout example 1

```
<!--index.php-->
Same as previews example
```

And the tpl file

```html
<!--tpls/index.php-->
<?php $view->design()->header()?>
<h1>Welcome: <?=$name?></h1>
<?php $view->design()->footer()?>
```

```html
<!--tpls/layout/header.php-->
<html>
<head>
  <title>Layout type 1</title>
</head>
<body>
```

```html
<!--tpls/layout/footer.php-->
</body>
</html>
```

#### Output:

```html
<html>
<head>
  <title>Layout type 1</title>
</head>
<body>
<h1>Welcome: Blueprint test</h1>
</body>
</html>
```

### Layout example 2

```php
<!--index.php-->
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

$content = $di->newInstance('Blueprint\Extended');
$content->setTemplate('index.php');
$content->name = "Sunkan";

$blueprint = $di->newInstance('Blueprint\Layout');
$blueprint->setTemplate('layout/main.php');
$blueprint->setContent($content);

echo $blueprint->render();
```

And the tpl file

```html
<!--tpls/index.php-->
<?php $view->title('Set title from tpl');?>
<h1>Welcome: <?=$name?></h1>
```

```html
<!--tpls/layout/main.php-->
<html>
<head>
  <title>Layout type 2 - <?=$view->title()?></title>
</head>
<body>

  <?=$content?>

</body>
</html>
```

#### Output:

```html
<html>
<head>
  <title>Layout type 2 - Set title from tpl</title>
</head>
<body>
<h1>Welcome: Blueprint test</h1>
</body>
</html>
```