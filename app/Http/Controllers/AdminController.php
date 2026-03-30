<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'catalogue' => Game::count(),
            'location' => Game::whereIn('offer_type', ['location', 'location_vente'])->count(),
            'vente' => Game::whereIn('offer_type', ['vente', 'location_vente'])->count(),
            'stock' => (int) Game::sum('stock_total'),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'users' => User::orderBy('pseudo')->get(),
            'orders' => Order::query()->with(['items.game', 'user'])->latest('id')->take(8)->get(),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::orderBy('pseudo')->get(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', 'in:admin,user'],
        ]);

        $user->update([
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Role mis a jour.');
    }
}
