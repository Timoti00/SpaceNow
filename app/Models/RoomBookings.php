<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBookings extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
    public function files()
    {
        return $this->hasMany(BookingFile::class, 'booking_id');
    }
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}