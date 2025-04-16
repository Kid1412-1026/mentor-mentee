<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->decimal('cgpa', 4, 2)->default(0.00);
            $table->integer('faculty_activity')->default(0);
            $table->integer('university_activity')->default(0);
            $table->integer('national_activity')->default(0);
            $table->integer('interaction')->default(0);
            $table->integer('faculty_competition')->default(0);
            $table->integer('university_competition')->default(0);
            $table->integer('national_competition')->default(0);
            $table->integer('interaction_competition')->default(0);
            $table->integer('leadership_competition')->default(0);
            $table->integer('graduate_on_time')->default(0);
            $table->integer('professional_certification')->default(0);
            $table->integer('employability')->default(0);
            $table->integer('mobility_program')->default(0);
            $table->foreignId('student_id')->nullable()->constrained()->noActionOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('results');
    }
};
