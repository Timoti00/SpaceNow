<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingFile extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function booking()
    {
        return $this->belongsTo(RoomBookings::class, 'room_booking_id');
    }
}