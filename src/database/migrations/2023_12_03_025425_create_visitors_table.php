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
        // protected $fillable = ['ip','date','time','os','browser','session','device','page','reference','last_activity','ip_location'];
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->integer('last_activity');
            $table->ipAddress('ip');
            $table->json('ip_location');
            $table->string('browser');
            $table->string( 'session');
            $table->string( 'device');
            $table->string( 'os');
            $table->string( 'page');
            $table->date( 'date');
            $table->string( 'reference');
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
