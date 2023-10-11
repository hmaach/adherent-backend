<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
    protected $searchable = [];
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reacts()
    {
        return $this->hasMany(React::class);
    }


    public function photo()
    {
        return $this->hasMany(Photo::class);
    }

    public function pdf()
    {
        return $this->hasOne(PDF::class);
    }



}
