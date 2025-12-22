<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('id', 'desc')->get();
        return view('addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $data['user_id'] = Auth::id();
        Address::create($data);

        return redirect()->back()->with('success', 'Đã lưu địa chỉ');
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'street_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);
        $address->update($data);
        return redirect()->back()->with('success', 'Đã cập nhật địa chỉ');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return redirect()->back()->with('success', 'Đã xoá địa chỉ');
    }
}
