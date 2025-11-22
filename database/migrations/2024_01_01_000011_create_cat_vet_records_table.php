<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cat_vet_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cat_id')->constrained('cats')->cascadeOnDelete();
            $table->date('visit_date');
            $table->string('clinic_name')->nullable();
            $table->string('reason');
            $table->decimal('amount', 8, 2)->default(0);
            $table->string('document_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cat_vet_records');
    }
};
