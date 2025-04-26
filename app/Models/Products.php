<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class Products extends Model
{

    use HasFactory;
    use HasSlug;


    protected $fillable = ['name', 'image', 'description', 'price', 'created_by', 'updated_by'];

    /** Get the option for generating the slug */

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
