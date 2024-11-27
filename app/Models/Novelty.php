<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Novelty extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Sluggable;
    public $translatedAttributes = ['title', 'content'];
    protected $guarded           = [];

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
