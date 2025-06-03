
namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageOptimizer
{
    public function optimize($image, $path, $quality = 80)
    {
        $img = Image::make($image);

        // Resize if width is greater than 1200px
        if ($img->width() > 1200) {
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convert to WebP format
        $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $path);
        
        // Save WebP version
        $img->encode('webp', $quality)->save(storage_path('app/public/' . $webpPath));

        // Save original format with optimization
        $img->save(storage_path('app/public/' . $path), $quality);

        return [
            'original' => $path,
            'webp' => $webpPath,
            'width' => $img->width(),
            'height' => $img->height(),
        ];
    }

    public function generateThumbnail($image, $path, $width = 300)
    {
        $img = Image::make($image);
        
        // Create thumbnail name
        $thumbPath = preg_replace('/(\.[^.]+)$/', '-thumb$1', $path);
        
        // Resize to thumbnail size
        $img->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Save thumbnail
        $img->save(storage_path('app/public/' . $thumbPath), 70);

        return $thumbPath;
    }

    public function deleteImages($paths)
    {
        foreach ((array)$paths as $path) {
            // Delete original
            Storage::disk('public')->delete($path);
            
            // Delete WebP version if exists
            $webpPath = preg_replace('/\.(jpg|jpeg|png|gif)$/i', '.webp', $path);
            Storage::disk('public')->delete($webpPath);
            
            // Delete thumbnail if exists
            $thumbPath = preg_replace('/(\.[^.]+)$/', '-thumb$1', $path);
            Storage::disk('public')->delete($thumbPath);
        }
    }
} 
<?php
 