<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Get wallet balance and summary
     */
    public function balance(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'wallet_balance' => $user->wallet_balance,
                'points_balance' => $user->points_balance,
                'currency' => '$',
            ],
        ]);
    }

    /**
     * Get wallet transaction history
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        $transactions = WalletTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'amount' => $transaction->amount,
                    'balance_after' => $transaction->balance_after,
                    'description' => $transaction->description,
                    'date' => $transaction->created_at->format('d-M-Y'),
                    'time' => $transaction->created_at->format('H:i'),
                    'created_at' => $transaction->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }
}
