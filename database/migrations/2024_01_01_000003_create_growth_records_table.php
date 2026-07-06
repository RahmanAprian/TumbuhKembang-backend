<?php
// database/migrations/2024_01_01_000003_create_growth_records_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('growth_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 5, 2)->comment('kg');
            $table->decimal('height', 5, 2)->comment('cm');
            $table->decimal('head_circumference', 5, 2)->nullable()->comment('cm');
            $table->integer('age_months');
            $table->date('recorded_at');
            $table->enum('nutritional_status', ['gizi_buruk','gizi_kurang','normal','gizi_lebih','obesitas'])->default('normal');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('growth_records'); }
};
