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
        Schema::create('groups', function (Blueprint $table) {
            $table->Uuid('id')->primary();
            $table->string('type')->index();
            $table->string('url');
            $table->tinyInteger('status')->default(0);
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('slug')->index();
            $table->tinyInteger('sort')->default('0');
            $table->timestamps();

        });
        Schema::create('group_post', function (Blueprint $table) {
            $table->Uuid('id')->primary();
            $table->foreignUuid('post_id')->index()->constrained()->onDelete('CASCADE');
            $table->foreignUuid('group_id')->index()->constrained()->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
