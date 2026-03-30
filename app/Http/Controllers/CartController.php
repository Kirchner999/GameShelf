<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $items = $this->cartItems($request);

        return view('cart.index', [
            'items' => $items,
            'cartTotal' => collect($items)->sum(fn (array $item): float => $item['subtotal']),
        ]);
    }

    public function add(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $game = Game::withCount('activeBorrowings')->findOrFail($data['game_id']);
        $quantity = (int) ($data['quantity'] ?? 1);

        abort_unless($game->canBePurchased($quantity), 422, 'Produit indisponible pour la vente.');

        $cart = $request->session()->get('cart', []);
        $cart[$game->id] = min(99, ((int) ($cart[$game->id] ?? 0)) + $quantity);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'Jeu ajoute au panier.');
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cart = $request->session()->get('cart', []);
        $quantity = (int) $data['quantity'];

        if ($quantity === 0) {
            unset($cart[$game->id]);
            $request->session()->put('cart', $cart);

            return redirect()->route('cart.index')->with('status', 'Jeu retire du panier.');
        }

        $game->loadCount('activeBorrowings');
        abort_unless($game->canBePurchased($quantity), 422, 'Quantite indisponible.');

        $cart[$game->id] = $quantity;
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'Panier mis a jour.');
    }

    public function remove(Request $request, Game $game): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$game->id]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('status', 'Jeu retire du panier.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $items = $this->cartItems($request);
        abort_if($items === [], 422, 'Le panier est vide.');

        DB::transaction(function () use ($items, $request): void {
            $total = collect($items)->sum(fn (array $item): float => $item['subtotal']);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_amount' => round($total, 2),
                'status' => 'confirmee',
                'ordered_at' => now()->toDateString(),
            ]);

            foreach ($items as $item) {
                $game = Game::query()->lockForUpdate()->withCount('activeBorrowings')->findOrFail($item['model']->id);
                abort_unless($game->canBePurchased($item['quantity']), 422, 'Le panier contient un produit hors stock.');

                $order->items()->create([
                    'game_id' => $game->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $game->price,
                ]);

                $game->decrement('stock_total', $item['quantity']);
            }
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('status', 'Commande confirmee.');
    }

    private function cartItems(Request $request): array
    {
        $cart = $request->session()->get('cart', []);

        if ($cart === []) {
            return [];
        }

        $games = Game::query()
            ->withCount('activeBorrowings')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $gameId => $quantity) {
            $game = $games->get((int) $gameId);

            if (! $game || ! $game->canBePurchased(1)) {
                continue;
            }

            $safeQuantity = min((int) $quantity, $game->available_stock);

            if ($safeQuantity <= 0) {
                continue;
            }

            $items[] = [
                'model' => $game,
                'id' => $game->id,
                'title' => $game->title,
                'platform' => $game->platform,
                'condition' => $game->condition,
                'price' => (float) $game->price,
                'quantity' => $safeQuantity,
                'available_stock' => $game->available_stock,
                'subtotal' => round(((float) $game->price) * $safeQuantity, 2),
            ];
        }

        return $items;
    }
}
