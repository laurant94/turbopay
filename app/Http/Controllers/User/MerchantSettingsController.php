<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MerchantSettingsController extends Controller
{
    public function index(Request $request)
    {
        $merchant = $this->getMerchant();
        // Ensure the user is authorized to see this merchant's settings
        if ($request->user()->id !== $merchant->user_id) {
            abort(403);
        }

        return Inertia::render('Merchants/Settings', [
            'merchant' => $merchant,
            'fee_payer' => $merchant->settings()->get('merchant.fees.payer', 'merchant'),
        ]);
    }

    public function update(Request $request, Merchant $merchant)
    {
        // Ensure the user is authorized to update this merchant's settings
        if ($request->user()->id !== $merchant->user_id) {
            abort(403);
        }

        $request->validate([
            'fee_payer' => 'required|string|in:merchant,customer',
        ]);

        $merchant->settings()->set('merchant.fees.payer', $request->input('fee_payer'));

        return back()->with('success', 'Settings updated.');
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
