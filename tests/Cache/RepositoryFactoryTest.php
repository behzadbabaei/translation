<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Cache;

use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\FileStore;
use Behzadbabaei\Translation\Cache\RepositoryFactory;
use Behzadbabaei\Translation\Cache\SimpleRepository;
use Behzadbabaei\Translation\Cache\TaggedRepository;
use Behzadbabaei\Translation\Test\TestCase;
use Illuminate\Support\Facades\App;

class RepositoryFactoryTest extends TestCase
{
    public function setUp() : void
    {
        // During the parent's setup, both a 'es' 'Spanish' and 'en' 'English' languages are inserted into the database.
        parent::setUp();
    }

    /**
     * @test
     */
    public function test_returns_simple_cache_if_non_taggable_store()
    {
        $fileStore = new FileStore(App::make('files'), __DIR__.'/temp');
        $repo = RepositoryFactory::make($fileStore, 'translation');
        $this->assertEquals(SimpleRepository::class, get_class($repo));
    }

    /**
     * @test
     */
    public function test_returns_simple_cache_if_taggable_store()
    {
        $arrayStore = new ArrayStore;
        $repo = RepositoryFactory::make($arrayStore, 'translation');
        $this->assertEquals(TaggedRepository::class, get_class($repo));
    }
}
