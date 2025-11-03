<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Submit feedback
     */
    public function submit(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'nullable|string|max:1000',
            'email' => 'nullable|email',
            'allow_contact' => 'boolean',
            'subscribe_newsletter' => 'boolean',
        ]);

        $feedback = Feedback::create([
            'user_id' => $request->user() ? $request->user()->id : null,
            'rating' => $request->rating,
            'message' => $request->message,
            'email' => $request->email,
            'allow_contact' => $request->allow_contact ?? false,
            'subscribe_newsletter' => $request->subscribe_newsletter ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!',
            'data' => [
                'id' => $feedback->id,
                'rating' => $feedback->rating,
            ],
        ], 201);
    }

    /**
     * Get user's feedback history (for authenticated users)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $feedbacks = Feedback::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($feedback) {
                return [
                    'id' => $feedback->id,
                    'rating' => $feedback->rating,
                    'message' => $feedback->message,
                    'status' => $feedback->status,
                    'date' => $feedback->created_at->format('d-M-Y'),
                    'created_at' => $feedback->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $feedbacks,
        ]);
    }
}
