<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Loaders;

use Illuminate\Contracts\Translation\Loader as IlluminateLoader;

interface Loader extends IlluminateLoader
{
    /**
     * The default locale.
     *
     * @var string
     */
    public function __construct($defaultLocale);

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function load($locale, $group, $namespace = null);

    /**
     * Load the messages for the given locale from the loader source (cache, file, database, etc...)
     *
     * @param string $locale
     * @param string $group
     * @param string $namespace
     *
     * @return array
     */
    public function loadSource($locale, $group, $namespace = '*');

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     *
     * @return void
     */
    public function addNamespace($namespace, $hint);

    /**
     * Add a new JSON path to the loader.
     *
     * @param string $path
     *
     * @return void
     **/
    public function addJsonPath($path);

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces();
}
