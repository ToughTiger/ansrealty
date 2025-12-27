<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('opportunity_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            
            $table->dateTime('scheduled_at');
            $table->dateTime('completed_at')->nullable();
            
            $table->enum('status', ['Planned', 'Confirmed', 'Completed', 'Cancelled', 'No Show'])->default('Planned');
            $table->text('customer_feedback')->nullable();
            $table->integer('customer_rating')->nullable()->comment('1-5 stars');
            
            $table->text('agent_notes')->nullable();
            $table->boolean('follow_up_required')->default(true);
            $table->date('follow_up_date')->nullable();
            
            $table->string('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'scheduled_at']);
            $table->index(['assigned_to', 'scheduled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
