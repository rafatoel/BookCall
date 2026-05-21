<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Booking extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_email',
        'title',
        'duration',
        'description',
        'meeting_link',
        'date',
        'start_time',
        'end_time',
        'confirmed',
        'complete',
        'canceled',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
