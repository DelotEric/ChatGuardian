<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_cares', function (Blueprint $table) {
            $table->string('responsible_type')->nullable()->after('partner_id');
            $table->unsignedBigInteger('responsible_id')->nullable()->after('responsible_type');
        });
    }

    public function down(): void
    {
        Schema::table('medical_cares', function (Blueprint $table) {
            $table->dropColumn(['responsible_type', 'responsible_id']);
        });
    }
};
