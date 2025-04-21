<?php
namespace Tuna976\NEWS\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageOptimizationService
{
    /**
     * Convert an uploaded image to WebP format
     *
     * @param UploadedFile $file The uploaded file
     * @param string $path The destination path (without extension)
     * @param int $quality WebP quality (0-100)
     * @return string|null The WebP file path or null if conversion failed
     */
    public function convertToWebP(UploadedFile $file, string $path, int $quality = 80): ?string
    {
        try {
            // First check if gd or imagick extension is available
            if (!extension_loaded('gd') && !extension_loaded('imagick')) {
                Log::warning('WebP conversion not available: GD or Imagick extension required');
                return null;
            }
            
            // Create a clean path with no extension
            $pathInfo = pathinfo($path);
            $directory = $pathInfo['dirname'];
            $filename = $pathInfo['filename'];
            
            // Create the WebP file path
            $webpPath = $directory . '/' . $filename . '.webp';
            
            // Store the original file first
            $originalPath = $file->store($directory, 'public');
            
            // Use GD to convert to WebP if available
            if (extension_loaded('gd') && function_exists('imagewebp')) {
                $image = $this->createImageResource($file);
                if ($image) {
                    // Create folder if it doesn't exist
                    $fullPath = Storage::disk('public')->path($webpPath);
                    $dirPath = dirname($fullPath);
                    if (!file_exists($dirPath)) {
                        mkdir($dirPath, 0755, true);
                    }
                    
                    // Save as WebP
                    imagewebp($image, $fullPath, $quality);
                    imagedestroy($image);
                    
                    return $webpPath;
                }
            }
            
            // If we reached here, WebP conversion failed
            Log::warning('WebP conversion failed, using original format', [
                'file' => $file->getClientOriginalName(),
            ]);
            
            return $originalPath;
        } catch (\Exception $e) {
            Log::error('WebP conversion failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);
            
            return null;
        }
    }
    
    /**
     * Create an image resource from the uploaded file
     */
    private function createImageResource(UploadedFile $file)
    {
        $mime = $file->getMimeType();
        $path = $file->getPathname();
        
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            default:
                return false;
        }
    }
    
    /**
     * Get WebP URL with a fallback to the original format
     *
     * @param string|null $path The original image path
     * @return string The appropriate image URL
     */
    public static function getResponsiveImageUrl(?string $path): string
    {
        if (!$path) {
            return '';
        }
        
        // Get path info
        $pathInfo = pathinfo($path);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'] ?? '';
        
        // WebP path
        $webpPath = $directory . '/' . $filename . '.webp';
        
        // Check if WebP version exists
        if (Storage::disk('public')->exists($webpPath)) {
            return Storage::url($webpPath);
        }
        
        // Fallback to original
        return Storage::url($path);
    }
}
