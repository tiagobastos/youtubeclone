<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Jobs\UploadImage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ChannelUpdateRequest;

class ChannelSettingsController extends Controller
{
    public function __construct()
    {
        //  Defining auth middleware for specific routes in channel  
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        // TODO
    }

    public function edit(Channel $channel)
    {
        $this->authorize('edit', $channel);

        return view('channel.settings.edit', [
            'channel' => $channel
        ]);
    }

    public function update(ChannelUpdateRequest $request, Channel $channel)
    {
        $this->authorize('update', $channel);
        
        $channel->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
        ]);

        if ($request->file('image')) {
            $filePath = $request->file('image')->store('uploads', 'local');
            
            $this->dispatch(new UploadImage($channel, $filePath));
        }

        return redirect()->to("/channel/{$channel->slug}/edit");
    }
}
