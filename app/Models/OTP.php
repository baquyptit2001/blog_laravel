<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'otp',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function is_expired()
    {
        return $this->created_at->diffInMinutes() > 5;
    }
}
