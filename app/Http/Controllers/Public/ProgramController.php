<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $query = Program::published();

        if ($city = $request->query('ville')) {
            $query->where('city', $city);
        }

        return view('public.programs.index', [
            'programs' => $query->paginate(12)->withQueryString(),
            'cities' => Program::published()->reorder()->whereNotNull('city')->select('city')->distinct()->orderBy('city')->pluck('city')->filter()->values(),
            'activeCity' => $city ?? null,
        ]);
    }

    public function show(string $slug): View
    {
        $program = Program::where('slug', $slug)->where('is_published', true)->with('lots')->firstOrFail();

        return view('public.programs.show', [
            'program' => $program,
            'lots' => $program->lots()->orderBy('reference')->get(),
            'relatedPrograms' => Program::published()
                ->where('id', '!=', $program->id)
                ->when($program->city, fn ($q) => $q->where('city', $program->city))
                ->limit(3)
                ->get(),
        ]);
    }
}
