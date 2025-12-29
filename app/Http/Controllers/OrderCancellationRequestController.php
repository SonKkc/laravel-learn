<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderCancellationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewCancellationRequest;

class OrderCancellationRequestController extends Controller
{
    public function store(Request $request, Order $order)
    {
        try {
            $this->authorize('requestCancellation', $order);

            $data = $request->validate([
                'reason' => 'nullable|string|max:2000',
            ]);

            $req = $order->cancellationRequests()->create([
                'user_id' => $request->user()->id,
                'reason' => $data['reason'] ?? null,
                'status' => 'requested',
            ]);

            // Mark the order as requesting so user and admin see the state consistently
            try {
                $order->status = 'requesting';
                $order->save();
            } catch (\Exception $e) {
                // If order update fails, log but don't block the request creation
                Log::warning('Failed to update order status to requesting: ' . $e->getMessage());
            }

            // Notify admin (fallback to mail.from address)
            try {
                Notification::route('mail', config('mail.from.address'))
                    ->notify(new NewCancellationRequest($req));
            } catch (\Exception $e) {
                // swallow notification errors for now but log for debugging
                Log::warning('Failed to send NewCancellationRequest notification: ' . $e->getMessage());
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Cancellation request submitted.']);
            }

            return back()->with('success', 'Cancellation request submitted. We will review it soon.');
        } catch (\Throwable $e) {
            // Log the exception for debugging and return a JSON error for XHR callers
            Log::error('OrderCancellationRequestController@store error: ' . $e->getMessage(), ['exception' => $e]);

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Failed to submit cancellation request. Please try again later.');
        }
    }
}
