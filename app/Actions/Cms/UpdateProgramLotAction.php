<?php

namespace App\Actions\Cms;

use App\Enums\LotStatus;
use App\Models\ProgramLot;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class UpdateProgramLotAction
{
    /**
     * @param  array<string, mixed>  $input
     */
    public function __invoke(ProgramLot $lot, array $input): ProgramLot
    {
        $validated = Validator::make($input, [
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'reference' => ['required', 'string', 'max:50', Rule::unique('program_lots', 'reference')->ignore($lot->id)],
            'surface' => ['nullable', 'numeric', 'min:0'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', new Enum(LotStatus::class)],
            'is_viabilise' => ['nullable', 'boolean'],
            'juridical_status' => ['nullable', 'string', 'max:50'],
            'plan_pdf_path' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'published_at' => ['nullable', 'date'],
        ])->validate();

        $newStatus = LotStatus::from($validated['status']);
        if ($lot->status !== $newStatus && ! $lot->status->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => __('Transition de :from vers :to non autorisée.', ['from' => $lot->status->label(), 'to' => $newStatus->label()]),
            ]);
        }

        $lot->update($validated);

        return $lot;
    }
}
