<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'client_id', 'secteur_id', 'title', 'description', 
        'budget_min', 'budget_max', 'city', 'status', 
        'expires_at', 'closed_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
