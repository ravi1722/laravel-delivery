<?php

namespace App\Services;

use App\Contracts\StorageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService implements StorageServiceInterface
{
    /**
     * Create a new class instance.
     */
    private string $disk;
    public function __construct()
    {
        $this->disk = config('filesystems.default');
    }

    public function getDisk()
    {
        return Storage::disk($this->disk);
    }

    private function upload(UploadedFile $file, string $folder)
    {
        $filename  = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $path      = $folder . '/' . $filename;
        Storage::disk($this->disk)->putFileAs($folder, $file, $filename);
        return $path;
    }
    // ── Upload with Custom Name ──────────────────────────────────
    private function uploadAs(
        UploadedFile $file,
        string $folder,
        string $name
    ): string {
        $filename = Str::slug($name) . '-' . time() . '.' . $file->getClientOriginalExtension();

        Storage::disk($this->disk)->putFileAs($folder, $file, $filename, [
            'visibility' => 'public',
        ]);

        return $folder . '/' . $filename;
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }
    // ── Get Temporary URL (for private files) ───────────────────
    public function temporaryUrl(string $path, int $minutes = 60): string
    {
        return Storage::disk($this->disk)->temporaryUrl($path, now()->addMinutes($minutes));
    }

    public function delete(string $path): bool
    {
        if (!$path) return false;
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }
        return false;
    }
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }
    public function move(string $from, string $to): bool
    {
        return Storage::disk($this->disk)->move($from, $to);
    }
    public function uploadExport(string $content, string $filename): string
    {
        $path = 'exports/' . $filename;

        Storage::disk($this->disk)->put($path, $content, [
            'visibility' => 'private', // exports are private
        ]);

        return $path;
    }
    public function uploadRestaurantLogo(UploadedFile $file): string
    {
        return $this->upload($file, 'restaurants/logos');
    }
    public function uploadRestaurantCover(UploadedFile $file): string
    {
        return $this->upload($file, 'restaurants/covers');
    }
    public function uploadMenuItem(UploadedFile $file): string
    {
        return $this->upload($file, 'menu/items');
    }
    public function uploadMenuCategory(UploadedFile $file): string
    {
        return $this->upload($file, 'menu/categories');
    }

    public function uploadUserAvatar(UploadedFile $file): string
    {
        return $this->upload($file, 'users/avatars');
    }

    public function uploadDeliveryProof(UploadedFile $file): string
    {
        return $this->upload($file, 'deliveries/proofs');
    }
}
