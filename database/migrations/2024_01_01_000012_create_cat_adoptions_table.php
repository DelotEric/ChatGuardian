<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cat_adoptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->constrained()->cascadeOnDelete();
            $table->string('adopter_name');
            $table->string('adopter_email')->nullable();
            $table->string('adopter_phone')->nullable();
            $table->string('adopter_address')->nullable();
            $table->date('adopted_at')->nullable();
            $table->decimal('fee', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_adoptions');
    }
};
