<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Loaders;

use Behzadbabaei\Translation\Cache\CacheRepositoryInterface as Cache;
use Illuminate\Contracts\Translation\Loader;

class CacheLoader implements Loader
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Loader
     */
    protected $loader;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @param string $defaultLocale
     * @param Cache $cache
     * @param Loader $loader
     * @param int $timeout
     * @param string $suffix
     */
    public function __construct(string $defaultLocale, Cache $cache, Loader $loader, int $timeout, string $suffix)
    {
        $this->defaultLocale = $defaultLocale;
        $this->cache = $cache;
        $this->loader = $loader;
        $this->timeout = $timeout;
        $this->suffix = $suffix;
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
        if ($this->cache->has($locale, $namespace, $group)) {
            return $this->cache->get($locale, $namespace, $group);
        }

        $translations = $this->loader->load($locale, $group, $namespace);
        $this->cache->put($locale, $namespace, $group, $translations, $this->timeout);

        return $translations;
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
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return $this->loader->namespaces();
    }
}
