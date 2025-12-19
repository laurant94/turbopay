<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WebhookEvent; // Import the model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WebhookEventController extends Controller
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

        $webhookEvents = WebhookEvent::where('merchant_id', $merchant->id)
            ->with('webhookEndpoint') // Eager load the endpoint to display its URL
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('WebhookEvents/Index', [
            'webhookEvents' => $webhookEvents,
        ]);
    }
}