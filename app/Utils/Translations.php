<?php

namespace App\Utils;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class Translations
{
    public function render(): View|Closure|string|array
    {
        $locale = session()->has('locale') ? session('locale') : app()->getLocale();
        $translations = app()->environment('production')
            ? cache()->rememberForever("translations_$locale", fn() => $this->getTranslations($locale))
            : $this->getTranslations($locale);

        return $translations;
    }

    private function getTranslations(string $locale): array
    {
        $appPHPTranslations = $this->getPHPTranslations(lang_path($locale));

        $appJsonTranslations = $this->getJsonTranslations(lang_path("$locale/$locale.json"));
        $modularJsonTranslations = $this->getJsonTranslations(lang_path("$locale.json"));

        return array_merge($appPHPTranslations, $appJsonTranslations, $modularJsonTranslations);
    }

    private function getPHPTranslations(string $directory): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        return collect(File::allFiles($directory))
            ->filter(fn($file) => $file->getExtension() === 'php')->flatMap(fn($file) => Arr::dot(File::getRequire($file->getRealPath())))->toArray();
    }

    private function getJsonTranslations(string $filePath): array
    {
        if (File::exists($filePath)) {
            return json_decode(File::get($filePath), true, 512, JSON_THROW_ON_ERROR);
        } else {
            return [];
        }
    }
}
