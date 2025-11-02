<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Get all user addresses
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $addresses = Address::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($address) {
                return [
                    'id' => $address->id,
                    'type' => $address->type,
                    'address' => $address->address,
                    'name' => $address->name,
                    'phone' => $address->phone,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'is_default' => $address->is_default,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $addresses,
        ]);
    }

    /**
     * Get default address
     */
    public function getDefault(Request $request)
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)
            ->where('is_default', true)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'No default address found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'address' => $address->address,
                'name' => $address->name,
                'phone' => $address->phone,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Get a specific address
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'address' => $address->address,
                'name' => $address->name,
                'phone' => $address->phone,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Create a new address
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:home,office,other',
            'address' => 'required|string',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $address = Address::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'address' => $request->address,
            'name' => $request->name,
            'phone' => $request->phone,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => $request->is_default ?? false,
        ]);

        // If this is set as default, update other addresses
        if ($address->is_default) {
            $address->setAsDefault();
        }

        // If this is the first address, make it default
        $addressCount = Address::where('user_id', $user->id)->count();
        if ($addressCount === 1) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'address' => $address->address,
                'name' => $address->name,
                'phone' => $address->phone,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'is_default' => $address->is_default,
            ],
        ], 201);
    }

    /**
     * Update an address
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'nullable|in:home,office,other',
            'address' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_default' => 'nullable|boolean',
        ]);

        $user = $request->user();

        $address = Address::where('user_id', $user->id)
            ->findOrFail($id);

        $address->update($request->only([
            'type',
            'address',
            'name',
            'phone',
            'latitude',
            'longitude',
            'is_default',
        ]));

        // If this is set as default, update other addresses
        if ($request->has('is_default') && $request->is_default) {
            $address->setAsDefault();
        }

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'address' => $address->address,
                'name' => $address->name,
                'phone' => $address->phone,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Delete an address
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)
            ->findOrFail($id);

        $wasDefault = $address->is_default;

        $address->delete();

        // If deleted address was default, set another as default
        if ($wasDefault) {
            $newDefault = Address::where('user_id', $user->id)->first();
            if ($newDefault) {
                $newDefault->setAsDefault();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully',
        ]);
    }

    /**
     * Set an address as default
     */
    public function setDefault(Request $request, $id)
    {
        $user = $request->user();

        $address = Address::where('user_id', $user->id)
            ->findOrFail($id);

        $address->setAsDefault();

        return response()->json([
            'success' => true,
            'message' => 'Default address set successfully',
            'data' => [
                'id' => $address->id,
                'type' => $address->type,
                'address' => $address->address,
                'name' => $address->name,
                'phone' => $address->phone,
                'is_default' => $address->is_default,
            ],
        ]);
    }
}
