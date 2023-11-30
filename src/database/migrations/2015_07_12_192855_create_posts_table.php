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
        Schema::create('posts', function (Blueprint $table) {
            $table->Uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('CASCADE');
            $table->string('status')->default('draft');
            $table->string('type')->nullable();
            $table->string('mime')->nullable();
            $table->string('title',500)->nullable();
            $table->tinyInteger('pinned')->default('0');
            $table->foreignUuid('parent')->index()->nullable();
            $table->longText('content')->nullable();
            $table->string('slug',500)->index()->nullable();
            $table->string('keyword',500)->nullable();
            $table->string('description',500)->nullable();
            $table->string('thumbnail',500)->nullable();
            $table->string('thumbnail_description',500)->nullable();
            $table->string('url',500)->nullable();
            $table->string('redirect_to',500)->nullable();
            $table->integer('visited')->default(0);
            $table->tinyInteger('allow_comment')->default(0);
            $table->json('data_field')->nullable();
            $table->json('data_loop')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
