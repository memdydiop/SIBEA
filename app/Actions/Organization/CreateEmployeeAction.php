<?php

namespace App\Actions\Organization;

use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class CreateEmployeeAction
{
    /**
     * Create a new employee.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): Employee
    {
        if (empty($input['registration_number'])) {
            $latestEmployee = Employee::withTrashed()->latest('id')->first();
            $nextNumber = $latestEmployee ? ($latestEmployee->id + 1) : 1;
            $input['registration_number'] = sprintf('SIB-%04d', $nextNumber);
        }

        $validated = Validator::make($input, [
            'registration_number' => ['required', 'string', 'max:50', 'unique:employees,registration_number'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'job_title' => ['required', 'string', 'max:150'],
            'user_id' => ['nullable', 'integer', 'unique:employees,user_id', 'exists:users,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'contract_type' => ['required', new Enum(ContractType::class)],
            'status' => ['required', new Enum(EmployeeStatus::class)],
            'hire_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:hire_date'],
            'hourly_cost_rate' => ['nullable', 'numeric', 'min:0'],
            'daily_cost_rate' => ['nullable', 'numeric', 'min:0'],
            'emergency_contact_name' => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ])->validate();

        return Employee::create($validated);
    }
}
