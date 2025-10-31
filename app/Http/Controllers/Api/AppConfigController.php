<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppConfigController extends Controller
{
    /**
     * Get app configuration and settings
     */
    public function index()
    {
        // Get the first (and only) settings record
        $settings = AppSetting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'App settings not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => $settings->app_name,
                'app_name_kh' => $settings->app_name_kh,
                'tagline' => $settings->tagline,
                'logo' => $settings->logo ? asset($settings->logo) : null,
                'splash_logo' => $settings->splash_logo ? asset($settings->splash_logo) : null,
                'splash_background_color' => $settings->splash_background_color,
                'primary_color' => $settings->primary_color,
                'phone' => $settings->phone,
                'email' => $settings->email,
                'address' => $settings->address,
                'currency' => $settings->currency,
                'currency_symbol' => $settings->currency_symbol,
                'delivery_fee' => $settings->delivery_fee,
                'tax_rate' => $settings->tax_rate,
                'app_version' => $settings->app_version,
                'is_maintenance' => $settings->is_maintenance,
            ],
        ]);
    }
}
