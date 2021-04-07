<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use App\Transformers\User\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    // soft delete  
    protected $date = ['deleted_at'];

    // model transformers
    public $transformer = UserTransformer::class;

    // admin status
    const ADMIN_USER = true;
    const REGULAR_USER = false;

    // user verification status
    const VERIFIED_USER = '1';
    const UNVERIFIED_USER = '0';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // to check if a user is admin
    public function isAdmin()
    {
        return $this->admin == User::ADMIN_USER;
    }

    // to check if a user is verified
    public function isVerified()
    {
        return $this->verified == User::VERIFIED_USER;
    }

    // to generate code for verification
    public static function generateVerificationCode()
    {
        return Str::random(45);
    }

    // setters for name
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower($name);
    }

    // getter for name
    public function getNameAttribute($name)
    {
        return ucwords($name);
    }

    // setters for email
    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    // getter for name
    public function getEmailAttribute($email)
    {
        return ucwords($email);
    }

    // post
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
