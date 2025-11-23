<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('sex', ['male', 'female', 'unknown'])->default('unknown');
            $table->date('birthdate')->nullable();
            $table->string('status')->default('free');
            $table->boolean('sterilized')->default(false);
            $table->date('sterilized_at')->nullable();
            $table->boolean('vaccinated')->default(false);
            $table->date('vaccinated_at')->nullable();
            $table->enum('fiv_status', ['unknown', 'positive', 'negative'])->default('unknown');
            $table->enum('felv_status', ['unknown', 'positive', 'negative'])->default('unknown');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cats');
    }
};
