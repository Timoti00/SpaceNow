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
        Schema::create('booking_files', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel room_bookings
            $table->unsignedBigInteger('booking_id');
            $table->string('file_path');

            $table->timestamps();

            // Tambahkan constraint foreign key
            $table->foreign('booking_id')->references('id')->on('room_bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_files');
    }
};