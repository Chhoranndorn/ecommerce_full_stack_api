<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    /**
     * Get app configuration settings
     */
    public function getConfig()
    {
        $settings = AppSetting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'App settings not configured',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => $settings->app_name,
                'app_name_kh' => $settings->app_name_kh,
                'tagline' => $settings->tagline,
                'logo' => $settings->logo,
                'primary_color' => $settings->primary_color,
                'currency' => $settings->currency,
                'currency_symbol' => $settings->currency_symbol,
                'app_version' => $settings->app_version,
                'is_maintenance' => $settings->is_maintenance,
            ],
        ]);
    }

    /**
     * Get about us information
     */
    public function getAboutUs()
    {
        $settings = AppSetting::first();

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'About us information not available',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => $settings->app_name,
                'app_name_kh' => $settings->app_name_kh,
                'about_us' => $settings->about_us,
                'about_us_kh' => $settings->about_us_kh,
                'banner_image' => $settings->banner_image,
                'contact' => [
                    'phone' => $settings->phone,
                    'email' => $settings->email,
                    'address' => $settings->address,
                ],
                'social_media' => [
                    'facebook' => $settings->facebook,
                    'instagram' => $settings->instagram,
                    'telegram' => $settings->telegram,
                    'website' => $settings->website,
                ],
                'location' => [
                    'latitude' => $settings->latitude,
                    'longitude' => $settings->longitude,
                ],
            ],
        ]);
    }
}
