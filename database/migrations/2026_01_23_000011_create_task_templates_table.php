<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('task_type');
            $table->string('priority')->default('Medium');
            $table->integer('default_duration_hours')->default(24);
            $table->unsignedBigInteger('default_assigned_to')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('checklist_items')->nullable();
            $table->timestamps();

            $table->foreign('default_assigned_to')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_templates');
    }
};
