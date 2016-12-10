<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_filename'
    ];

    public function user()
    {
        return $this->belognsTo(User::class);
    }
}
