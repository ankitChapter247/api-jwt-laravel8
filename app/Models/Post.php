<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'category_id',
        'shortDesc',
        'description',
        'image',
        'thumbnail',
        'is_fetured',
        'created_at',
        'updated_at',
        'post_slug'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
