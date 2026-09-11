<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface StorageServiceInterface
{
    public function uploadRestaurantLogo(UploadedFile $file): string;
    public function uploadRestaurantCover(UploadedFile $file): string;
    public function uploadMenuItem(UploadedFile $file): string;
    public function uploadMenuCategory(UploadedFile $file): string;
    public function uploadUserAvatar(UploadedFile $file): string;
    public function uploadDeliveryProof(UploadedFile $file): string;
    public function getDisk();
    public function delete(string $path): bool;
    public function exists(string $path): bool;
    public function move(string $from, string $to): bool;
    public function url(string $path): string;
    public function temporaryUrl(string $path, int $minutes = 60): string;
    public function uploadExport(string $content, string $filename): string;
}
