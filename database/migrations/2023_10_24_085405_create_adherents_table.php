<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adherents', function (Blueprint $table) {
            $table->id();
//            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignIdFor(\App\Models\User::class, 'user_id');
//            $table->unsignedBigInteger('secteur_id')->nullable();
            $table->foreignIdFor(\App\Models\Secteur::class, 'secteur_id')->nullable();
            $table->text('propos')->nullable();
            $table->string('profession')->nullable();
            $table->string('ville')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adherents');
    }
};
