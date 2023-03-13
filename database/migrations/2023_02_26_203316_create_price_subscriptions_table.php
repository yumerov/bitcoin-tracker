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
        Schema::create('price_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->decimal('price', 8, 2);
            $table->decimal('deactivated_at', 8, 2)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['email', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_subscriptions');
    }
};
