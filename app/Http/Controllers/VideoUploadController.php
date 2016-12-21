<?php

namespace App\Http\Controllers;

use App\Jobs\UploadVideo;
use Illuminate\Http\Request;

class VideoUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('video.upload');
    }

    public function store(Request $request)
    {
        $channel = $request->user()->channel()->first();

        $video = $channel->videos()->where('uid', $request->uid)->firstOrFail();

        if ($request->file('video')) {
            $filePath = $request->file('video')->storeAs('uploads', $video->video_filename, 'local');
            
            $this->dispatch(new UploadVideo($video, $filePath));
        }

        return response()->json(null, 200);
    }
}
