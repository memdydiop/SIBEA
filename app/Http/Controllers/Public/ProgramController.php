<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        return view('public.programs.index', [
            'programs' => Program::published()->paginate(12),
        ]);
    }

    public function show(string $slug): View
    {
        $program = Program::where('slug', $slug)->where('is_published', true)->with('lots')->firstOrFail();

        return view('public.programs.show', [
            'program' => $program,
            'lots' => $program->lots()->orderBy('reference')->get(),
        ]);
    }
}
