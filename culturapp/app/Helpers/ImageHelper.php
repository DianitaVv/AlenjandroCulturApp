<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    public static function uploadImage(UploadedFile $file, string $folder = 'images', int $maxWidth = 1200): string
    {
        // Generar nombre único
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;
        
        try {
            // Intentar redimensionar con GD si está disponible
            if (extension_loaded('gd') && in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $resizedImage = self::resizeImageWithGD($file, $maxWidth);
                if ($resizedImage) {
                    Storage::disk('public')->put($path, $resizedImage);
                    return $path;
                }
            }
        } catch (\Exception $e) {
            // Si falla, continuar con el guardado directo
        }
        
        // Guardar directamente sin redimensionar
        Storage::disk('public')->putFileAs($folder, $file, $filename);
        
        return $path;
    }
    
    private static function resizeImageWithGD(UploadedFile $file, int $maxWidth): ?string
    {
        try {
            $extension = strtolower($file->getClientOriginalExtension());
            
            // Crear imagen desde archivo
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($file->getPathname());
                    break;
                case 'png':
                    $image = imagecreatefrompng($file->getPathname());
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file->getPathname());
                    break;
                default:
                    return null;
            }
            
            if (!$image) return null;
            
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            
            // Solo redimensionar si es necesario
            if ($originalWidth <= $maxWidth) {
                ob_start();
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, null, 90);
                        break;
                    case 'png':
                        imagepng($image);
                        break;
                    case 'gif':
                        imagegif($image);
                        break;
                }
                $result = ob_get_contents();
                ob_end_clean();
                imagedestroy($image);
                return $result;
            }
            
            // Calcular nuevas dimensiones
            $newWidth = $maxWidth;
            $newHeight = ($originalHeight * $maxWidth) / $originalWidth;
            
            // Crear nueva imagen redimensionada
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preservar transparencia para PNG
            if ($extension === 'png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefill($newImage, 0, 0, $transparent);
            }
            
            // Redimensionar
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Generar output
            ob_start();
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($newImage, null, 90);
                    break;
                case 'png':
                    imagepng($newImage);
                    break;
                case 'gif':
                    imagegif($newImage);
                    break;
            }
            $result = ob_get_contents();
            ob_end_clean();
            
            // Limpiar memoria
            imagedestroy($image);
            imagedestroy($newImage);
            
            return $result;
            
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public static function deleteImage(?string $path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}