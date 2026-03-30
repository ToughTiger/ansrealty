<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('assigned_to')->constrained()->onDelete('set null');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('assigned_to')->constrained()->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
