<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));

        $games = Game::query()
            ->withCount('activeBorrowings')
            ->search($search)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('games.index', compact('games', 'search'));
    }

    public function search(Request $request): JsonResponse
    {
        $search = trim((string) $request->string('q'));

        if ($search === '') {
            return response()->json([]);
        }

        $results = Game::query()
            ->withCount('activeBorrowings')
            ->search($search)
            ->orderBy('title')
            ->limit(10)
            ->get()
            ->map(fn (Game $game): array => [
                'id' => $game->id,
                'title' => $game->title,
                'platform' => $game->platform,
                'genre' => $game->genre,
                'offer_type' => $game->offer_type,
                'condition' => $game->condition,
                'price' => $game->price,
                'available_stock' => $game->available_stock,
            ]);

        return response()->json($results);
    }

    public function create(): View
    {
        return view('games.form', [
            'game' => new Game(),
            'action' => route('admin.games.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Game::create($this->validatedData($request));

        return redirect()->route('games.index')->with('status', 'Jeu ajoute au catalogue.');
    }

    public function edit(Game $game): View
    {
        $game->loadCount('activeBorrowings');

        return view('games.form', [
            'game' => $game,
            'action' => route('admin.games.update', $game),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Game $game): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['stock_total'] = max($data['stock_total'], $game->activeBorrowings()->count());

        $game->update($data);

        return redirect()->route('games.index')->with('status', 'Jeu mis a jour.');
    }

    public function destroy(Game $game): RedirectResponse
    {
        $game->delete();

        return redirect()->route('games.index')->with('status', 'Jeu supprime.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'platform' => ['required', 'string', 'max:100'],
            'genre' => ['required', 'string', 'max:100'],
            'offer_type' => ['required', 'in:location,vente,location_vente'],
            'condition' => ['required', 'in:neuf,occasion,collector'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_total' => ['required', 'integer', 'min:0'],
        ]);
    }
}
