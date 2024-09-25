<?php

namespace App\Models;

use App\Models\Linea;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use Sluggable;
    public $translatedAttributes = ['name', 'description'];
    protected $guarded = [];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->whereTranslationLike('name', '%' . $filters['search'] . '%');
        $query->orWhereTranslationLike('description', '%' . $filters['search'] . '%');

    }

    // public function linea()
    // {
    //     return $this->belongsTo(Linea::class);

    // }

    public function line(){
        return $this->belongsTo(Linea::class);
        
    }

}
