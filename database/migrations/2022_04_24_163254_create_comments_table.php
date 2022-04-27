<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Post::class)
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('body');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->index()
                ->nullable()
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
