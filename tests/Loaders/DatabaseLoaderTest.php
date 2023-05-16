<?php

namespace Waavi\Translation\Test\Loaders;

use Illuminate\Support\Facades\App;
use Mockery;
use Waavi\Translation\Loaders\DatabaseLoader;
use Waavi\Translation\Repositories\TranslationRepository;
use Waavi\Translation\Test\TestCase;

class DatabaseLoaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->translationRepository = App::make(TranslationRepository::class);
        $this->loader = new DatabaseLoader('es', $this->translationRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_returns_from_database()
    {
        $expected = [
            'simple' => 'text',
            'array' => [
                'item' => 'item',
                'nested' => [
                    'item' => 'nested',
                ],
            ],
        ];
        $translation = $this->translationRepository->create([
            'locale' => 'es',
            'namespace' => '*',
            'group' => 'group',
            'item' => 'simple',
            'text' => 'text',
        ]);
        $translation = $this->translationRepository->create([
            'locale' => 'es',
            'namespace' => '*',
            'group' => 'group',
            'item' => 'array.item',
            'text' => 'item',
        ]);
        $translation = $this->translationRepository->create([
            'locale' => 'es',
            'namespace' => '*',
            'group' => 'group',
            'item' => 'array.nested.item',
            'text' => 'nested',
        ]);
        $translations = $this->loader->loadSource('es', 'group');
        $this->assertEquals($expected, $translations);
    }
}
