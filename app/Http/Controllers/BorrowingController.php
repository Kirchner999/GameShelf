<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Borrowing::query()
            ->with(['game', 'user'])
            ->latest('id');

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        return view('borrowings.index', [
            'borrowings' => $query->get(),
            'isAdmin' => $request->user()->isAdmin(),
        ]);
    }

    public function create(Request $request): View
    {
        $activeBorrowCount = Borrowing::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('returned_at')
            ->count();

        $games = Game::query()
            ->withCount('activeBorrowings')
            ->get()
            ->filter(fn (Game $game): bool => $game->canBeBorrowed())
            ->values();

        return view('borrowings.create', [
            'games' => $games,
            'activeBorrowCount' => $activeBorrowCount,
            'canCreateBorrowing' => $activeBorrowCount < 2,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'game_id' => ['required', 'integer', 'exists:games,id'],
            'borrowed_at' => ['required', 'date'],
        ]);

        $activeBorrowCount = Borrowing::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('returned_at')
            ->count();

        abort_if($activeBorrowCount >= 2, 422, 'Limite atteinte: 2 locations actives maximum.');

        $game = Game::query()->withCount('activeBorrowings')->findOrFail($data['game_id']);
        abort_unless($game->canBeBorrowed(), 422, 'Stock indisponible pour ce jeu.');

        Borrowing::create([
            'game_id' => $game->id,
            'user_id' => $request->user()->id,
            'borrowed_at' => $data['borrowed_at'],
        ]);

        return redirect()->route('borrowings.index')->with('status', 'Location enregistree.');
    }

    public function markReturned(Request $request, Borrowing $borrowing): RedirectResponse
    {
        $user = $request->user();

        abort_if(
            ! $user->isAdmin() && ($borrowing->user_id !== $user->id || $borrowing->returned_at !== null),
            403
        );

        $borrowing->update([
            'returned_at' => now()->toDateString(),
        ]);

        return redirect()->route('borrowings.index')->with('status', 'Retour enregistre.');
    }
}
