<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create communication_templates first (referenced table)
        Schema::create('communication_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // email, whatsapp, sms
            $table->string('category')->nullable(); // lead_followup, booking_confirmation, etc.
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('variables')->nullable(); // {customer_name}, {property_name}, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Then create communications table (referencing table)
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->string('communication_type'); // email, whatsapp, sms, call
            $table->string('direction'); // inbound, outbound
            $table->morphs('communicable'); // lead, opportunity, booking, etc. - This already creates index
            $table->unsignedBigInteger('user_id')->nullable(); // who initiated
            $table->string('recipient_type')->nullable(); // email, phone
            $table->string('recipient')->nullable(); // actual email/phone
            $table->string('subject')->nullable();
            $table->text('message');
            $table->string('status')->default('sent'); // sent, delivered, failed, read
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable(); // provider response, message ID, etc.
            $table->unsignedBigInteger('template_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('template_id')->references('id')->on('communication_templates')->onDelete('set null');
            
            // Removed duplicate index - morphs() already creates it
            $table->index('communication_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('communications');
        Schema::dropIfExists('communication_templates');
    }
};
