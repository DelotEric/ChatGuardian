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
        Schema::table('cats', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('name');
        });

        Schema::create('cat_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_photos');

        Schema::table('cats', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }
};
