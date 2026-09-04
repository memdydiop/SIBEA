<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Expertise;
use Illuminate\View\View;

class ExpertiseController extends Controller
{
    public function index(): View
    {
        return view('public.expertises.index', [
            'expertises' => Expertise::active()->withCount('services')->get(),
        ]);
    }

    public function show(string $slug): View
    {
        $expertise = Expertise::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $expertise->load(['services' => fn ($q) => $q->where('is_active', true)->orderBy('order')]);

        return view('public.expertises.show', [
            'expertise' => $expertise,
            'otherExpertises' => Expertise::active()->where('id', '!=', $expertise->id)->limit(4)->get(),
        ]);
    }
}
