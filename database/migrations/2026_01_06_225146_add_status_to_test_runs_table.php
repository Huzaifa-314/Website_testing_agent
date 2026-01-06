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
        Schema::table('test_runs', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('test_case_id'); // pending, running, completed, failed
            $table->integer('current_step')->nullable()->after('status');
            $table->integer('total_steps')->nullable()->after('current_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test_runs', function (Blueprint $table) {
            $table->dropColumn(['status', 'current_step', 'total_steps']);
        });
    }
};
