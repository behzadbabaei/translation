<?php

declare(strict_types = 1);

namespace Behzadbabaei\Translation\Test\Traits;

use Illuminate\Database\Eloquent\Model;
use Behzadbabaei\Translation\Traits\Translatable;

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
