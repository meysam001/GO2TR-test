<?php

namespace App\Jobs;

use App\Models\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\ImageManagerStatic as Image;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $photoModel;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Photo $photoModel)
    {
        $this->photoModel = $photoModel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ext = explode('.', $this->photoModel->path)[1];
        $filename = basename($this->photoModel->path, ".$ext");
        $filename = $filename.config('filesystems.thumbSuffix').$ext;
        $image_resize = Image::make(storage_path('app').'/'.$this->photoModel->path);
        $image_resize->resize(intval($this->photoModel->width / 2), intval($this->photoModel->height / 2));
        $image_resize->save(storage_path('app/public').'/'.$filename);
    }
}
