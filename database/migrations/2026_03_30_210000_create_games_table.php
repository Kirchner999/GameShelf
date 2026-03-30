<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('platform', 100);
            $table->string('genre', 100);
            $table->enum('offer_type', ['location', 'vente', 'location_vente'])->default('location');
            $table->enum('condition', ['neuf', 'occasion', 'collector'])->default('neuf');
            $table->decimal('price', 10, 2)->default(9.99);
            $table->unsignedInteger('stock_total')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
