<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Info extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;

    public $translatedAttributes = ['content'];
    protected $guarded = [];

    public function scopeFilter($query, array $filters)
    {
        $query->orWhereTranslationLike('content', '%' . $filters['q'] . '%');
    }
}
