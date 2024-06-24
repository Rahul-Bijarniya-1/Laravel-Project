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
        Schema::create('users2', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('resource_type');
            $table->unsignedBigInteger('resource_id');
            $table->timestamps();

            $table->index(['resource_type', 'resource_id']); // For morph relationships
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users2');
    }
};
