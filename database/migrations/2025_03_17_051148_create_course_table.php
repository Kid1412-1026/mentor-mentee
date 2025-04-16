<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->integer('credit_hour')->default(0);
            $table->enum('section', ['Faculty Core', 'Programme Core', 'Elective','University Core','Co-curriculum','Language','Industrial Training']);
            $table->enum('faculty', ['Faculty of Computing and Informatics(FKI)', 'Knowledge and Language Learning(PPIB)', 'Co-Curricular(PKPP)']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
