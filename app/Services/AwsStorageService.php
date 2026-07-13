<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AwsStorageService
{
    /**
     * Disk yang digunakan
     */
    protected string $disk = 's3';
    protected string $privateDisk = 's3-private';
    
    /**
     * Upload file ke S3
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return array
     */
    public function upload(
        UploadedFile $file,
        string $directory,
        array $options = []
    ): array {
        $disk = $options['private'] ?? false ? $this->privateDisk : $this->disk;
        $optimize = $options['optimize'] ?? true;
        
        // Generate unique filename
        $filename = $this->generateFilename($file, $options['prefix'] ?? null);
        $path = trim($directory, '/') . '/' . $filename;
        
        // Check if image and should optimize
        $isImage = $this->isImage($file);
        
        if ($isImage && $optimize) {
            $path = $this->uploadOptimizedImage($file, $path, $disk, $options);
        } else {
            // Upload file langsung
            $content = file_get_contents($file->getRealPath());
            
            $uploadOptions = [
                'visibility' => $options['private'] ?? false ? 'private' : 'public',
                'ContentType' => $file->getMimeType(),
            ];
            
            // Set Cache-Control untuk public files
            if (!($options['private'] ?? false)) {
                $uploadOptions['CacheControl'] = 'max-age=31536000, public';
            }
            
            Storage::disk($disk)->put($path, $content, $uploadOptions);
        }
        
        return [
            'disk' => $disk,
            'path' => $path,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
            'url' => $this->getUrl($path, $disk),
            'is_private' => $options['private'] ?? false,
        ];
    }
    
    /**
     * Upload gambar dengan optimasi
     */
    protected function uploadOptimizedImage(
        UploadedFile $file,
        string $path,
        string $disk,
        array $options = []
    ): string {
        $maxWidth = $options['max_width'] ?? 1920;
        $maxHeight = $options['max_height'] ?? 1080;
        $quality = $options['quality'] ?? 85;
        
        $image = Image::read($file->getRealPath());
        
        // Resize jika terlalu besar
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scale(width: $maxWidth, height: $maxHeight);
        }
        
        // Convert to WebP untuk ukuran lebih kecil
        if ($options['convert_webp'] ?? false) {
            $path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
            $encoded = $image->toWebp($quality);
            $contentType = 'image/webp';
        } else {
            $encoded = $image->toJpeg($quality);
            $contentType = 'image/jpeg';
        }
        
        // Upload ke S3
        Storage::disk($disk)->put($path, (string) $encoded, [
            'visibility' => $options['private'] ?? false ? 'private' : 'public',
            'ContentType' => $contentType,
            'CacheControl' => 'max-age=31536000, public',
        ]);
        
        return $path;
    }
    
    /**
     * Create thumbnail dan upload
     */
    public function createThumbnail(
        string $sourcePath,
        string $disk = null,
        int $width = 200,
        int $height = 200
    ): string {
        $disk = $disk ?? $this->disk;
        
        if (!Storage::disk($disk)->exists($sourcePath)) {
            throw new \Exception("Source file not found: {$sourcePath}");
        }
        
        // Download dari S3
        $content = Storage::disk($disk)->get($sourcePath);
        
        // Create thumbnail
        $image = Image::read($content);
        $image->cover($width, $height);
        
        // Generate thumbnail path
        $thumbnailPath = 'thumbnails/' . basename($sourcePath);
        
        // Upload thumbnail ke S3
        Storage::disk($disk)->put($thumbnailPath, (string) $image->toJpeg(80), [
            'visibility' => 'public',
            'ContentType' => 'image/jpeg',
            'CacheControl' => 'max-age=31536000, public',
        ]);
        
        return $thumbnailPath;
    }
    
    /**
     * Delete file dari S3
     */
    public function delete(string $path, ?string $disk = null): bool
    {
        $disk = $disk ?? $this->disk;
        
        if (!$path || !Storage::disk($disk)->exists($path)) {
            return false;
        }
        
        // Delete file
        $deleted = Storage::disk($disk)->delete($path);
        
        // Delete thumbnail jika ada
        $thumbnailPath = 'thumbnails/' . basename($path);
        if (Storage::disk($disk)->exists($thumbnailPath)) {
            Storage::disk($disk)->delete($thumbnailPath);
        }
        
        return $deleted;
    }
    
    /**
     * Get URL untuk file
     */
    public function getUrl(string $path, ?string $disk = null): string
    {
        $disk = $disk ?? $this->disk;
        
        // Jika menggunakan CloudFront
        if ($cloudFrontUrl = config('filesystems.cloudfront_url')) {
            return rtrim($cloudFrontUrl, '/') . '/' . ltrim($path, '/');
        }
        
        return Storage::disk($disk)->url($path);
    }
    
    /**
     * Get temporary URL untuk private files (valid 5 menit)
     */
    public function getTemporaryUrl(
        string $path,
        int $minutes = 5,
        ?string $disk = null
    ): string {
        $disk = $disk ?? $this->privateDisk;
        
        return Storage::disk($disk)->temporaryUrl(
            $path,
            now()->addMinutes($minutes)
        );
    }
    
    /**
     * Copy file antar storage
     */
    public function copyFromLocal(string $localPath, string $s3Path): array
    {
        if (!file_exists($localPath)) {
            throw new \Exception("Local file not found: {$localPath}");
        }
        
        $content = file_get_contents($localPath);
        $mimeType = mime_content_type($localPath);
        
        Storage::disk($this->disk)->put($s3Path, $content, [
            'visibility' => 'public',
            'ContentType' => $mimeType,
            'CacheControl' => 'max-age=31536000, public',
        ]);
        
        return [
            'path' => $s3Path,
            'url' => $this->getUrl($s3Path),
            'size' => Storage::disk($this->disk)->size($s3Path),
        ];
    }
    
    /**
     * Migrate existing files dari local ke S3
     */
    public function migrateFromLocal(string $localDisk = 'public'): array
    {
        $migrated = [];
        $failed = [];
        
        $files = Storage::disk($localDisk)->allFiles();
        
        foreach ($files as $file) {
            try {
                $localPath = Storage::disk($localDisk)->path($file);
                
                if (!file_exists($localPath)) {
                    $failed[] = $file;
                    continue;
                }
                
                $content = Storage::disk($localDisk)->get($file);
                $mimeType = Storage::disk($localDisk)->mimeType($file);
                
                // Upload ke S3
                Storage::disk($this->disk)->put($file, $content, [
                    'visibility' => 'public',
                    'ContentType' => $mimeType,
                    'CacheControl' => 'max-age=31536000, public',
                ]);
                
                $migrated[] = [
                    'path' => $file,
                    'url' => $this->getUrl($file),
                    'size' => Storage::disk($this->disk)->size($file),
                ];
                
            } catch (\Exception $e) {
                $failed[] = [
                    'path' => $file,
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        return [
            'migrated' => $migrated,
            'failed' => $failed,
            'total' => count($files),
            'success' => count($migrated),
            'errors' => count($failed),
        ];
    }
    
    /**
     * Check if file is image
     */
    protected function isImage(UploadedFile $file): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        return in_array(
            strtolower($file->getClientOriginalExtension()),
            $imageExtensions
        );
    }
    
    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file, ?string $prefix = null): string
    {
        $extension = $file->getClientOriginalExtension();
        $hash = Str::random(32);
        $timestamp = time();
        
        return ($prefix ? $prefix . '_' : '') . "{$timestamp}_{$hash}.{$extension}";
    }
    
    /**
     * Get S3 bucket statistics
     */
    public function getStatistics(): array
    {
        $directories = ['dokumen', 'temuan', 'foto-user', 'settings', 'bukti-tindak-lanjut'];
        $stats = [];
        $totalSize = 0;
        $totalFiles = 0;
        
        foreach ($directories as $dir) {
            $files = Storage::disk($this->disk)->allFiles($dir);
            $dirSize = 0;
            
            foreach ($files as $file) {
                $dirSize += Storage::disk($this->disk)->size($file);
            }
            
            $stats[$dir] = [
                'count' => count($files),
                'size' => $dirSize,
                'size_human' => $this->formatBytes($dirSize),
            ];
            
            $totalSize += $dirSize;
            $totalFiles += count($files);
        }
        
        return [
            'directories' => $stats,
            'total_files' => $totalFiles,
            'total_size' => $totalSize,
            'total_size_human' => $this->formatBytes($totalSize),
            'bucket' => config('filesystems.disks.s3.bucket'),
            'region' => config('filesystems.disks.s3.region'),
        ];
    }
    
    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
