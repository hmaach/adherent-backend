<?php

namespace App\Imports;

use App\Models\Stagiaire;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StagiaireImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return Stagiaire::create([
            'id_inscriptionsessionprogramme' => $row['id_inscriptionsessionprogramme'],
            'MatriculeEtudiant' => $row['MatriculeEtudiant'],
            'Nom' => $row['Nom'],
            'Prenom' => $row['Prenom'],
            'Sexe' => $row['Sexe'],
            'EtudiantActif' => $row['EtudiantActif'],
            'diplome' => $row['diplome'],
            'Principale' => $row['Principale'],
            'LibelleLong' => $row['LibelleLong'],
            'CodeDiplome' => $row['CodeDiplome'],
            'Code' => $row['Code'],
            'EtudiantPayant' => $row['EtudiantPayant'],
            'codediplome1' => $row['codediplome1'],
            'prenom2' => $row['prenom2'],
            'DateNaissance' => $row['DateNaissance'],
            'Site' => $row['Site'],
            'Regimeinscription' => $row['Regimeinscription'],
            'DateInscription' => $row['DateInscription'],
            'DateDossierComplet' => $row['DateDossierComplet'],
            'LieuNaissance' => $row['LieuNaissance'],
            'MotifAdmission' => $row['MotifAdmission'],
            'CIN' => $row['CIN'],
            'NTelelephone' => $row['NTelelephone'],
            'NTel_du_Tuteur' => $row['NTel_du_Tuteur'],
            'Adresse' => $row['Adresse'],
            'Nationalite' => $row['Nationalite'],
            'anneeEtude' => $row['anneeEtude'],
            'Nom_Arabe' => $row['Nom_Arabe'],
            'Prenom_arabe' => $row['Prenom_arabe'],
            'NiveauScolaire' => $row['NiveauScolaire'],
        ]);
    }
}
