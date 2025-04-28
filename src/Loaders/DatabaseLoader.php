<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Loaders;

use Behzadbabaei\Translation\Repositories\TranslationRepository;
use Illuminate\Contracts\Translation\Loader;

class DatabaseLoader implements Loader
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var TranslationRepository
     */
    protected $translationRepository;

    /**
     * @param string $defaultLocale
     * @param TranslationRepository $translationRepository
     */
    public function __construct(string $defaultLocale, TranslationRepository $translationRepository)
    {
        $this->defaultLocale = $defaultLocale;
        $this->translationRepository = $translationRepository;
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
        $translations = $this->translationRepository->getItems($locale, $namespace, $group);
        $result = [];

        foreach ($translations as $translation) {
            $this->setTranslation($result, $translation['item'], $translation['text']);
        }

        return $result;
    }

    /**
     * Set a translation in the results array.
     *
     * @param array $result
     * @param string $key
     * @param string $value
     * @return void
     */
    protected function setTranslation(array &$result, string $key, string $value)
    {
        $keys = explode('.', $key);
        $current = &$result;

        foreach ($keys as $k) {
            if (!isset($current[$k])) {
                $current[$k] = [];
            }
            $current = &$current[$k];
        }

        $current = $value;
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
        // Not needed for database loader
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
