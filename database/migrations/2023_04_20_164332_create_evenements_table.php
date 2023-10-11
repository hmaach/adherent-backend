<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->string('titre');
            $table->string('color')->nullable();
            $table->string('oldColor')->nullable();
            $table->string('type')->nullable();
            $table->string('audience')->default('public');
            $table->integer('audience_id')->nullable();
            $table->dateTime('dateDeb');
            $table->dateTime('dateFin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
