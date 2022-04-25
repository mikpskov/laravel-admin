<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->boolean('direction');
            $table->unsignedBigInteger('voteable_id');
            $table->string('voteable_type', 255);
            $table->timestamp('created_at');

            $table->index(['voteable_id', 'voteable_type']);
            $table->unique(['user_id', 'voteable_id', 'voteable_type']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
