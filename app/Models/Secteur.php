<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;
    protected $fillable = ['fillable '];

    public function adherents()
    {
        return $this->hasMany(Adherent::class);
    }
}
