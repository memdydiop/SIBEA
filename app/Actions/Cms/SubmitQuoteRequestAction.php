<?php

namespace App\Actions\Cms;

use App\Enums\QuoteRequestStatus;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\Validator;

class SubmitQuoteRequestAction
{
    /**
     * Submit a new quote request from public site.
     *
     * @param  array<string, mixed>  $input
     */
    public function __invoke(array $input): QuoteRequest
    {
        $validated = Validator::make($input, [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'company' => ['nullable', 'string', 'max:150'],
            'role' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'location' => ['nullable', 'string', 'max:150'],
            'service_type' => ['required', 'string', 'max:150'],
            'project_nature' => ['nullable', 'string', 'max:150'],
            'estimated_budget' => ['nullable', 'string', 'max:100'],
            'desired_timeline' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'consent' => ['required', 'accepted'],
        ])->validate();

        $validated['consent'] = true;
        $validated['status'] = QuoteRequestStatus::New;
        $validated['reference'] = $this->generateReference();

        return QuoteRequest::create($validated);
    }

    private function generateReference(): string
    {
        $year = now()->year;
        $count = QuoteRequest::withTrashed()->whereYear('created_at', $year)->count() + 1;

        do {
            $reference = sprintf('QR-%d-%04d', $year, $count);
            $exists = QuoteRequest::withTrashed()->where('reference', $reference)->exists();
            if ($exists) {
                $count++;

                continue;
            }
            break;
        } while (true);

        return $reference;
    }
}
