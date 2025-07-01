<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'headline',
        'date_event',
        'time_event',
        'details',
        'price',
        'participant_limit',
        'picture',
        'user_id',
        'is_expired',
    ];

    protected $casts = [
        'date_event' => 'datetime',
        'is_expired' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}