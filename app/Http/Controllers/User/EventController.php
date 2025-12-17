<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Event; // Use the new Event model

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $merchant = $this->getMerchant();

        // Make sure a merchant is active
        if (!$merchant) {
            // Handle case where no active merchant is found, e.g., redirect or show error
            return redirect()->route('dashboard')->with('error', 'Aucun marchand actif trouvé.');
        }

        $events = Event::where('merchant_id', $merchant->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return Inertia::render('Events/Index', [
            'events' => $events,
        ]);
    }

    protected function getMerchant(): Merchant{
        $merchantId = session('merchant');
        if(!$merchantId){
            return abort(404, "Aucun marchant trouvé");
        }

        $merchant = Merchant::findOrFail($merchantId);

        return $merchant;
    }
}
