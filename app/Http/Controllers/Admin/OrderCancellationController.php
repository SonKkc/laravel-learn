<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderCancellationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\ProcessCancellation;
use Illuminate\Pagination\Paginator;

use App\Models\Order;

class OrderCancellationController extends Controller
{
    public function approve(Request $request, OrderCancellationRequest $req)
    {
        // TODO: add admin authorization check
        if ($req->status !== 'requested') {
            return back()->with('error', 'Request already processed.');
        }

        $req->status = 'approved';
        $req->admin_id = Auth::id();
        $req->processed_at = now();
        $req->save();

        // Mark the related order as cancelled now so client's UI reflects the change immediately
        if ($req->order) {
            $req->order->status = 'cancelled';
            $req->order->save();
        }

        // Dispatch job to process actual cancellation (refund/stock/notifications)
        ProcessCancellation::dispatch($req);

        return back()->with('success', 'Cancellation request approved.');
    }

    public function index(Request $request)
    {
        $items = OrderCancellationRequest::with(['order', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.cancellations.index', compact('items'));
    }

    public function show(Request $request, OrderCancellationRequest $req)
    {
        $req->load(['order.items.product', 'user']);
        return view('admin.cancellations.show', ['requestModel' => $req]);
    }

    public function reject(Request $request, OrderCancellationRequest $req)
    {
        // TODO: add admin authorization check
        if ($req->status !== 'requested') {
            return back()->with('error', 'Request already processed.');
        }

        $data = $request->validate(['admin_note' => 'nullable|string|max:2000']);

        $req->status = 'rejected';
        $req->admin_id = Auth::id();
        $req->admin_note = $data['admin_note'] ?? null;
        $req->processed_at = now();
        $req->save();

        return back()->with('success', 'Cancellation request rejected.');
    }
}
