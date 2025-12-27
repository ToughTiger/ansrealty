<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            
            $table->morphs('taskable');
            
            $table->enum('type', ['Call', 'Email', 'Meeting', 'Site Visit', 'WhatsApp', 'Follow Up', 'Other'])->default('Call');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])->default('Medium');
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Cancelled'])->default('Pending');
            
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->dateTime('due_date');
            $table->dateTime('completed_at')->nullable();
            
            $table->text('completion_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['assigned_to', 'status', 'due_date']);
            // morphs() already creates the taskable_type, taskable_id index
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
