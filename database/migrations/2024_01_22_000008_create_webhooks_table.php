<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['meta', 'google', 'api', 'custom'])->default('custom');
            $table->string('endpoint')->unique();
            $table->string('verify_token')->nullable();
            $table->enum('status', ['active', 'inactive', 'testing'])->default('active');
            $table->text('description')->nullable();
            $table->integer('total_calls')->default(0);
            $table->integer('successful_calls')->default(0);
            $table->integer('failed_calls')->default(0);
            $table->timestamp('last_called_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhooks');
    }
};
