<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number')->unique()->nullable();
            $table->foreignId('opportunity_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_lead_id')->constrained('leads')->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->enum('booking_stage', [
                'Token Received',
                'Token Confirmed',
                'Agreement Pending',
                'Agreement Signed',
                'Payment Plan Active',
                'Registration Pending',
                'Registration Done',
                'Possession Pending',
                'Possession Done',
                'Completed'
            ])->default('Token Received');
            $table->decimal('property_value', 12, 2);
            $table->decimal('token_amount', 12, 2)->nullable();
            $table->date('token_date')->nullable();
            $table->decimal('booking_amount', 12, 2)->nullable();
            $table->date('booking_date')->nullable();
            $table->decimal('agreement_value', 12, 2)->nullable();
            $table->date('agreement_date')->nullable();
            $table->string('agreement_number')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('possession_date')->nullable();
            $table->decimal('agent_commission_percentage', 5, 2)->nullable();
            $table->decimal('agent_commission_amount', 12, 2)->nullable();
            $table->enum('commission_status', ['Pending', 'Calculated', 'Approved', 'Paid'])->default('Pending');
            $table->decimal('commission_paid', 12, 2)->default(0);
            $table->date('commission_paid_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('invoice_generated')->default(false);
            $table->string('invoice_number')->nullable();
            $table->text('notes')->nullable();
            $table->json('payment_milestones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
