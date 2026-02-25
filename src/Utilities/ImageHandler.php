<?php

namespace Shahrakii\Crudly\Utilities;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHandler
{
    protected $disk = 'public';
    protected $defaultPath = 'uploads';
    protected $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    protected $maxSize = 2048; // KB

    /**
     * Check if field name suggests it's an image field
     */
    public static function isImageField(string $fieldName): bool
    {
        $imageIndicators = [
            'image', 'photo', 'picture',
            'avatar', 'thumbnail', 'icon',
            'featured', 'cover', 'banner',
            'logo', 'profile', 'screenshot'
        ];

        $fieldLower = Str::lower($fieldName);

        foreach ($imageIndicators as $indicator) {
            if (str_contains($fieldLower, $indicator)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Store uploaded image
     */
    public function store(UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $path = $file->store($folder, $this->disk);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Delete image file
     */
    public function delete(?string $path): bool
    {
        if (!$path) {
            return true;
        }

        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get image URL
     */
    public static function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return asset('storage/' . $path);
    }

    /**
     * Get validation rules for image fields
     */
    public static function validationRules(bool $isRequired = true): string
    {
        $rules = $isRequired ? 'required' : 'nullable';
        return "{$rules}|image|mimes:jpeg,png,gif,webp|max:2048";
    }

    /**
     * Get image field names from array
     */
    public static function getImageFields(array $columns): array
    {
        $imageFields = [];

        foreach ($columns as $column) {
            if (self::isImageField($column['name'])) {
                $imageFields[] = $column['name'];
            }
        }

        return $imageFields;
    }
}
