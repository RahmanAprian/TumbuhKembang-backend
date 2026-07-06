<?php
// database/migrations/2024_01_01_000004_create_vaccines_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_name');
            $table->integer('recommended_age_months');
            $table->date('given_date')->nullable();
            $table->boolean('is_done')->default(false);
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('vaccines'); }
};
