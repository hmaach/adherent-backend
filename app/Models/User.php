<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'nom',
        'prenom',
        'email',
        'tel',
        'sex',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function search($query)
    {
        $users = $this->where('name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->get();

        foreach ($users as $user) {
            $postes = $user->postes()->where('title', 'like', "%$query%")->get();
            $evenements = $user->evenements()->where('title', 'like', "%$query%")->get();
        }
        dd($users);
        //return [$users, $postes, $evenements];
    }

    public function postes()
    {
        return $this->hasMany(Poste::class);
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }

    public function reacts()
    {
        return $this->hasMany(React::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }


    public function cv()
    {
        return $this->hasOne(CV::class);
    }

    public function competences()
    {
        return $this->hasMany(Competence::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    public function formations()
    {
        return $this->hasMany(Formation::class);
    }

    public function interets()
    {
        return $this->hasMany(Interet::class);
    }
    public function pdfCategories()
    {
        return $this->hasMany(PdfCategorie::class);
    }
}
