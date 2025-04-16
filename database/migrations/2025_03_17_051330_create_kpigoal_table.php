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
        Schema::create('kpi_goals', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->decimal('cgpa', 4, 2)->default(0.00);
            $table->integer('faculty_activity')->default(0);
            $table->integer('university_activity')->default(0);
            $table->integer('national_activity')->default(0);
            $table->integer('international_activity')->default(0);
            $table->integer('faculty_competition')->default(0);
            $table->integer('university_competition')->default(0);
            $table->integer('national_competition')->default(0);
            $table->integer('international_competition')->default(0);
            $table->integer('leadership')->default(0);
            $table->string('graduate_on_time', 50)->nullable();
            $table->integer('professional_certification')->default(0);
            $table->string('employability')->nullable();
            $table->integer('mobility_program')->default(0);
            $table->foreignId('admin_id')->nullable()->constrained()->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('kpi_goals');
    }
};
