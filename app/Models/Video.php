<?php

namespace App\Models;

use App\Models\Channel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    // Soft deletes trait;
    use SoftDeletes;

    protected $fillable = [
        'uid',
        'title',
        'description',

        'video_id',
        'video_filename',

        'processed',
        'visibility',

        'allow_votes',
        'allow_comments',
        'processed_percentage',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function getRouteKeyName()
    {
        return 'uid';   
    }
}
