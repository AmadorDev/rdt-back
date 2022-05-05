<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;


class ProductVideo extends Model implements TranslatableContract
{
   use HasFactory;
    use Sluggable;
    use Translatable;

    public $translatedAttributes = ['title', 'content'];

    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }
    public function scopeFilter($query, array $filters)
    {
        $query->whereTranslationLike('title', '%' . $filters['search'] . '%');
        $query->orWhereTranslationLike('content', '%' . $filters['search'] . '%');

    }
}
