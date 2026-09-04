<?php

namespace App\Http\Controllers\Public;

use App\Actions\Cms\SubmitQuoteRequestAction;
use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(): View
    {
        return view('public.quote.create');
    }

    public function store(Request $request, SubmitQuoteRequestAction $action): RedirectResponse
    {
        $quote = $action($request->all());

        return redirect()->route('public.quote.success', ['reference' => $quote->reference])
            ->with('success', __('Votre demande a été envoyée avec succès. Référence : :ref', ['ref' => $quote->reference]));
    }

    public function success(string $reference): View
    {
        $quote = QuoteRequest::where('reference', $reference)->firstOrFail();

        return view('public.quote.success', ['quote' => $quote]);
    }
}
