<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function config;

class Language extends Model
{
    use SoftDeletes;

    /**
     *  Table name in the database.
     *
     * @var string
     */
    protected $table = 'translator_languages';

    /**
     *  List of variables that cannot be mass assigned
     *
     * @var array
     */
    protected $fillable = ['locale', 'name'];

    /**
     * Language constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('translator.connection'));
    }

    /**
     *  Each language may have several translations.
     */
    public function translations()
    {
        return $this->hasMany(Translation::class, 'locale', 'locale');
    }

    /**
     *  Returns the name of this language in the current selected language.
     *
     * @return string
     */
    public function getLanguageCodeAttribute()
    {
        return "languages.{$this->locale}";
    }
}
