<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredGames = Game::query()
            ->withCount('activeBorrowings')
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'catalogue' => Game::count(),
            'location' => Game::whereIn('offer_type', ['location', 'location_vente'])->count(),
            'vente' => Game::whereIn('offer_type', ['vente', 'location_vente'])->count(),
            'stock' => (int) Game::sum('stock_total'),
            'users' => User::count(),
            'orders' => Order::count(),
        ];

        return view('home', compact('featuredGames', 'stats'));
    }
}
