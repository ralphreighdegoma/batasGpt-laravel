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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        //check if profiles table exists
        if (Schema::hasTable('profiles')) {
            Schema::dropIfExists('profiles');
        }

        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');

        //remove members_test table
        if (Schema::hasTable('members_test')) {
            Schema::dropIfExists('members_test');
        }

        //messenger_threads
        if (Schema::hasTable('messenger_threads')) {
            Schema::dropIfExists('messenger_threads');
        }

        //migrations
        if (Schema::hasTable('migrations')) {
            Schema::dropIfExists('migrations');
        }

        //otp_codes
        if (Schema::hasTable('otp_codes')) {
            Schema::dropIfExists('otp_codes');
        }
    }
};
