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
        Schema::table('user_audit_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('user_audit_logs', 'user_email')) {
                $table->string('user_email')->nullable()->after('user_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_audit_logs', function (Blueprint $table) {
            if (Schema::hasColumn('user_audit_logs', 'user_email')) {
                $table->dropColumn('user_email');
            }
        });
    }
};
