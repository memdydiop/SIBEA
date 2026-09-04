<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function show(string $slug): View
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->with('expertise')->firstOrFail();

        return view('public.services.show', [
            'service' => $service,
            'relatedServices' => Service::active()
                ->where('expertise_id', $service->expertise_id)
                ->where('id', '!=', $service->id)
                ->limit(4)
                ->get(),
        ]);
    }
}
