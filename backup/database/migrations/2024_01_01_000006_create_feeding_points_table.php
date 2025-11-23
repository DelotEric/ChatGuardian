<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('feeding_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('feeding_point_volunteer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feeding_point_id')->constrained('feeding_points')->cascadeOnDelete();
            $table->foreignId('volunteer_id')->constrained('volunteers')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['feeding_point_id', 'volunteer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feeding_point_volunteer');
        Schema::dropIfExists('feeding_points');
    }
};
