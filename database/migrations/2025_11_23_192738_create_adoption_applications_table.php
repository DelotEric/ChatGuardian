<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('adoption_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('applicant_name');
            $table->string('applicant_email');
            $table->string('applicant_phone');
            $table->string('applicant_address')->nullable();
            $table->enum('housing_type', ['house', 'apartment'])->default('apartment');
            $table->boolean('has_garden')->default(false);
            $table->string('family_composition')->nullable();
            $table->string('other_pets')->nullable();
            $table->text('motivation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_applications');
    }
};
