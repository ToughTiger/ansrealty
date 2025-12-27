<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            
            $table->date('agreement_date')->nullable();
            $table->string('agreement_number')->nullable();
            $table->decimal('agreement_value', 15, 2)->nullable();
            
            $table->date('registration_date')->nullable();
            $table->string('registration_number')->nullable();
            
            $table->boolean('loan_required')->default(false);
            $table->string('bank_name')->nullable();
            $table->decimal('loan_amount', 15, 2)->nullable();
            $table->date('loan_application_date')->nullable();
            $table->enum('loan_status', ['Applied', 'Approved', 'Disbursed', 'Rejected'])->nullable();
            $table->date('loan_disbursement_date')->nullable();
            
            $table->date('possession_date')->nullable();
            $table->date('handover_date')->nullable();
            
            $table->integer('customer_satisfaction_rating')->nullable()->comment('1-5 stars');
            $table->text('customer_feedback')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_sales');
    }
};
