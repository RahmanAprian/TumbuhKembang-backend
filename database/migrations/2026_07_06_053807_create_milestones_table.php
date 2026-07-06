<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained()->onDelete('cascade');
            $table->integer('month');
            $table->boolean('is_achieved')->default(false);
            $table->date('achieved_date')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('milestones'); }
};