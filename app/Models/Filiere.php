<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'libelle',
        'niveau'
    ];

    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }


}
