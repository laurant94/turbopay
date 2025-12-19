<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WebhookEndpoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Enums\EventType; // Import EventType enum
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class WebhookEndpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant) {
            return redirect()->route('dashboard')->with('error', 'Aucun marchand actif trouvé.');
        }

        $webhookEndpoints = WebhookEndpoint::where('merchant_id', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('WebhookEndpoints/Index', [
            'webhookEndpoints' => $webhookEndpoints,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get all cases from the EventType enum for selection in the frontend
        $eventTypes = array_map(fn($case) => ['value' => $case->value, 'label' => Str::title(str_replace('_', ' ', $case->value))], EventType::cases());

        return Inertia::render('WebhookEndpoints/Create', [
            'eventTypes' => $eventTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant) {
            return redirect()->route('dashboard')->with('error', 'Aucun marchand actif trouvé.');
        }

        $validated = $request->validate([
            'url' => ['required', 'url'],
            'secret' => ['nullable', 'string', 'min:16', 'max:64'],
            'events' => ['nullable', 'array'], // Allow 'events' to be null
            'events.*' => [
                Rule::requiredIf(fn() => $request->has('events') && is_array($request->input('events'))), // Only apply if 'events' is an array
                Rule::in(array_map(fn($case) => $case->value, EventType::cases()))
            ],
            'headers' => ['nullable', 'array'],
            'headers.*.key' => ['required', 'string', 'max:255'],
            'headers.*.value' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
        ]);

        $validated['merchant_id'] = $merchant->id;
        $validated['secret'] = $validated['secret'] ?? Str::random(32); // Generate a secret if not provided
        $validated['active'] = $validated['active'] ?? true; // Default to active

        WebhookEndpoint::create($validated);

        return redirect()->route('user.webhook-endpoints.index')->with('success', 'Webhook endpoint créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(WebhookEndpoint $webhookEndpoint)
    {
        // Ensure the webhook belongs to the active merchant
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant || $webhookEndpoint->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized action.');
        }

        // You could also eager load related webhook events if you want to display them
        // $webhookEndpoint->load('events'); 
        // For now, let's keep it simple and just pass the endpoint itself.

        return Inertia::render('WebhookEndpoints/Show', [
            'webhookEndpoint' => $webhookEndpoint,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebhookEndpoint $webhookEndpoint)
    {
        // Ensure the webhook belongs to the active merchant
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant || $webhookEndpoint->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized action.');
        }

        $eventTypes = array_map(fn($case) => ['value' => $case->value, 'label' => Str::title(str_replace('_', ' ', $case->value))], EventType::cases());

        return Inertia::render('WebhookEndpoints/Edit', [
            'webhookEndpoint' => $webhookEndpoint,
            'eventTypes' => $eventTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebhookEndpoint $webhookEndpoint)
    {
        // Ensure the webhook belongs to the active merchant
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant || $webhookEndpoint->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'url' => ['required', 'url'],
            'secret' => ['nullable', 'string', 'min:16', 'max:64'],
            'events' => ['nullable', 'array'],
            'events.*' => [
                Rule::requiredIf(fn() => $request->has('events') && is_array($request->input('events'))),
                Rule::in(array_map(fn($case) => $case->value, EventType::cases()))
            ],
            'headers' => ['nullable', 'array'],
            'headers.*.key' => ['required', 'string', 'max:255'],
            'headers.*.value' => ['required', 'string', 'max:255'],
            'active' => ['boolean'],
        ]);

        $validated['secret'] = $validated['secret'] ?? Str::random(32); // Generate if cleared or not provided
        $validated['active'] = $validated['active'] ?? false; // Explicitly handle checkbox uncheck

        $webhookEndpoint->update($validated);

        return redirect()->route('user.webhook-endpoints.index')->with('success', 'Webhook endpoint mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebhookEndpoint $webhookEndpoint)
    {
        // Ensure the webhook belongs to the active merchant
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        if (!$merchant || $webhookEndpoint->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized action.');
        }

        $webhookEndpoint->delete();

        return redirect()->route('user.webhook-endpoints.index')->with('success', 'Webhook endpoint supprimé avec succès.');
    }
}
