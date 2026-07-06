<?php
// database/migrations/2024_01_01_000002_create_children_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female']);
            $table->string('blood_type')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('children'); }
};
