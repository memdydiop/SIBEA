<?php

namespace App\Concerns;

use App\Data\HomepageData;
use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

/**
 * Factorise le chargement et l'upload des 3 slides hero (DRY entre Livewire et Action).
 */
trait WithHomepageSettings
{
    /**
     * Charge les valeurs homepage depuis SiteSetting (avec defaults).
     *
     * @return array<string, mixed>
     */
    protected function loadHomepageData(): array
    {
        return HomepageData::fromSettings();
    }

    /**
     * Stocke un upload d'image hero/slide dans cms/home et retourne le chemin public.
     */
    protected function storeHomepageImage(?UploadedFile $file, string $directory = 'cms/home'): ?string
    {
        if (! $file) {
            return null;
        }

        Validator::make(
            ['file' => $file],
            ['file' => ['image', 'mimes:jpeg,png,webp', 'max:2048', 'dimensions:max_width=4000,max_height=4000']],
        )->validate();

        $path = $file->store($directory, 'public');

        return '/storage/'.$path;
    }

    /**
     * Stocke le logo branding.
     */
    protected function storeBrandingLogo(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        Validator::make(
            ['file' => $file],
            ['file' => ['image', 'mimes:jpeg,png,webp,svg', 'max:1024', 'dimensions:max_width=2000,max_height=2000']],
        )->validate();

        $path = $file->store('cms/branding', 'public');

        return '/storage/'.$path;
    }
}
