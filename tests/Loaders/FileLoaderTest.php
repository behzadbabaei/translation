<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Loaders;

use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Behzadbabaei\Translation\Loaders\FileLoader;
use Behzadbabaei\Translation\Test\TestCase;
use \Mockery;

class FileLoaderTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->laravelLoader = Mockery::mock(LaravelFileLoader::class);
        $this->fileLoader = new FileLoader('en', $this->laravelLoader);
    }

    public function tearDown() : void
    {
        Mockery::close();

        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_from_file()
    {
        $data = [
            'simple' => 'Simple',
            'nested' => [
                'one' => 'First',
                'two' => 'Second',
            ],
        ];
        $this->laravelLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($data);
        $this->assertEquals($data, $this->fileLoader->loadSource('en', 'group', 'name'));
    }
}
