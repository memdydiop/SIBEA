<?php

namespace App\Actions\Cms;

use App\Actions\Audit\LogAuditAction;
use App\Data\HomepageData;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SaveHomepageSettings
{
    /**
     * @param  array<string, mixed>  $input  Données brutes (snake_case keys = SiteSetting keys)
     * @return array<string, mixed> Données validées persistées
     */
    public function __invoke(array $input, LogAuditAction $audit): array
    {
        $validated = Validator::make($input, HomepageData::rules())->validate();

        $old = [];
        foreach (HomepageData::homepageKeys() as $key) {
            $old[$key] = SiteSetting::get($key);
        }

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value, HomepageData::groupFor($key));
        }

        Cache::forget('home:vitrine:v1');
        Cache::forget('home:vitrine:settings:v2');

        $audit('HOMEPAGE_UPDATED', SiteSetting::class, $old, $validated);

        return $validated;
    }
}
