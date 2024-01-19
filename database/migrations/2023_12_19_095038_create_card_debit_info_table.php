<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('card_debit_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('card_id');
            $table->string('card_number');
            $table->decimal('USD', 10, 2);
            $table->string('by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_debit_info');
    }
};
