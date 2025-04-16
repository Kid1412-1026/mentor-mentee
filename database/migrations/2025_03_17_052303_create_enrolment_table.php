<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('enrolments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained()->noActionOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->noActionOnDelete();
            $table->integer('sem')->nullable();
            $table->integer('year')->nullable();
            $table->decimal('pointer', 4, 2)->default(0.00);
            $table->string('grade', 3)->default('A');
            $table->tinyInteger('rating')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enrolments');
    }
};
