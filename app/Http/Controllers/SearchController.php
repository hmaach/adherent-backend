<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Poste;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->input('query');
        $stagiaires = DB::table('users')
            ->join('groupes', 'users.id_groupe', '=', 'groupes.id')
            ->join('filieres', 'groupes.id_filiere', '=', 'filieres.id')
            ->select('users.id', 'users.nom', 'users.prenom', 'filieres.libelle')
            ->where('users.nom', 'like', '%' . $query . '%')
            ->orWhere('users.prenom', 'like', '%' . $query . '%')
            ->limit(2)
            ->get();

        $users = DB::table('users')
            ->select('users.id', 'users.nom', 'users.prenom')
            ->whereNull('id_groupe')
            ->where('users.nom', 'like', '%' . $query . '%')
            ->orWhere('users.prenom', 'like', '%' . $query . '%')
            ->limit(2)
            ->get();

        $posts = DB::table('postes')
            ->join('users', 'users.id', '=', 'postes.id_user')
            ->leftJoin('reacts', 'reacts.id_poste', '=', 'postes.id')
            ->select(
                'postes.id as id',
                'users.id as user_id',
                'users.nom',
                'users.prenom',
                'users.role',
                'postes.libelle',
                'postes.type',
                'postes.audience',
                'postes.created_at',
                DB::raw('coalesce(count(reacts.id_poste), 0) as reacts'),
                'reacts.id_user as liked'
            )
            ->where('users.nom', 'like', '%' . $query . '%')
            ->orWhere('users.prenom', 'like', '%' . $query . '%')
            ->orWhere('postes.libelle', 'like', '%' . $query . '%')
            ->groupBy('postes.id', 'reacts.id_user')
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'stagiaires' => $stagiaires,
            'users' => $users,
            'posts'=>$posts
        ];
    }
}
