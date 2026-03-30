<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::query()
            ->with(['items.game', 'user'])
            ->latest('id');

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        $orders = $query->get();

        return view('orders.index', [
            'orders' => $orders,
            'isAdmin' => $request->user()->isAdmin(),
        ]);
    }
}
