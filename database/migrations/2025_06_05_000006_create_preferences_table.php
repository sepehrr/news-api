<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('preferable');
            $table->timestamps();

            // Ensure a user can't have duplicate preferences for the same item
            $table->unique(['user_id', 'preferable_id', 'preferable_type'], 'unique_user_preference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferences');
    }
};
