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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('ip')->nullable();
            $table->json('ip_location')->nullable();
            $table->string('browser')->nullable();
            $table->string( 'session')->nullable();
            $table->string( 'device')->nullable();
            $table->string( 'os')->nullable();
            $table->string( 'page')->nullable();
            $table->date( 'date')->default(date('Y-m-d'));
            $table->string( 'reference')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
