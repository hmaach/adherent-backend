<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'job_id', 'adherent_id', 'message', 'price_quote', 
        'estimated_days', 'status', 'accepted_at'
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }
}
