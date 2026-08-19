<?php

use App\Models\Candidate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

return new class extends Migration
{
    public function up(): void
    {
        $targetDiskName = config('app.resume_disk', 'private');

        Media::query()
            ->where('model_type', Candidate::class)
            ->where('collection_name', Candidate::RESUME_PATH)
            ->where('disk', '!=', $targetDiskName)
            ->eachById(function (Media $media) use ($targetDiskName) {
                $sourceDisk = Storage::disk($media->disk);
                $targetDisk = Storage::disk($targetDiskName);
                $mediaDirectory = dirname($media->getPathRelativeToRoot());

                foreach ($sourceDisk->allFiles($mediaDirectory) as $sourcePath) {
                    $stream = $sourceDisk->readStream($sourcePath);
                    if ($stream === false) {
                        throw new RuntimeException("Unable to read candidate resume file: {$sourcePath}");
                    }

                    try {
                        $targetDisk->writeStream($sourcePath, $stream);
                    } finally {
                        if (is_resource($stream)) {
                            fclose($stream);
                        }
                    }
                }

                $media->forceFill([
                    'disk' => $targetDiskName,
                    'conversions_disk' => $targetDiskName,
                ])->save();

                $sourceDisk->deleteDirectory($mediaDirectory);
            });
    }

    public function down(): void
    {
        // Resumes intentionally remain private when rolling application code back.
    }
};
