<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('origin')->nullable();
            $table->enum('roast_level', ['light', 'medium', 'dark'])->default('medium');
            $table->enum('grind_type', ['whole_bean', 'coarse', 'medium', 'fine', 'extra_fine'])->default('whole_bean');
            $table->string('sku')->unique()->nullable();
            $table->decimal('price_b2c', 10, 2);
            $table->decimal('price_b2b', 10, 2);
            $table->unsignedInteger('min_wholesale_qty')->default(10);
            $table->unsignedInteger('stock_qty')->default(0);
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
