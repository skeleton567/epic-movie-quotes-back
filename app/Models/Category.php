<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $guarded =['id'];
    public $translatable = ['category'];
    public function movies(): BelongsToMany
    {
        return  $this->belongsToMany(Movie::class);
    }
}
