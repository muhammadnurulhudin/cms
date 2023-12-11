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
        Schema::table('visitors', function (Blueprint $table) {
            $table->index('session');
            $table->index('page');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->index('session');
            $table->index('page');
                    });
    }
};
