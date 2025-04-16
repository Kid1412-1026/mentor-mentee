<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('course_id')->nullable()->constrained()->noActionOnDelete();
            $table->foreignId('programme_id')->nullable()->constrained()->noActionOnDelete();
            $table->integer('intake')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rules');
    }
};
