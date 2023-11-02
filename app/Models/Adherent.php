<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adherent extends Model
{
    use HasFactory;

    protected $fillable = ['propos', 'profession', 'ville', 'secteur_id'];

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
}
