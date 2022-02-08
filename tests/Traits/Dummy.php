<?php

declare(strict_types = 1);

namespace Waavi\Translation\Test\Traits;

use Waavi\Translation\Traits\Translatable;

class Dummy extends Model
{
    use Translatable;

    /**
     * @var array
     */
    protected $fillable = ['title', 'text'];

    /**
     * @var array
     */
    protected $translatableAttributes = ['title', 'text'];

    /**
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = 'slug';
    }
}
