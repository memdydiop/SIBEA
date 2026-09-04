<?php

namespace App\Http\Controllers\Public;

use App\Enums\EmployeeStatus;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('public.team.index', [
            'departments' => Department::active()->with(['employees' => fn ($q) => $q->where('status', EmployeeStatus::Active)->where('is_public', true)->orderBy('last_name')])->get(),
            'teams' => Team::where('is_active', true)->with(['leader', 'members' => fn ($q) => $q->where('status', EmployeeStatus::Active)->where('is_public', true)])->get(),
            'employees' => Employee::where('status', EmployeeStatus::Active)->where('is_public', true)->with('department')->orderBy('last_name')->paginate(12),
        ]);
    }
}
