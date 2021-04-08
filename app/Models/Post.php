<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\Post\PostTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\belongsTo;

class Post extends Model
{
    use SoftDeletes;

    // soft delete colume
    protected $date = ['deleted_at'];

    // model transformers
    public $transformer = PostTransformer::class;

    // user verification status
    const VERIFIED_POST = '1';
    const UNVERIFIED_POST = '0';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'status',
        'content',
        'user_id',
    ];

    // to check if a user is verified
    public function isVerified()
    {
        return $this->status == Post::VERIFIED_POST;
    }

    // setters for title
    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = strtolower($title);
        $this->attributes['slug'] = Str::slug($title);
    }

    // getter for title
    public function getTitleAttribute($title)
    {
        return ucwords($title);
    }

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
