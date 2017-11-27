# Blueprint

## Installation


The preferred method of installing this library is with
[Composer](https://getcomposer.org/) by running the following from your project
root:

    $ composer require sunkan/blueprint

## Using

### Simple example

Without external dependencies

```php
//index.php
$blueprint = new \Blueprint\Simple(new \Blueprint\Helper\ResolverList());
$blueprint->name = 'Blueprint';

//With the simple renderer youo have to add the complete path to template
echo $blueprint->render('tpls/index.php');
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

### Example with template finder

```php
//index.php
//if multiple directorys are specified they are search in lifo order
$finder = new \Blueprint\DefaultFinder();
$finder->addPath(__DIR__ . '/tpls/');

$template = new \Blueprint\Extended(
    $finder,
    new \Blueprint\Helper\ResolverList()
);
$template->name = "Sunkan";

//will include tpl from tpls/welcome.php
$response = $template->render('welcome');

//will include tpl from tpls/welcome.test.php
$response = $template->render('welcome', 'test');

echo $response;
```

And the tpl file

```html
<!--tpls/welcome.php-->
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


```php
//index.php
//Same as previews example
//But you need to add a resolver to find the helper classes

$resolver = new \Blueprint\Helper\Resolver(function($cls) use ($finder) {
    return new $cls($finder);
});
$resolver->addNs('Blueprint\DesignHelper');

$blueprint->addResolver($resolver);

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
//index.php
//if multiple directorys are specified they are search in lifo order
$finder = new \Blueprint\DefaultFinder();
$finder->addPath(__DIR__ . '/tpls/');

$resolver = new \Blueprint\Helper\Resolver(function($cls) use ($finder) {
    return new $cls($finder);
});
$resolver->addNs('Blueprint\DesignHelper');
$resolverList = new \Blueprint\Helper\ResolverList([$resolver]);

$content = new \Blueprint\Extended($finder, $resolverList);
$content->setTemplate('index.php');
$content->name = "Sunkan";

$blueprint = new \Blueprint\Layout($finder, $resolverList);
$blueprint->setTemplate('layout/main');
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