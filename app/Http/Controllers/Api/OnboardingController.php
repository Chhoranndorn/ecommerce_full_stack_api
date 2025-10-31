<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Onboarding;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    /**
     * Get all active onboarding screens
     */
    public function index()
    {
        $onboardings = Onboarding::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'title_en' => $item->title_en,
                    'description' => $item->description,
                    'description_en' => $item->description_en,
                    'image' => asset($item->image),
                    'image_secondary' => $item->image_secondary ? asset($item->image_secondary) : null,
                    'order' => $item->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $onboardings,
        ]);
    }
}
