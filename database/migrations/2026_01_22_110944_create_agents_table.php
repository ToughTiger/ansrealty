<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('agent_code')->unique()->nullable();
            $table->enum('agent_type', ['Internal', 'External'])->default('External');
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->unique();
            $table->string('mobile', 15);
            $table->string('alternate_mobile', 15)->nullable();
            $table->string('pan_number', 10)->nullable();
            $table->string('aadhar_number', 12)->nullable();
            $table->string('rera_number')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->decimal('commission_percentage', 5, 2)->default(1.00);
            $table->enum('commission_type', ['Percentage', 'Fixed'])->default('Percentage');
            $table->decimal('fixed_commission', 10, 2)->nullable();
            $table->foreignId('assigned_employee_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['Active', 'Inactive', 'Suspended'])->default('Active');
            $table->date('joining_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('documents')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
