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
            $table->text('prescription')->nullable()->after('notes');
            $table->string('dosage')->nullable()->after('prescription');
            $table->string('duration')->nullable()->after('dosage');
            $table->decimal('weight_at_visit', 5, 2)->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_cares', function (Blueprint $table) {
            $table->dropColumn(['prescription', 'dosage', 'duration', 'weight_at_visit']);
        });
    }
};
