<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id(); // Automatically creates an 'id' column as INT AUTO_INCREMENT
            $table->string('case_number'); // VARCHAR(255) for case_number
            $table->text('title'); // TEXT for title
            $table->string('date'); // VARCHAR(255) for date
            $table->text('link'); // TEXT for link
            $table->text('content')->nullable(); // TEXT for content, nullable as it's optional
            $table->timestamps(); // Automatically adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
