<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineaEventTranslation extends Model
{
      use HasFactory;
    public $timestamps  = false;
    protected $fillable = ['title', 'content'];
}
