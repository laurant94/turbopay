<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use App\Models\AuditLog;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $merchant = $this->getMerchant();

        $logs = AuditLog::where('merchant_id', $merchant->id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return Inertia::render('AuditLogs/Index', [
            'logs' => $logs,
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