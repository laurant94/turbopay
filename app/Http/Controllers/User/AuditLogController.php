<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $merchant = $user->activeMerchant();

        $logs = AuditLog::where('merchant_id', $merchant->id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return Inertia::render('AuditLogs/Index', [
            'logs' => $logs,
        ]);
    }
}