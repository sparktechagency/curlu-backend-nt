<?php

namespace App\Services;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    private string $disk = 'public_path';  // 'public_path', 'public', 's3', 'local'
    private string $path = '';
    private array $defaultFiles = [];

    public function __construct(?string $disk = null, ?string $path = null, array|string $defaultFiles = [])
    {
        if ($disk !== null) {
            $this->disk = $disk;
        }

        if ($path !== null) {
            $this->path = trim($path, '/');
        }

        if (!empty($defaultFiles)) {
            $this->defaultFiles = is_array($defaultFiles) ? $defaultFiles : [$defaultFiles];
        }
    }

    public function setDisk(string $disk): static
    {
        $this->disk = $disk;
        return $this;
    }

    public function getDisk(): string
    {
        return $this->disk;
    }

    public function setPath(string $path): static
    {
        $this->path = trim($path, '/');
        return $this;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setDefaultFiles(array|string $files): static
    {
        $this->defaultFiles = is_array($files) ? $files : [$files];
        return $this;
    }

    public function getDefaultFiles(): array
    {
        return $this->defaultFiles;
    }

    protected function generateUniqueFilename(): string
    {
        return time() . bin2hex(random_bytes(6));
    }

    protected function ensureDirectoryExists(): void
    {
        if ($this->disk === 'public_path') {
            $fullPath = public_path($this->path);
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0777, true);
            }
            return;
        }

        if ($this->disk === 's3') {
            return;
        }

        if (!Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->makeDirectory($this->path);
        }
    }

    public function generateUserAvatar(string $name, ?string $oldAvatar = null): string
    {
        $this->ensureDirectoryExists();

        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random&bold=true&size=256';
        $response = Http::get($avatarUrl);

        $filename = $this->generateUniqueFilename() . '.png';
        if ($oldAvatar) {
            $this->deleteFile($oldAvatar);
        }
        if ($this->disk === 'public_path') {
            file_put_contents(public_path($this->path . '/' . $filename), $response->body());
        } else {
            $temp = tempnam(sys_get_temp_dir(), 'avatar_');
            file_put_contents($temp, $response->body());
            Storage::disk($this->disk)->putFileAs($this->path, new File($temp), $filename);
            unlink($temp);
        }
        return $this->path ? rtrim($this->path, '/') . '/' . $filename : $filename;
    }

    public function saveFile(UploadedFile $file): string
    {
        $this->ensureDirectoryExists();

        $filename = $this->generateUniqueFilename() . '.' . $file->getClientOriginalExtension();

        if ($this->disk === 'public_path') {
            $file->move(public_path($this->path), $filename);
        } else {
            Storage::disk($this->disk)->putFileAs($this->path, $file, $filename);
        }

        return $this->path ? rtrim($this->path, '/') . '/' . $filename : $filename;
    }

    public function saveOptimizedImage(UploadedFile $file, ?int $quality = null, ?int $resizeWidth = null, ?int $resizeHeight = null, bool $convertToWebp = false): string
    {
        $this->ensureDirectoryExists();

        $originalExtension = strtolower($file->getClientOriginalExtension());
        $extension = $convertToWebp ? 'webp' : $originalExtension;

        $quality = $quality ?? 40;
        $quality = max(0, min(100, $quality));

        $filename = $this->generateUniqueFilename() . '.' . $extension;

        $manager = new ImageManager(['driver' => 'gd']);
        $image = $manager->make($file)->orientate();

        if ($resizeWidth && $resizeHeight) {
            $image->fit($resizeWidth, $resizeHeight, function ($constraint) {
                $constraint->upsize();
            });
        } elseif ($resizeWidth && $image->width() > $resizeWidth) {
            $image->resize($resizeWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } elseif ($resizeHeight && $image->height() > $resizeHeight) {
            $image->resize(null, $resizeHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        if ($this->disk === 'public_path') {
            $image->save(public_path($this->path . '/' . $filename), $quality, $extension);
        } else {
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            $image->save($tempPath, $quality, $extension);
            Storage::disk($this->disk)->putFileAs($this->path, new File($tempPath), $filename);
            unlink($tempPath);
        }

        return $this->path ? rtrim($this->path, '/') . '/' . $filename : $filename;
    }

    public function updateFile(UploadedFile $file, ?string $existingFile = null): string
    {
        if ($existingFile) {
            $this->deleteFile($existingFile);
        }
        return $this->saveFile($file);
    }

    public function updateOptimizedImage(UploadedFile $file, ?string $existingFile = null, ?int $quality = 40, ?int $resizeWidth = null, ?int $resizeHeight = null, bool $convertToWebp = false): string
    {
        if ($existingFile) {
            $this->deleteFile($existingFile);
        }
        return $this->saveOptimizedImage($file, $quality, $resizeWidth, $resizeHeight, $convertToWebp);
    }

    public function deleteFile(string $filePath): bool
    {
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            $filePath = parse_url($filePath, PHP_URL_PATH);
        }

        $filePath = preg_replace('#^/storage/#', '', $filePath);

        $relativePath = ltrim(str_replace('\\', '/', $filePath), '/');
        $fileName = basename($relativePath);

        if (in_array($fileName, $this->defaultFiles)) {
            return false;
        }

        if ($this->disk === 'public_path') {
            $fullPath = public_path($relativePath);
            if (file_exists($fullPath)) {
                return unlink($fullPath);
            }
            return false;
        }

        if (Storage::disk($this->disk)->exists($relativePath)) {
            return Storage::disk($this->disk)->delete($relativePath);
        }

        return false;
    }

    public function saveMultipleFiles(array $files, bool $optimize = false, int $quality = null, ?int $resizeWidth = null, ?int $resizeHeight = null, bool $convertToWebp = false): array
    {
        $storedPaths = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $storedPaths[] = $optimize
                    ? $this->saveOptimizedImage($file, $quality, $resizeWidth, $resizeHeight, $convertToWebp)
                    : $this->saveFile($file);
            }
        }
        return $storedPaths;
    }

    public function updateMultipleFiles(array $newFiles, array $existingFiles = [], bool $optimize = false, int $quality = null, ?int $resizeWidth = null, ?int $resizeHeight = null, bool $convertToWebp = false): array
    {
        foreach ($existingFiles as $oldFile) {
            $this->deleteFile($oldFile);
        }
        return $this->saveMultipleFiles($newFiles, $optimize, $quality, $resizeWidth, $resizeHeight, $convertToWebp);
    }

    public function deleteMultipleFiles(array $files): bool
    {
        $success = true;
        foreach ($files as $file) {
            if (!$this->deleteFile($file)) {
                $success = false;
            }
        }
        return $success;
    }
}
