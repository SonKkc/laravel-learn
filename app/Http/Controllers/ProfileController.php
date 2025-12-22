<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the user profile and their orders.
     */
    public function show(Request $request)
    {
        $user = Auth::user();

        // If no authenticated user, redirect to login (middleware('auth') should prevent this)
        if (! $user) {
            return redirect()->route('login');
        }

        // Supported statuses (adjust to match your app's statuses)
        $availableStatuses = [
            'all' => 'Tất cả',
            'new' => 'Chờ xử lý',
            'processing' => 'Đang xử lý',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        $status = $request->query('status', 'all');

        // Get user addresses for profile page
        $addresses = $user->addresses()->orderBy('created_at', 'desc')->get();

        // Build query and paginate
        $query = $user->orders()->with('items.product')->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $perPage = 10;
        $orders = $query->paginate($perPage)->withQueryString();

        // Get counts per status for tabs
        $statusCounts = $user->orders()
            ->selectRaw('COALESCE(status, "") as status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('profile.show', compact('user', 'orders', 'statusCounts', 'status', 'availableStatuses', 'addresses'));
    }

    /**
     * Update basic profile information (name, email)
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        if (! $user) return redirect()->route('login');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // If email changed, consider verifying flow elsewhere
        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Cập nhật hồ sơ thành công');
    }
}
