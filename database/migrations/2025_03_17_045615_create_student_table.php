<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id(); // Set as primary key (not auto-incrementing)
            $table->string('matric_no', 15)->unique();
            $table->string('name', 100)->nullable();
            $table->string('program', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('intake')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('motto', 255)->nullable();
            $table->string('faculty', 255)->nullable();
            $table->string('img', 255)->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->noActionOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained()->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
