<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PointController extends Controller
{
    /**
     * Get all point transactions (earned + withdrawn)
     */
    public function transactions(Request $request)
    {
        $user = $request->user();

        $transactions = PointTransaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'type' => $transaction->type,
                    'points' => $transaction->points,
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

    /**
     * Get earned points only
     */
    public function earned(Request $request)
    {
        $user = $request->user();

        $transactions = PointTransaction::where('user_id', $user->id)
            ->where('type', 'earned')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'points' => $transaction->points,
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

    /**
     * Get withdrawn points only
     */
    public function withdrawn(Request $request)
    {
        $user = $request->user();

        $transactions = PointTransaction::where('user_id', $user->id)
            ->where('type', 'withdrawn')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'points' => abs($transaction->points), // Show as positive
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

    /**
     * Convert points to money
     * 10 points = $1.00
     */
    public function convert(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:10',
        ]);

        $user = $request->user();
        $pointsToConvert = $request->points;

        // Check if points is multiple of 10
        if ($pointsToConvert % 10 !== 0) {
            return response()->json([
                'success' => false,
                'message' => 'Points must be a multiple of 10',
            ], 400);
        }

        // Check if user has enough points
        if ($user->points_balance < $pointsToConvert) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient points balance',
            ], 400);
        }

        // Calculate money amount (10 points = $1.00)
        $moneyAmount = $pointsToConvert / 10;

        DB::beginTransaction();

        try {
            // Deduct points from user
            $user->points_balance -= $pointsToConvert;
            $user->save();

            // Create point transaction (withdrawn)
            PointTransaction::create([
                'user_id' => $user->id,
                'type' => 'withdrawn',
                'points' => -$pointsToConvert, // Negative for withdrawal
                'balance_after' => $user->points_balance,
                'description' => "Converted {$pointsToConvert} points to money",
                'reference_type' => 'Conversion',
            ]);

            // Add money to wallet
            $user->wallet_balance += $moneyAmount;
            $user->save();

            // Create wallet transaction (credit)
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'credit',
                'amount' => $moneyAmount,
                'balance_after' => $user->wallet_balance,
                'description' => "Converted {$pointsToConvert} points",
                'reference_type' => 'PointConversion',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully converted {$pointsToConvert} points to \${$moneyAmount}",
                'data' => [
                    'points_converted' => $pointsToConvert,
                    'money_received' => $moneyAmount,
                    'new_points_balance' => $user->points_balance,
                    'new_wallet_balance' => $user->wallet_balance,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to convert points',
            ], 500);
        }
    }
}
