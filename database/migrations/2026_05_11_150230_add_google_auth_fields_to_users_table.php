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
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')
                    ->nullable()
                    ->unique()
                    ->after('id');
            }

            if (! Schema::hasColumn('users', 'google_avatar_url')) {
                if (Schema::hasColumn('users', 'profile_photo_path')) {
                    $table->string('google_avatar_url')
                        ->nullable()
                        ->after('profile_photo_path');
                } else {
                    $table->string('google_avatar_url')
                        ->nullable()
                        ->after('email');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropUnique(['google_id']);
                $table->dropColumn('google_id');
            }

            if (Schema::hasColumn('users', 'google_avatar_url')) {
                $table->dropColumn('google_avatar_url');
            }
        });
    }
};
