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
        Schema::create('jurisprudences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('reference_number')->unique();
            $table->date('decision_date');
            $table->string('court');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurisprudences');
    }
};
