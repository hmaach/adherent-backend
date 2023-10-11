<?php

namespace App\Imports;

use App\Models\Stagiaire;
use Maatwebsite\Excel\Concerns\ToModel;

class StagiaresImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Output the $row array for debugging

        $stagiare = new Stagiaire([
            'id_inscriptionsessionprogramme' => $row[0],
            'MatriculeEtudiant' => $row[1],
            'Nom' => $row[2],
            'Prenom' => $row[3],
            'Sexe' => $row[4],
            'EtudiantActif' => $row[5],
            'diplome' => $row[6],
            'Principale' => $row[7],
            'LibelleLong' => $row[8],
            'CodeDiplome' => $row[9],
            'Code' => $row[10],
            'EtudiantPayant' => $row[11],
            'codediplome1' => $row[12],
            'prenom2' => $row[13],
            'DateNaissance' => $row[14],
            'Site' => $row[15],
            'Regimeinscription' => $row[16],
            'DateInscription' => $row[17],
            'DateDossierComplet' => $row[18],
            'LieuNaissance' => $row[19],
            'MotifAdmission' => $row[20],
            'CIN' => $row[21],
            'NTelelephone' => $row[22],
            'NTel_du_Tuteur' => $row[23],
            'Adresse' => $row[24],
            'Nationalite' => $row[25],
            'anneeEtude' => $row[26],
            'Nom_Arabe' => $row[27],
            'Prenom_arabe' => $row[28],
            'NiveauScolaire' => $row[29],
        ]);

        return $stagiare;
    }
}
