<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('short_id')->unique();
            $table->char('icon')->default('');
            $table->string('name', 15);
            $table->string('description', 255)->nullable();
            $table->foreignId('workspace_id')->constrained('workspaces')->cascadeOnDelete();
            $table->boolean('is_private')->default(false);
            $table->boolean('is_dm')->default(false);
            $table->timestamps();
        });

        Schema::create('channel_user', function (Blueprint $table) {
            $table->foreignId('channel_id')->constrained('channels')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['channel_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
