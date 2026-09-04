<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PublicProject;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = PublicProject::published();

        if ($category = $request->query('categorie')) {
            $query->where('category', $category);
        }

        return view('public.projects.index', [
            'projects' => $query->paginate(12)->withQueryString(),
            'categories' => PublicProject::published()->reorder()->select('category')->distinct()->pluck('category'),
            'activeCategory' => $category ?? null,
        ]);
    }

    public function show(string $slug): View
    {
        $project = PublicProject::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return view('public.projects.show', [
            'project' => $project,
            'relatedProjects' => PublicProject::published()->where('id', '!=', $project->id)->where('category', $project->category)->limit(3)->get(),
        ]);
    }
}
