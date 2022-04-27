<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->morphs('likeable');
            $table->timestamp('created_at');

            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    public function down(): void
    {
        Schema::drop('likes');
    }
};
