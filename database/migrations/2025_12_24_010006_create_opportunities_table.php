<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('opportunity_number')->unique()->nullable();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('opportunity_stage_id')->nullable()->constrained()->nullOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->decimal('expected_value', 15, 2)->nullable();
            $table->integer('probability')->default(0)->comment('0-100');
            $table->date('expected_close_date')->nullable();
            
            $table->decimal('final_value', 15, 2)->nullable();
            $table->date('actual_close_date')->nullable();
            
            $table->enum('close_status', ['Open', 'Won', 'Lost'])->default('Open');
            $table->string('lost_reason')->nullable();
            $table->text('lost_remarks')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['opportunity_stage_id', 'assigned_to']);
            $table->index(['close_status', 'expected_close_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
