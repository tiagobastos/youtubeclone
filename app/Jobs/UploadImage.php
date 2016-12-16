<?php

namespace App\Jobs;

use Image;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadImage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $channel;

    public $filePath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channel $channel, $filePath)
    {
        $this->channel = $channel;
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = basename($this->filePath);

        Image::make(storage_path().'/app/'.$this->filePath)
            ->encode('png')
            ->fit(40, 40, function ($condition) {
                $condition->upsize();
            })->save();

        if (Storage::disk('local')->exists($this->filePath)) {
            Storage::disk('s3images')->putFileAs(
                'images/profile/', 
                new File(storage_path().'/app/'.$this->filePath), 
                $fileName);

            Storage::disk('local')->delete($this->filePath);

            if (Storage::disk('s3images')->exists('images/profile/'.$this->channel->image_filename)) {
                Storage::disk('s3images')->delete('images/profile/'.$this->channel->image_filename);
            }

            $this->channel->image_filename = $fileName;
            $this->channel->save();
        }
        
        // get the file
        // resize
        // upload to s3
        // delete file
        // update channel image
    }
}
