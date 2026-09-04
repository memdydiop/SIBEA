<?php

namespace App\Actions\Organization;

use App\Enums\ContractType;
use App\Enums\EmployeeStatus;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateEmployeeAction
{
    /**
     * Update an existing employee.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(Employee $employee, array $input): Employee
    {
        $validated = Validator::make($input, [
            'registration_number' => [
                'required',
                'string',
                'max:50',
                'regex:/^EMP-\d{4}-\d{4}$/',
                Rule::unique('employees', 'registration_number')->ignore($employee->id),
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'job_title' => ['required', 'string', 'max:150'],
            'user_id' => [
                'nullable',
                'integer',
                Rule::unique('employees', 'user_id')->ignore($employee->id),
                'exists:users,id',
            ],
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
            'is_public' => ['nullable', 'boolean'],
        ])->validate();

        $employee->update($validated);

        return $employee;
    }
}
