<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignment_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['round_robin', 'location', 'source', 'load_balance', 'priority'])->default('round_robin');
            $table->boolean('is_active')->default(true);
            $table->integer('priority_order')->default(0);
            $table->json('conditions')->nullable(); // Store conditions like locations, sources, etc.
            $table->json('assigned_users')->nullable(); // Array of user IDs for this rule
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Track which agent was last assigned (for round-robin)
        Schema::create('assignment_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('last_assigned_user_id')->nullable();
            $table->integer('assignment_count')->default(0);
            $table->timestamp('last_assigned_at')->nullable();
            $table->timestamps();
            
            $table->foreign('rule_id')->references('id')->on('assignment_rules')->onDelete('cascade');
            $table->foreign('last_assigned_user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Add tracking columns to leads table
        Schema::table('leads', function (Blueprint $table) {
            $table->timestamp('last_activity_at')->nullable()->after('converted_at');
            $table->integer('interaction_count')->default(0)->after('last_activity_at');
            $table->boolean('is_stale')->default(false)->after('interaction_count');
            $table->timestamp('marked_stale_at')->nullable()->after('is_stale');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['last_activity_at', 'interaction_count', 'is_stale', 'marked_stale_at']);
        });
        
        Schema::dropIfExists('assignment_counters');
        Schema::dropIfExists('assignment_rules');
    }
};
