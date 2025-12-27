<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('listed_price', 15, 2);
            $table->decimal('offered_price', 15, 2);
            $table->decimal('counter_offer_price', 15, 2)->nullable();
            $table->decimal('final_agreed_price', 15, 2)->nullable();
            
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            
            $table->boolean('discount_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            
            $table->decimal('booking_amount', 15, 2)->nullable();
            $table->date('booking_date')->nullable();
            
            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
