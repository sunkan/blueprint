<?php

namespace Blueprint;

use Blueprint\Exception\TemplateNotFoundException;

class DefaultFinder implements FinderInterface
{
    protected $paths = [];

    public function __construct(array $paths = [])
    {
        foreach ($paths as $path) {
            $this->addPath($path);
        }
    }

    public function addPath(string $path)
    {
        if (!in_array($path, $this->paths)) {
            $this->paths[] = rtrim($path, DIRECTORY_SEPARATOR);
        }
    }

    public function findTemplate(string $file, string $type = null): string
    {
        $type = is_null($type) ? '' : '.' . $type;
        foreach (array_reverse($this->paths) as $path) {
            $filePath = $path . DIRECTORY_SEPARATOR . $file . $type . '.php';
            if (file_exists($filePath)) {
                return $filePath;
            }
        }

        throw new TemplateNotFoundException($file . ':' . $type);
    }
}
