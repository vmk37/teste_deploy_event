<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//model adress
class Address extends Model
{
    protected $fillable = [
        'event_id',
        'cep',
        'street',
        'addressNumber',
        'neighborhood',
        'municipality',
        'state'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
}
