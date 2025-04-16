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
        Schema::create('activities', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->integer('sem')->nullable();
            $table->integer('year')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->text('remark')->nullable();
            $table->string('uploads')->nullable();
            $table->foreignId('student_id')->nullable()->constrained()->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
