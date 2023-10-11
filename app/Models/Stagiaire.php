<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stagiaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_inscriptionsessionprogramme',
        'MatriculeEtudiant',
        'Nom',
        'Prenom',
        'Sexe',
        'EtudiantActif',
        'diplome',
        'Principale',
        'LibelleLong',
        'CodeDiplome',
        'Code',
        'EtudiantPayant',
        'codediplome1',
        'prenom2',
        'DateNaissance',
        'Site',
        'Regimeinscription',
        'DateInscription',
        'DateDossierComplet',
        'LieuNaissance',
        'MotifAdmission',
        'CIN',
        'NTelelephone',
        'NTel_du_Tuteur',
        'Adresse',
        'Nationalite',
        'anneeEtude',
        'Nom_Arabe',
        'Prenom_arabe',
        'NiveauScolaire',
    ];
}
