<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('GameShelf');
    }

    public function test_the_catalog_page_renders_a_game_that_can_be_added_to_cart(): void
    {
        Game::create([
            'title' => 'Test Game',
            'platform' => 'PC',
            'genre' => 'Action',
            'offer_type' => 'vente',
            'condition' => 'neuf',
            'price' => 49.99,
            'stock_total' => 3,
        ]);

        $response = $this->get('/jeux');

        $response
            ->assertOk()
            ->assertSee('Test Game')
            ->assertSee('Ajouter au panier');
    }

    public function test_auth_pages_are_accessible_to_guests(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }
}