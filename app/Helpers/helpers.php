<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

if (!function_exists('storage_url')) {
    /**
     * Get the URL for a stored file, fallback to local storage if it exists there.
     *
     * @param string|null $path
     * @return string
     */
    function storage_url($path)
    {
        if (!$path) {
            return '';
        }

        // If it's already a full URL, return it directly
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Clean path from leading slash or 'storage/' prefix
        $clean = ltrim($path, '/');
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }

        // Check if file exists in the local public disk fallback
        try {
            if (Storage::disk('local_public')->exists($clean)) {
                return asset('storage/' . $clean);
            }
        } catch (\Exception $e) {
            Log::debug('local_public check failed: ' . $e->getMessage());
        }

        // Otherwise, resolve via the 'public' disk (which is configured for Cloudinary)
        try {
            $url = Storage::disk('public')->url($clean);
            if (str_contains($url, 'res.cloudinary.com')) {
                $parts = explode('?', $url, 2);
                $pathPart = $parts[0];
                $queryPart = isset($parts[1]) ? '?' . $parts[1] : '';

                $ext = pathinfo($clean, PATHINFO_EXTENSION);
                if ($ext && !str_ends_with($pathPart, '.' . $ext . '.' . $ext)) {
                    $pathPart .= '.' . $ext;
                }
                return $pathPart . $queryPart;
            }
            return $url;
        } catch (\Exception $e) {
            Log::warning('Cloudinary URL generation failed for path: ' . $clean . '. Error: ' . $e->getMessage());
            return asset('storage/' . $clean);
        }
    }
}
