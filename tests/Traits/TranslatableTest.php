<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Mockery;
use Behzadbabaei\Translation\Repositories\LanguageRepository;
use Behzadbabaei\Translation\Repositories\TranslationRepository;
use Behzadbabaei\Translation\Test\TestCase;

class TranslatableTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        Schema::create('dummies', function ($table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('title_translation')->nullable();
            $table->string('slug')->nullable();
            $table->string('text')->nullable();
            $table->string('text_translation')->nullable();
            $table->timestamps();
        });
        $this->languageRepository = App::make(LanguageRepository::class);
        $this->translationRepository = App::make(TranslationRepository::class);
    }

    /**
     * @test
     */
    public function it_saves_translations()
    {
        $dummy = new Dummy;
        $dummy->title = 'Dummy title';
        $dummy->text = 'Dummy text';
        $saved = $dummy->save() ? true : false;
        $this->assertTrue($saved);
        $this->assertEquals(1, Dummy::count());
        $this->assertEquals('slug', $dummy->slug);
        // Check that there is a language entry in the database:
        $titleTranslation = $this->translationRepository->findByLangCode('en', $dummy->translationCodeFor('title'));
        $this->assertEquals('Dummy title', $titleTranslation->text);
        $this->assertEquals('Dummy title', $dummy->title);
        $textTranslation = $this->translationRepository->findByLangCode('en', $dummy->translationCodeFor('text'));
        $this->assertEquals('Dummy text', $textTranslation->text);
        $this->assertEquals('Dummy text', $dummy->text);
        // Delete it:
        $deleted = $dummy->delete();
        $this->assertTrue($deleted);
        $this->assertEquals(0, Dummy::count());
        $this->assertEquals(0, $this->translationRepository->count());
    }

    /**
     * @test
     */
    public function it_flushes_cache()
    {
        $cacheMock = Mockery::mock(\Behzadbabaei\Translation\Cache\SimpleRepository::class);
        $this->app->bind('translation.cache.repository', function ($app) use ($cacheMock) {
            return $cacheMock;
        });
        $cacheMock->shouldReceive('flush')->with('en', 'translatable', '*');
        $dummy = new Dummy;
        $dummy->title = 'Dummy title';
        $dummy->text = 'Dummy text';
        $saved = $dummy->save() ? true : false;
        $this->assertTrue($saved);
    }

    /**
     * @test
     */
    public function to_array_features_translated_attributes()
    {
        $dummy = Dummy::create(['title' => 'Dummy title', 'text' => 'Dummy text']);
        $this->assertEquals(1, Dummy::count());
        // Change the text on the translation object:
        $titleTranslation = $this->translationRepository->findByLangCode('en', $dummy->translationCodeFor('title'));
        $titleTranslation->text = 'Translated text';
        $titleTranslation->save();
        // Verify that toArray pulls from the translation and not model's value, and that the _translation attributes are hidden
        $this->assertEquals(['title' => 'Translated text', 'text' => 'Dummy text'],
            $dummy->makeHidden(['created_at', 'updated_at', 'slug', 'id'])->toArray());
    }

    protected static function resolvePhpUnitAttributesForMethod(string $className, ?string $methodName = null): Collection
    {
        return new Collection();
    }
}


