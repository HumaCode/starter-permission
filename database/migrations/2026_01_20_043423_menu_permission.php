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
        Schema::create('menu_permission', function (Blueprint $table) {
            $table->id();
            $table->uuid('menu_id')->nullable();
            $table->uuid('permission_id');

            $table->timestamps();

            // Foreign keys
            $table->foreign('menu_id')->references('id')->on('menus')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_permission');
    }
};
