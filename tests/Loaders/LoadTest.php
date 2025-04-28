<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Loaders;

use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Behzadbabaei\Translation\Loaders\FileLoader;
use Behzadbabaei\Translation\Loaders\DatabaseLoader;
use Behzadbabaei\Translation\Loaders\MixedLoader;
use Behzadbabaei\Translation\Test\TestCase;
use \Mockery;

class LoadTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->laravelLoader = Mockery::mock(LaravelFileLoader::class);
        $this->fileLoader = new FileLoader('en', $this->laravelLoader);
        $this->dbLoader = Mockery::mock(DatabaseLoader::class);
        $this->mixedLoader = new MixedLoader('en', $this->fileLoader, $this->dbLoader);
    }

    public function tearDown() : void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_merges_default_and_target_locales()
    {
        $en = [
            'simple' => 'Simple',
            'nested' => [
                'one' => 'First',
                'two' => 'Second',
            ],
        ];
        $es = [
            'simple' => 'OverSimple',
            'nested' => [
                'one' => 'OverFirst',
            ],
        ];
        $expected = [
            'simple' => 'OverSimple',
            'nested' => [
                'one' => 'OverFirst',
                'two' => 'Second',
            ],
        ];
        $this->laravelLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($en);
        $this->laravelLoader->shouldReceive('load')->with('es', 'group', 'name')->andReturn($es);
        $this->dbLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn([]);
        $this->dbLoader->shouldReceive('load')->with('es', 'group', 'name')->andReturn([]);
        $result = $this->mixedLoader->load('es', 'group', 'name');
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function it_merges_file_and_database_translations()
    {
        $fileTranslations = [
            'simple' => 'File',
            'nested' => [
                'one' => 'FileOne',
                'two' => 'FileTwo',
            ],
        ];
        $dbTranslations = [
            'simple' => 'DB',
            'nested' => [
                'one' => 'DBOne',
            ],
            'db_only' => 'DBOnly',
        ];
        $expected = [
            'simple' => 'File',
            'nested' => [
                'one' => 'FileOne',
                'two' => 'FileTwo',
            ],
            'db_only' => 'DBOnly',
        ];
        $this->laravelLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($fileTranslations);
        $this->dbLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($dbTranslations);
        $result = $this->mixedLoader->load('en', 'group', 'name');
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function it_returns_translation_code_if_text_not_found()
    {
        $this->laravelLoader->shouldReceive('load')->with('en', 'auth', '*')->andReturn([]);
        $this->dbLoader->shouldReceive('load')->with('en', 'auth', '*')->andReturn([]);
        $this->assertEquals('auth.code', trans('auth.code'));
    }
}
