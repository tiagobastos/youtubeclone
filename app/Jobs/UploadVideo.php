<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UploadVideo implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video, $filePath)
    {
        $this->filePath = $filePath;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = basename($this->filePath);

        if (Storage::disk('local')->exists($this->filePath)) {
            Storage::disk('s3drop')->putFileAs(
                'drop/', 
                new File(storage_path().'/app/'.$this->filePath), 
                $fileName);
            Storage::disk('local')->delete($this->filePath);
        }
    }
}
