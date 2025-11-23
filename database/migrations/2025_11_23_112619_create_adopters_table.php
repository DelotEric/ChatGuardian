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
        Schema::create('adopters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('cats', function (Blueprint $table) {
            $table->foreignId('adopter_id')->nullable()->after('status')->constrained()->nullOnDelete();
            $table->date('adopted_at')->nullable()->after('adopter_id');
        });
    }

    public function down(): void
    {
        Schema::table('cats', function (Blueprint $table) {
            $table->dropForeign(['adopter_id']);
            $table->dropColumn(['adopter_id', 'adopted_at']);
        });

        Schema::dropIfExists('adopters');
    }
};
