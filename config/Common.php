<?php

namespace Blueprint\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    const VERSION = '0.0.1';

    public function define(Container $di)
    {
        if (!isset($di->params['Blueprint\Helper\Resolver'])) {
            $di->params['Blueprint\Helper\Resolver']['resolver'] = function ($cls) use ($di) {
                return $di->newInstance($cls);
            };
        }
        if (isset($di->setter['Blueprint\Helper\Resolver']['setNs'])) {
            array_unshift($di->setter['Blueprint\Helper\Resolver']['setNs'], 'Blueprint\DesignHelper');
        } else {
            $di->setter['Blueprint\Helper\Resolver']['setNs'] = ['Blueprint\DesignHelper'];
        }
        if (!$di->has('blueprint_resolver')) {
            $di->set('blueprint_resolver', $di->lazyNew('Blueprint\Helper\Resolver'));
        }
        if (!isset($di->setter['Blueprint\Extended']['addResolver'])) {
            $di->setter['Blueprint\Extended']['addResolver'] = $di->lazyGet('blueprint_resolver');
        }
    }
}
