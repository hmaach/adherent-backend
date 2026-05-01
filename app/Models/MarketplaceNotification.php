<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'title', 'message', 'is_read', 'related_type', 'related_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
