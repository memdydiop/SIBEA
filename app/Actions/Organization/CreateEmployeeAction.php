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

            do {
                $padded = str_pad((string) $nextNumber, 8, '0', STR_PAD_LEFT);
                $candidate = sprintf('EMP-%s-%s', substr($padded, 0, 4), substr($padded, 4, 4));
                $exists = Employee::withTrashed()->where('registration_number', $candidate)->exists();

                if ($exists) {
                    $nextNumber++;

                    continue;
                }

                break;
            } while (true);

            $input['registration_number'] = $candidate;
        }

        $validated = Validator::make($input, [
            'registration_number' => ['required', 'string', 'max:50', 'regex:/^EMP-\d{4}-\d{4}$/', 'unique:employees,registration_number'],
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
            'is_public' => ['nullable', 'boolean'],
        ])->validate();

        $validated['is_public'] = $validated['is_public'] ?? false;

        return Employee::create($validated);
    }
}
