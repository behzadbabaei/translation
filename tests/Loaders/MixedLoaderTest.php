<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Loaders;

use Behzadbabaei\Translation\Loaders\DatabaseLoader;
use Behzadbabaei\Translation\Loaders\FileLoader;
use Behzadbabaei\Translation\Loaders\MixedLoader;
use Behzadbabaei\Translation\Test\TestCase;
use \Mockery;

class MixedLoaderTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->fileLoader = Mockery::mock(FileLoader::class);
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
    public function it_merges_file_and_db()
    {
        $file = [
            'in.file' => 'File',
            'no.db'   => 'No database',
        ];
        $db = [
            'in.file' => 'Database',
            'no.file' => 'No file',
        ];
        $expected = [
            'in.file' => 'File',
            'no.db'   => 'No database',
            'no.file' => 'No file',
        ];
        $this->fileLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($file);
        $this->dbLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($db);
        $this->assertEquals($expected, $this->mixedLoader->load('en', 'group', 'name'));
    }

    /**
     * @test
     */
    public function it_cascades_namespaces()
    {
        $this->fileLoader->shouldReceive('addNamespace')->with('package', '/some/path/to/package')->andReturnNull();
        $this->dbLoader->shouldReceive('addNamespace')->with('package', '/some/path/to/package')->andReturnNull();
        $this->assertNull($this->mixedLoader->addNamespace('package', '/some/path/to/package'));
    }

    /**
     * @test
     */
    public function it_merges_nested_arrays()
    {
        $file = [
            'simple' => 'File',
            'nested' => [
                'one' => 'FileOne',
                'two' => 'FileTwo',
                'three' => [
                    'a' => 'FileA',
                    'b' => 'FileB',
                ],
            ],
        ];
        $db = [
            'simple' => 'DB',
            'nested' => [
                'one' => 'DBOne',
                'three' => [
                    'a' => 'DBA',
                    'c' => 'DBC',
                ],
            ],
            'db_only' => 'DBOnly',
        ];
        $expected = [
            'simple' => 'File',
            'nested' => [
                'one' => 'FileOne',
                'two' => 'FileTwo',
                'three' => [
                    'a' => 'FileA',
                    'b' => 'FileB',
                    'c' => 'DBC',
                ],
            ],
            'db_only' => 'DBOnly',
        ];
        $this->fileLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($file);
        $this->dbLoader->shouldReceive('load')->with('en', 'group', 'name')->andReturn($db);
        $this->assertEquals($expected, $this->mixedLoader->load('en', 'group', 'name'));
    }
}
