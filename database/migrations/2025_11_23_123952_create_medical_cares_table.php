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
        Schema::create('medical_cares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['vaccination', 'deworming', 'vet_visit', 'sterilization', 'other'])->default('other');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('care_date');
            $table->date('next_due_date')->nullable();
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('partner_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_cares');
    }
};
