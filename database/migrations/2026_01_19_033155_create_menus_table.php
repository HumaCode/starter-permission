<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
            $table->enum('level', ['menu', 'submenu', 'childmenu'])->default('menu');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('route')->nullable();
            $table->string('icon')->nullable(); // lucide icon name
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // untuk badge, description, dll
            $table->timestamps();

            $table->index(['parent_id', 'order']);
            $table->index('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
