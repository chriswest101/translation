<?php

namespace Waavi\Translation\Test\Loaders;

use Illuminate\Translation\FileLoader as LaravelFileLoader;
use Mockery;
use Waavi\Translation\Loaders\FileLoader;
use Waavi\Translation\Test\TestCase;

class FileLoaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->laravelLoader = Mockery::mock(LaravelFileLoader::class);
        $this->fileLoader = new FileLoader('en', $this->laravelLoader);
    }

    protected function tearDown(): void
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
