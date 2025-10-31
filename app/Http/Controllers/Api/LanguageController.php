<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Get all active languages
     */
    public function index()
    {
        $languages = Language::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->map(function ($language) {
                return [
                    'id' => $language->id,
                    'name' => $language->name,
                    'name_en' => $language->name_en,
                    'code' => $language->code,
                    'flag_icon' => asset($language->flag_icon),
                    'is_default' => $language->is_default,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $languages,
        ]);
    }
}
