<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postes', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->string('type');
            $table->string('audience')->default("public");
            $table->string('pdf_path')->nullable();
            $table->integer('audience_id')->nullable();
            $table->timestamps();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postes');
    }
};
