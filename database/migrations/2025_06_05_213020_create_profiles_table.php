<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('phone_number', 20)->nullable();

            // Staff & Dosen
            $table->integer('employee_id')->nullable();
            $table->string('position')->nullable();
            $table->string('division')->nullable();   // Staff only
            $table->string('degree')->nullable();     // Dosen only

            // Mahasiswa (user)
            $table->integer('nrp')->nullable();
            $table->string('faculty')->nullable();
            $table->string('study_program')->nullable();
            $table->string('batch_year', 10)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};