<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Loaders;

use Illuminate\Contracts\Translation\Loader;

class MixedLoader implements Loader
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var Loader
     */
    protected $primaryLoader;

    /**
     * @var Loader
     */
    protected $secondaryLoader;

    /**
     * @param string $defaultLocale
     * @param Loader $primaryLoader
     * @param Loader $secondaryLoader
     */
    public function __construct(string $defaultLocale, Loader $primaryLoader, Loader $secondaryLoader)
    {
        $this->defaultLocale = $defaultLocale;
        $this->primaryLoader = $primaryLoader;
        $this->secondaryLoader = $secondaryLoader;
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
        $primary = $this->primaryLoader->load($locale, $group, $namespace);
        $secondary = $this->secondaryLoader->load($locale, $group, $namespace);

        return array_replace_recursive($secondary, $primary);
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
        $this->primaryLoader->addNamespace($namespace, $hint);
        $this->secondaryLoader->addNamespace($namespace, $hint);
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
