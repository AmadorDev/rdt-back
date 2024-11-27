<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;

class Linea extends Model implements TranslatableContract
{
    use HasFactory;
    use Sluggable;
    use Translatable;

    public $translatedAttributes = ['name', 'description', "short_name"];

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

    public function product(){
        return $this->belongsTo(Product::class);
        
    }
 
}
