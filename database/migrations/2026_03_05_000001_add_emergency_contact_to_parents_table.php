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
        Schema::table('parents', function (Blueprint $table) {
            if (!Schema::hasColumn('parents', 'emergency_contact_name')) {
                $table->string('emergency_contact_name', 255)->nullable()->after('phone');
            }

            if (!Schema::hasColumn('parents', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parents', function (Blueprint $table) {
            if (Schema::hasColumn('parents', 'emergency_contact_phone')) {
                $table->dropColumn('emergency_contact_phone');
            }

            if (Schema::hasColumn('parents', 'emergency_contact_name')) {
                $table->dropColumn('emergency_contact_name');
            }
        });
    }
};
