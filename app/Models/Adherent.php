<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'propos',
        'profession',
        'ville',
        'secteur_id',
        'subscription_status',
        'subscription_end_date',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'paid_at',
        'payment_admin_notes',
    ];

    protected $casts = [
        'subscription_end_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function secteur()
    {
        return $this->belongsTo(Secteur::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function announces()
    {
        return $this->hasMany(Announce::class);
    }

    public function rating()
    {
        return $this->hasMany(Rating::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }
}
