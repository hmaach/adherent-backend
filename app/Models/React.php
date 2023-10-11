<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class React extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_poste',
        'unique_combination',
    ];
    public function rules()
    {
        return [
            'poste_id' => 'required|exists:users,id|unique:reacts,user_id,NULL,poste_id,' . $this->poste_id,
            'user_id' => 'required|exists:postes,id|unique:reacts,poste_id,NULL,user_id,' . $this->user_id,
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }
}
