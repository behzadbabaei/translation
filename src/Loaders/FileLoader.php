<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Loaders;

use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Illuminate\Contracts\Translation\Loader;

class FileLoader implements Loader
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var LaravelFileLoader
     */
    protected $loader;

    /**
     * @param string $defaultLocale
     * @param LaravelFileLoader $loader
     */
    public function __construct(string $defaultLocale, LaravelFileLoader $loader)
    {
        $this->defaultLocale = $defaultLocale;
        $this->loader = $loader;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = '*')
    {
        return $this->loadSource($locale, $group, $namespace);
    }

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     * @return array
     */
    public function loadSource($locale, $group, $namespace = '*')
    {
        return $this->loader->load($locale, $group, $namespace);
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->loader->addNamespace($namespace, $hint);
    }

    /**
     * Add a new JSON path to the loader.
     *
     * @param string $path
     *
     * @return void
     */
    public function addJsonPath($path)
    {
        $this->laravelFileLoader->addJsonPath($path);
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return $this->hints;
    }
}
