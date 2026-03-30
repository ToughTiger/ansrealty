<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['Admin', 'Manager', 'Employee', 'Telecaller'])->default('Employee')->after('email');
            $table->string('mobile', 15)->nullable()->after('email');
            $table->string('employee_code')->nullable()->unique()->after('email');
            $table->date('joining_date')->nullable()->after('email');
            $table->enum('status', ['Active', 'Inactive'])->default('Active')->after('email');
            $table->decimal('target_monthly', 12, 2)->nullable()->after('email');
            $table->foreignId('reports_to')->nullable()->constrained('users')->onDelete('set null')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'mobile', 'employee_code', 'joining_date', 'status', 'target_monthly', 'reports_to']);
        });
    }
};
