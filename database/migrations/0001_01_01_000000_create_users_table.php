<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('student_id')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('faculty')->nullable();
            $table->string('major')->nullable();
            $table->enum('role', ['mahasiswa', 'teknisi', 'admin'])->default('mahasiswa');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};