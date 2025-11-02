<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Special;
use Illuminate\Http\Request;

class SpecialController extends Controller
{
    /**
     * Get all active promotions/specials
     */
    public function index()
    {
        $specials = Special::active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($special) {
                return [
                    'id' => $special->id,
                    'name' => $special->name,
                    'title' => $special->title,
                    'title_kh' => $special->title_kh,
                    'image' => $special->image,
                    'discount_percentage' => $special->discount_percentage,
                    'discount_type' => $special->discount_type,
                    'discount_text' => $special->discount_text,
                    'valid_from' => $special->valid_from,
                    'valid_until' => $special->valid_until,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $specials,
        ]);
    }

    /**
     * Get a specific promotion/special by ID
     */
    public function show($id)
    {
        $special = Special::findOrFail($id);

        if (!$special->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This promotion is no longer available',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $special->id,
                'name' => $special->name,
                'title' => $special->title,
                'title_kh' => $special->title_kh,
                'image' => $special->image,
                'description' => $special->description,
                'description_kh' => $special->description_kh,
                'discount_percentage' => $special->discount_percentage,
                'discount_type' => $special->discount_type,
                'discount_text' => $special->discount_text,
                'valid_from' => $special->valid_from,
                'valid_until' => $special->valid_until,
                'is_active' => $special->is_active,
            ],
        ]);
    }
}
