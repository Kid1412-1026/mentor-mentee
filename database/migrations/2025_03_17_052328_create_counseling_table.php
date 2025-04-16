<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('counselings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->noActionOnDelete();
            $table->foreignId('admin_id')->constrained()->noActionOnDelete();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('duration');
            $table->string('venue');
            $table->text('description')->nullable();
            $table->string('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('counselings');
    }
};


