<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
}
