<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;

class DeliveryMethodController extends Controller
{
    /**
     * Get all active delivery methods
     */
    public function index()
    {
        $methods = DeliveryMethod::where('is_active', true)
            ->orderBy('price')
            ->get()
            ->map(function ($method) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'name_kh' => $method->name_kh,
                    'description' => $method->description,
                    'price' => $method->price,
                    'estimated_days' => $method->estimated_days,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }

    /**
     * Get a specific delivery method
     */
    public function show($id)
    {
        $method = DeliveryMethod::where('is_active', true)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $method->id,
                'name' => $method->name,
                'name_kh' => $method->name_kh,
                'description' => $method->description,
                'price' => $method->price,
                'estimated_days' => $method->estimated_days,
            ],
        ]);
    }
}
