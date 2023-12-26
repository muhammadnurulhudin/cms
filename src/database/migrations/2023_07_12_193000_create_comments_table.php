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
        Schema::create('comments', function (Blueprint $table) {
            $table->Uuid('id')->primary();
            $table->foreignUuid('post_id')->constrained()->onDelete('CASCADE');
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('CASCADE')->nullable();
            $table->foreignUuid('parent')->nullable();
            $table->string('name')->nullable();
            $table->longText('content')->nullable();
            $table->string('email')->nullable();
            $table->string('link')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
