<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('sem');
            $table->tinyInteger('year');
            $table->string('batch',9);
            $table->date('session_date');
            $table->enum('method', ['face-to-face', 'online']);
            $table->integer('duration');
            $table->text('agenda');
            $table->text('discussion');
            $table->text('action');
            $table->text('remarks')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained()->noActionOnDelete();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetings');
    }
};
