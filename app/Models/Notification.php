<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }

    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }
}
