<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('status');
            $table->string('recurrence_pattern')->nullable()->after('is_recurring'); // daily, weekly, monthly, yearly
            $table->integer('recurrence_interval')->default(1)->after('recurrence_pattern'); // Every X days/weeks/months
            $table->json('recurrence_days')->nullable()->after('recurrence_interval'); // For weekly: [1,3,5] = Mon, Wed, Fri
            $table->date('recurrence_end_date')->nullable()->after('recurrence_days');
            $table->unsignedBigInteger('parent_task_id')->nullable()->after('recurrence_end_date');
            $table->foreign('parent_task_id')->references('id')->on('tasks')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['parent_task_id']);
            $table->dropColumn([
                'is_recurring',
                'recurrence_pattern',
                'recurrence_interval',
                'recurrence_days',
                'recurrence_end_date',
                'parent_task_id',
            ]);
        });
    }
};
