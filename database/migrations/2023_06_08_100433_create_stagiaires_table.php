<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagiairesTable extends Migration
{
    public function up()
    {
        Schema::create('stagiaires', function (Blueprint $table) {
            $table->string('id_inscriptionsessionprogramme');
            $table->string('MatriculeEtudiant');
            $table->string('Nom');
            $table->string('Prenom');
            $table->string('Sexe');
            $table->string('EtudiantActif');
            $table->string('diplome');
            $table->string('Principale');
            $table->string('LibelleLong');
            $table->string('CodeDiplome');
            $table->string('Code');
            $table->string('EtudiantPayant');
            $table->string('codediplome1');
            $table->string('prenom2');
            $table->string('DateNaissance');
            $table->string('Site');
            $table->string('Regimeinscription');
            $table->string('DateInscription');
            $table->string('DateDossierComplet');
            $table->string('LieuNaissance');
            $table->string('MotifAdmission');
            $table->string('CIN');
            $table->string('NTelelephone');
            $table->string('NTel_du_Tuteur');
            $table->string('Adresse');
            $table->string('Nationalite');
            $table->string('anneeEtude');
            $table->string('Nom_Arabe');
            $table->string('Prenom_arabe');
            $table->string('NiveauScolaire');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stagiaires');
    }
}
