<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunity_property', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_shortlisted')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['opportunity_id', 'property_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunity_property');
    }
};
