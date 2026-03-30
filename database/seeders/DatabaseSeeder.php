<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Game;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'pseudo' => 'ShadowFox',
            'email' => 'shadowfox@example.com',
            'password' => 'shadow123',
            'role' => 'admin',
        ]);

        $pixelNinja = User::create([
            'pseudo' => 'PixelNinja',
            'email' => 'pixelninja@example.com',
            'password' => 'pixel123',
            'role' => 'user',
        ]);

        $neoGamer = User::create([
            'pseudo' => 'NeoGamer',
            'email' => 'neogamer@example.com',
            'password' => 'neo12345',
            'role' => 'user',
        ]);

        $games = collect([
            ['title' => 'Elden Ring', 'platform' => 'PC', 'genre' => 'RPG', 'offer_type' => 'location_vente', 'condition' => 'collector', 'price' => 74.99, 'stock_total' => 2],
            ['title' => 'Cyberpunk 2077', 'platform' => 'PlayStation 5', 'genre' => 'RPG', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 69.99, 'stock_total' => 3],
            ['title' => 'Halo Infinite', 'platform' => 'Xbox Series X', 'genre' => 'FPS', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 59.99, 'stock_total' => 5],
            ['title' => 'The Legend of Zelda: Tears of the Kingdom', 'platform' => 'Nintendo Switch', 'genre' => 'Aventure', 'offer_type' => 'location_vente', 'condition' => 'collector', 'price' => 89.99, 'stock_total' => 2],
            ['title' => 'Forza Horizon 5', 'platform' => 'PC', 'genre' => 'Course', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 59.99, 'stock_total' => 4],
            ['title' => 'Baldur\'s Gate 3', 'platform' => 'PlayStation 5', 'genre' => 'RPG', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 69.99, 'stock_total' => 3],
            ['title' => 'Sea of Thieves', 'platform' => 'Xbox Series X', 'genre' => 'Aventure', 'offer_type' => 'location', 'condition' => 'neuf', 'price' => 6.99, 'stock_total' => 4],
            ['title' => 'Mario Kart 8 Deluxe', 'platform' => 'Nintendo Switch', 'genre' => 'Course', 'offer_type' => 'vente', 'condition' => 'occasion', 'price' => 44.99, 'stock_total' => 6],
            ['title' => 'Resident Evil 4', 'platform' => 'PC', 'genre' => 'Horreur', 'offer_type' => 'vente', 'condition' => 'occasion', 'price' => 34.99, 'stock_total' => 4],
            ['title' => 'Marvel\'s Spider-Man 2', 'platform' => 'PlayStation 5', 'genre' => 'Action', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 69.99, 'stock_total' => 5],
            ['title' => 'Animal Crossing: New Horizons', 'platform' => 'Nintendo Switch', 'genre' => 'Simulation', 'offer_type' => 'vente', 'condition' => 'neuf', 'price' => 59.99, 'stock_total' => 6],
            ['title' => 'Alan Wake 2', 'platform' => 'PlayStation 5', 'genre' => 'Horreur', 'offer_type' => 'location', 'condition' => 'neuf', 'price' => 6.99, 'stock_total' => 3],
            ['title' => 'Indiana Jones and the Great Circle', 'platform' => 'Xbox Series X', 'genre' => 'Aventure', 'offer_type' => 'location', 'condition' => 'neuf', 'price' => 7.99, 'stock_total' => 4],
            ['title' => 'Minecraft', 'platform' => 'PC', 'genre' => 'Simulation', 'offer_type' => 'vente', 'condition' => 'occasion', 'price' => 24.99, 'stock_total' => 6],
            ['title' => 'Silent Hill 2', 'platform' => 'PlayStation 5', 'genre' => 'Horreur', 'offer_type' => 'location', 'condition' => 'collector', 'price' => 8.99, 'stock_total' => 2],
            ['title' => 'Pokemon Violet', 'platform' => 'Nintendo Switch', 'genre' => 'RPG', 'offer_type' => 'location_vente', 'condition' => 'neuf', 'price' => 69.99, 'stock_total' => 5],
        ])->map(fn (array $game): Game => Game::create($game))->keyBy('title');

        Borrowing::insert([
            ['game_id' => $games['Elden Ring']->id, 'user_id' => $admin->id, 'borrowed_at' => '2026-03-01', 'returned_at' => null],
            ['game_id' => $games['Halo Infinite']->id, 'user_id' => $pixelNinja->id, 'borrowed_at' => '2026-03-05', 'returned_at' => '2026-03-08'],
            ['game_id' => $games['Animal Crossing: New Horizons']->id, 'user_id' => $neoGamer->id, 'borrowed_at' => '2026-03-07', 'returned_at' => null],
        ]);

        $order = Order::create([
            'user_id' => $pixelNinja->id,
            'total_amount' => 69.98,
            'status' => 'confirmee',
            'ordered_at' => '2026-03-10',
        ]);

        $order->items()->createMany([
            ['game_id' => $games['Resident Evil 4']->id, 'quantity' => 1, 'unit_price' => 34.99],
            ['game_id' => $games['Minecraft']->id, 'quantity' => 1, 'unit_price' => 34.99],
        ]);
    }
}
